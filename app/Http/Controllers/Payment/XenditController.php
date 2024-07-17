<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\API\Xendit\XenditPaymentInvoiceController;
use App\Http\Controllers\Buyer\Xendit\LoanTransaction\XenditLoanTransactionController;
use App\Http\Controllers\Controller;
use App\Jobs\CreateGroupOrderJob;
use App\Jobs\SetExpireOnInvoice;
use App\Models\BulkPayments;
use App\Models\Groups;
use App\Models\GroupTransactions;
use App\Models\LoanApply;
use App\Models\Order;
use App\Models\OrderTransactions;
use App\Models\Disbursements as OrderDisbursements;
use App\Models\PaymentProviderTransaction;
use App\Models\Quote;
use App\Models\XenditPaymentInvoice;
use App\Models\XenditRequestResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Xendit\Disbursements;
use Xendit\Platform;
use Xendit\Xendit;
use Xendit\Invoice;
use Xendit\Balance;
use Log;

class XenditController extends Controller
{
    public function __construct()
    {
        if (config('app.env')=='live') {
            Xendit::setApiKey(getSettingValueByKey('xendit_live_token'));
        }elseif(config('app.env')=='staging'){
            Xendit::setApiKey(getSettingValueByKey('xendit_test_token'));
        }else{
            Xendit::setApiKey(getSettingValueByKey('xendit_dev_token'));
        }
    }

    public function createInvoice(Order $order){
        if (empty(OrderTransactions::invoiceExist($order->id)) && config('app.env')!='local') {
            $params = setInvoice($order);
            if (isset($params['status']) && $params['status']==false){
                return $params;
            }

            $xrr = XenditRequestResponse::createXenditRequestResponse(['type'=>'create_invoice','data'=>json_encode($params)]);

            $data = Invoice::create($params);

            $xrr->xendit_id = $data['id'];
            $xrr->save();

            $data['order_id'] = $order->id;

            return OrderTransactions::createOrUpdateInvoice($data);
        }
    }

    public function createGroupInvoice(Groups $group,Quote $quote,array $groupFinalAmount){
        if (empty(GroupTransactions::invoiceExist($quote->id)) && config('app.env')!='local') {
            $params = setGroupInvoice($group,$quote,$groupFinalAmount);
            if (isset($params['status']) && $params['status']==false){
                return $params;
            }

            $xrr = XenditRequestResponse::createXenditRequestResponse(['type'=>'create_group_invoice','data'=>json_encode($params)]);

            $data = Invoice::create($params);

            $xrr->xendit_id = $data['id'];
            $xrr->save();

            $data['group_id'] = $group->id;
            $data['quote_id'] = $quote->id;

            return GroupTransactions::createOrUpdateInvoice($data);
        }
    }

    public function createSingleInvoiceFromBulk(Order $order){
        if (empty(OrderTransactions::invoiceExist($order->id)) && config('app.env')!='local') {
            $supplierXenId = $order->quote->supplier()->value('xen_platform_id');
            $customer = objToArr($order->user()->first(['firstname','lastname','email','phone_code','mobile']));
            $xenditInvoiceSettings = json_decode(getSettingValueByKey('xendit_invoice'),true);
            $external_id = $order->order_number;

            $generatedLinkCount = $order->orderTransactions()->count();//count = index
            if ($generatedLinkCount>0) {
                $external_id = $order->order_number.'-BLK'.($generatedLinkCount+1);
            }
            $invoiceDuration = $xenditInvoiceSettings['bulk_invoice_valid_hours'] * 3600;//default invoice duration

            if ($order->is_credit==0){
                $cashInvoiceValidHoursArr = $xenditInvoiceSettings['cash_invoice_valid_hours'];
                $generatedLinkCount = $order->orderTransactions()->count();//count = index

                $invoiceValidHours = 0;
                if (isset($cashInvoiceValidHoursArr[$generatedLinkCount])) {
                    $invoiceValidHours = $cashInvoiceValidHoursArr[$generatedLinkCount];//get index wise invoice valid hours
                }
                if ($xenditInvoiceSettings['max_invoice_generate']<=$generatedLinkCount){
                    //for more then max invoice generate
                    $external_id = $order->order_number.'-BLK'.($generatedLinkCount+1);
                }elseif ($generatedLinkCount>0) {
                    $external_id = $order->order_number.'-'.($generatedLinkCount+1);
                    $invoiceDuration = $invoiceValidHours * 3600;//default invoice duration for predefine for cash
                }
            }

            $product = '';
            $quoteItems = $order->quote->quoteItems()->get(['rfq_product_id','product_quantity','price_unit']);
            foreach ($quoteItems as $quoteItem) {
                $product .= '|'.get_product_name_by_id($quoteItem->rfq_product_id,1).' - '.$quoteItem->product_quantity.' '.get_unit_name($quoteItem->price_unit);
            }
            $descr = substr($product,1);
            $params = [
                'external_id' => $external_id,
                'payer_email' => $customer['email'],
                'description' => $descr,
                'amount' => $order->payment_amount,
                'should_send_email' => true,
                'customer' => [
                    'given_names' => $customer['firstname'].' '.$customer['lastname'],
                    'email' => $customer['email'],
                    'mobile_number' => $customer['phone_code'].$customer['mobile'],
                    //'address' => ['110','Afrin Flats','Nawabwada','Raopura'],
                ],
                'invoice_duration' => $invoiceDuration,//86400 sec = 1 day
                'success_redirect_url' => url('success-invoice-payment/'.$order->id),
                'failure_redirect_url' => url('fail-invoice-payment/'.$order->id),
                'reminder_time_unit' => $xenditInvoiceSettings['invoice_reminder_time_unit'],
                'reminder_time' => (int)$xenditInvoiceSettings['invoice_reminder_time'],
            ];
            if ($supplierXenId){
                //payment goes to the supplier
                $params['for-user-id'] = $supplierXenId;
            }
            $xrr = XenditRequestResponse::createXenditRequestResponse(['type'=>'create_invoice','data'=>json_encode($params)]);

            $data = Invoice::create($params);

            $xrr->xendit_id = $data['id'];
            $xrr->save();

            $data['order_id'] = $order->id;

            return OrderTransactions::createOrUpdateInvoice($data);
        }
    }

    public function createBulkInvoice(BulkPayments $bulkPayment){
        if (empty(OrderTransactions::invoiceExist(0,$bulkPayment->id)) && config('app.env')!='local') {
            $xenditInvoiceSettings = json_decode(getSettingValueByKey('xendit_invoice'),true);
            $supplierXenId = $bulkPayment->supplier()->value('xen_platform_id');
            $customer = objToArr($bulkPayment->user()->first(['firstname','lastname','email','phone_code','mobile']));
            $invoiceValidHours = $xenditInvoiceSettings['bulk_invoice_valid_hours'];
            $firstOrderId = $bulkPayment->bulkOrderPayment()->value('order_id');
            $params = [
                'external_id' => $bulkPayment->bulk_payment_number,
                'payer_email' => $customer['email'],
                'description' => $bulkPayment->bulk_payment_number,
                'amount' => $bulkPayment->payable_amount,
                'should_send_email' => true,
                'customer' => [
                    'given_names' => $customer['firstname'].' '.$customer['lastname'],
                    'email' => $customer['email'],
                    'mobile_number' => $customer['phone_code'].$customer['mobile'],
                ],
                'invoice_duration' => $invoiceValidHours * 3600,//3600 sec = 1 hours
                'success_redirect_url' => url('success-invoice-payment/'.$firstOrderId),
                'failure_redirect_url' => url('fail-invoice-payment/'.$firstOrderId),
                'reminder_time_unit' => $xenditInvoiceSettings['invoice_reminder_time_unit'],
                'reminder_time' => (int)$xenditInvoiceSettings['invoice_reminder_time'],
            ];
            if ($supplierXenId){
                //payment goes to the supplier
                $params['for-user-id'] = $supplierXenId;
            }

            $xrr = XenditRequestResponse::createXenditRequestResponse(['type'=>'create_bulk_invoice','data'=>json_encode($params)]);

            $data = Invoice::create($params);

            $xrr->xendit_id = $data['id'];
            $xrr->save();

            $data['bulk_payment_id'] = $bulkPayment->id;

            return OrderTransactions::createOrUpdateInvoice($data);
        }
    }

    public function expireInvoice($data){
        if (config('app.env')!='local') {
            $params = [
                'for-user-id' => $data->user_id // OPTIONAL
            ];
            XenditRequestResponse::createXenditRequestResponse(['type' => 'expire_invoice', 'xendit_id' => $data->invoice_id, 'data' => json_encode($params)]);

            return Invoice::expireInvoice($data->invoice_id, $params);
        }
    }

    public function invoiceCallback(Request $request){

        $data = $request->all();
        Log::info($data);
        $xenResponse = ['type'=>'invoice_callback','xendit_id'=>$data['id'],'data'=>json_encode($data)];

        $external_id = $data['external_id'];

        /**begin: Set Loan Payment Invoice Callback @var  $loanInvoice */
        $loanInvoice = LoanApply::where('loan_number', $external_id)->first();
        if (!empty($loanInvoice)) {
            (new XenditPaymentInvoiceController())->setPaymentInvoiceCallback($data);
        }
        /**end: Set Loan Payment Invoice Callback */


        $groupTransaction = GroupTransactions::where('invoice_id',$data['id'])->first();
        if (!empty($groupTransaction)){
            $xenResponse['type'] = 'group_invoice_callback';
            XenditRequestResponse::createXenditRequestResponse($xenResponse);
            if ($data['status']=='PAID' || $data['status']=='SETTLED') {
                dispatch(new CreateGroupOrderJob($data['id']));
            }
            $data['quote_id'] = $groupTransaction->quote_id??null;
            GroupTransactions::createOrUpdateInvoice($data);
            return 'true';
        }
        XenditRequestResponse::createXenditRequestResponse($xenResponse);
        $isBulkPayment = strpos($external_id,'|');
        if (!$isBulkPayment && strpos($external_id,'-')!=strrpos($external_id,'-')){
            $external_id = substr($external_id,0,strrpos($external_id,'-'));
        }

        if (!$isBulkPayment){
            $order = Order::where('order_number', $external_id)->first(['id']);
            if (!empty($order)) {
                $data['order_id'] = $order->id;
                $orderTransaction = OrderTransactions::createOrUpdateInvoice($data);
                if ($data['status']=='PAID' || $data['status']=='SETTLED') {
                    $adminOrderObj = new OrderController;
                    $adminOrderObj->setOrderStatusChange(3, $order->id,null, $order->order_status);
                    //expire old pending invoices
                    if (empty($orderTransaction->bulk_payment_id)){
                        $activeInvoices = getActiveBulkPaymentInvoices($orderTransaction->order_id);
                        foreach ($activeInvoices as $activeInvoice) {
                            dispatch(new SetExpireOnInvoice($activeInvoice));
                        }
                    }
                }
            }
        }else{
            $bulkPayment = BulkPayments::where('bulk_payment_number', $external_id)->withTrashed()->first(['id', 'user_id', 'supplier_id','order_transaction_id']);

            if (!empty($bulkPayment)) {
                $data['bulk_payment_id'] = $bulkPayment->id;
                $orderTransaction = OrderTransactions::createOrUpdateInvoice($data);
                if ($data['status']=='PAID' || $data['status']=='SETTLED') {
                    $adminOrderObj = new OrderController;

                    foreach ($bulkPayment->bulkOrderPayments()->get() as $bulkOrderPayment){
                        $order = $bulkOrderPayment->order()->first(['id']);
                        $adminOrderObj->setOrderStatusChange(3, $order->id);
                    }

                    if (!empty($orderTransaction->bulk_payment_id)){
                        $orderIds = $orderTransaction->bulkPayment->bulkOrderPayments()->get(['order_id'])->pluck('order_id')->all();
                        if ($orderIds) {
                            $activeInvoices = DB::table('order_transactions')
                                ->where(['status'=>'PENDING'])
                                ->whereIn('order_id',$orderIds)
                                ->get(['id','user_id','invoice_id']);

                            $activeInvoices2 = getActiveBulkPaymentInvoices($orderIds);

                            $activeInvoices->merge($activeInvoices2);
                            //expire old pending invoices
                            foreach ($activeInvoices as $activeInvoice) {
                                dispatch(new SetExpireOnInvoice($activeInvoice));
                            }
                        }
                    }
                }
            }
        }
    }

    public function createDisbursement(Order $order,$payableAmount,$xenRequestType='create_disbursement'){
        if (config('app.env')!='local') {
            $params = setDisbursement($order, $payableAmount);

            $xrr = XenditRequestResponse::createXenditRequestResponse(['type' => $xenRequestType, 'data' => json_encode($params)]);
            $data = Disbursements::create($params);
            $xrr->xendit_id = $data['id'];
            $xrr->save();

            return $data;
        }
    }

    public function createBuyerDisbursement(Order $order,$payableAmount){
        if (config('app.env')!='local') {
            $params = setBuyerDisbursement($order, $payableAmount);

            if ($params==false){
                return $params;
            }

            $xrr = XenditRequestResponse::createXenditRequestResponse(['type' => 'create_buyer_disbursement', 'data' => json_encode($params)]);
            $data = Disbursements::create($params);
            $xrr->xendit_id = $data['id'];
            $xrr->save();

            return $data;
        }
    }

    public function disbursementCallback(Request $request){
        $data = $request->all();
        Log::info($data);
        XenditRequestResponse::createXenditRequestResponse(['type'=>'disbursement_callback','xendit_id'=>$data['id'],'data'=>json_encode($data)]);
        if (isset($data['id'])) {
            $paymentProviderTransaction = PaymentProviderTransaction::where('transfer_id',$data['id'])->first();
            if (!empty($paymentProviderTransaction)){// if disbursement related to loan orders
                $status = trim($data['status']);
                $paymentProviderTransaction->transaction_status = PAYMENT_TRANSACTION_STATUS[$status];
                $paymentProviderTransaction->save();
                //disbursment completed
                if ($status=='COMPLETED'){
                    $loan = $paymentProviderTransaction->related()->first();
                    if (!empty($loan)) {
                        if ($paymentProviderTransaction->transaction_type_id == TRANSACTION_TYPES['DISBURSEMENT_TO_SUPPLIER']) {
                            $loan->disbursed_to_supplier = 1;
                            $loan->save();
                        } elseif ($paymentProviderTransaction->transaction_type_id == TRANSACTION_TYPES['DISBURSEMENT_BLITZNET_TO_KOINWORKS']) {
                            $loan->disbursed_to_koinworks = 1;
                            $loan->save();
                        }
                        PaymentProviderTransaction::bootSystemActivities();
                    }
                }
            } else {
                OrderDisbursements::createOrUpdateDisbursement($data);
            }
        }
    }

    public function batchDisburesments(){
        $batch_params = [
            'for-user-id' => '619b7b7842acae456a3a52f1',
            'reference'=> 'disb_batch-'.time(),
            'disbursements'=> [
                [
                    'amount'=> 90000,
                    'bank_code'=> 'MANDIRI',
                    'bank_account_name'=> 'Steve',
                    'bank_account_number'=> '1121212',
                    'description'=> 'Xen Platform user Batch Disbursement',
                    'external_id'=> 'disbursement-15'
                ],
                [
                    'amount'=> 90000,
                    'bank_code'=> 'MANDIRI',
                    'bank_account_name'=> 'Harry',
                    'bank_account_number'=> '123456',
                    'description'=> 'Xen Platform user Batch Disbursement with email notifications',
                    'external_id'=> 'disbursement-16',
                    'email_to'=> ['munir.bhartisoft1121@gmail.com'],
                    'email_cc'=> ['munircc@yopmail.com'],
                    'email_bcc'=> ['munirbcc@yopmail.com']
                ]
            ]
        ];
        $createBatchDisbursements = Disbursements::createBatch($batch_params);
        return response()->json($createBatchDisbursements);
    }

    public function balanceTransfer(array $data){
        if (config('app.env')!='local') {
            if (empty($data['destination_user_id'])) {
                $data['destination_user_id'] = getSettingValueByKey('xendit_main_account');
            }
            XenditRequestResponse::createXenditRequestResponse(['type'=>'balance_transfer','data'=>json_encode($data)]);
            $res = Platform::createTransfer($data);
            XenditRequestResponse::createXenditRequestResponse(['type'=>'balance_transfer_response','xendit_id'=>$res['transfer_id'],'data'=>json_encode($res)]);
            return $res;
        }
    }

    public function getAvailableBanks(){
        return Disbursements::getAvailableBanks();
    }

    public function setCallbackUrls(Request $request){
        $callbackUrlParams = [
            'for-user-id'=> $request->xen_platform_id,
            'url' => $request->url//'http://13.229.58.92/api/dis-callback'
        ];
        $callbackType = $request->type;//disbursement
        XenditRequestResponse::createXenditRequestResponse(['type'=>'set_callback_url','xendit_id'=>$callbackType,'data'=>json_encode($callbackUrlParams)]);
        $setCallbackUrl = Platform::setCallbackUrl($callbackType, $callbackUrlParams);
        XenditRequestResponse::createXenditRequestResponse(['type'=>'set_callback_url_response','xendit_id'=>$callbackType,'data'=>json_encode($setCallbackUrl)]);
        return response()->json($setCallbackUrl);
    }

    public function getBalance($xenAcountId=''){
        $params = [];
        if ($xenAcountId) {
            $params['for-user-id'] = $xenAcountId; //The sub-account user-id that you want to make this transaction for (Optional).
        }
        $account_type = 'CASH';//CASH, HOLDING, TAX
        return Balance::getBalance($account_type, $params);
    }

    public function getAccount($xenAcountId){
        return Platform::getAccount($xenAcountId);;
    }

    public function createXenAccount(array $data){
        if (config('app.env')!='local') {
            $params = [
                'email' => trim($data['email']),
                'type' => $data['type'] ?? 'OWNED',
                'public_profile' => ['business_name' => trim($data['business_name'])]
            ];
            $xrr = XenditRequestResponse::createXenditRequestResponse(['type' => 'create_xen_account', 'data' => json_encode($params)]);

            $data = Platform::createAccount($params);
            $xrr->xendit_id = $data['id'];
            $xrr->save();

            return $data;
        }
    }

    public function updateXenAccount(array $data){
        if (isset($data['email']) && !empty($data['email'])){
            $updateParams['email'] = $data['email'];
        }
        if (isset($data['business_name']) && !empty($data['business_name'])){
            $updateParams['public_profile']['business_name'] = $data['business_name'];
        }
        if (count($updateParams)) {
            XenditRequestResponse::createXenditRequestResponse(['type'=>'update_xen_account','xendit_id'=>$data['xen_platform_id'],'data'=>json_encode($updateParams)]);
            return Platform::updateAccount($data['xen_platform_id'], $updateParams);
        }
        return [];
    }

    /*
     * Simulate Virtual Account Payment created manually
     * at this time api not available of "xendit-php" package
     * Date:23/09/22
     * */
    public function fvaSimulatePayment($xenPlatformId,$externalId,$amount){
        if (empty($xenPlatformId) || empty($externalId) || empty($amount)){
            return (object)[];
        }
        $params = [
            'amount' => $amount,
        ];
        XenditRequestResponse::createXenditRequestResponse(['type'=>'fva_simulate_payment_request','xendit_id'=>$xenPlatformId,'data'=>json_encode($params)]);
        $data = Http::withBasicAuth(getSettingValueByKey('xendit_test_token'), '')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'for-user-id' => $xenPlatformId,
            ])->post('https://api.xendit.co/callback_virtual_accounts/external_id='.$externalId.'/simulate_payment', $params);
        XenditRequestResponse::createXenditRequestResponse(['type'=>'fva_simulate_payment_response','xendit_id'=>$xenPlatformId,'data'=>json_encode($data)]);
        return $data;
    }

    public function fvaCallback(Request $request){
        $data = $request->all();
        XenditRequestResponse::createXenditRequestResponse(['type'=>'fixed_virtual_account_callback','xendit_id'=>$data['owner_id'],'data'=>json_encode($data)]);
    }

    public function fvaPaidCallback(Request $request){
        $data = $request->all();
        XenditRequestResponse::createXenditRequestResponse(['type'=>'fixed_virtual_account_paid_callback','xendit_id'=>$data['owner_id'],'data'=>json_encode($data)]);
    }

    /* below codes are testing purpose */

    public function getInvoice(Request $request){
        $retrieveParam = [
            'for-user-id' => $request->user_id // OPTIONAL
        ];
        $data = Invoice::retrieve($request->id,$retrieveParam);
        return response()->json(array('success' => true, 'data' => $data));
    }

    public function getDisbursements(Request $request){

        $retrieveParams = [];
        if (isset($request->user_id)) {
            $retrieveParams['for-user-id'] = $request->user_id;
        }
        return response()->json(Disbursements::retrieve($request->id, $retrieveParams));
    }

    public function regerateInvoiceCallback(Request $request){

        $data = json_decode(file_get_contents("http://18.139.226.176/api/get-callback-file/".$request->id),true);
        if (isset($request->is_return)) {
            return response()->json($data);
        }
        //$data = $data['data']['response'];
        $order = Order::where('order_number',$data['external_id'])->first(['id']);
        if (!empty($order)) {

            $data['order_id'] = $order->id;

            //OrderTransactions::createOrUpdateInvoice($data);
            if ($data['status']=='PAID' || $data['status']=='SETTLED') {
                $adminOrderObj = new OrderController;
                $adminOrderObj->setOrderStatusChange(3, $order->id);
            }
        }
        return response()->json($data);
    }

    public function getXenAccount(Request $request){
        return response()->json(Platform::getAccount($request->id));
    }

    public function xenTransfer(){
        $transferParams = [
            'reference' => 'TR-'.time(),
            'amount' => 1230,
            'source_user_id' => '61408058278d147e1e558100',
            'destination_user_id' => '619b7b7842acae456a3a52f1',
        ];
        $createTransfer = Platform::createTransfer($transferParams);
        return response()->json($createTransfer);
    }

    public function getAllInvoice(){
        $retrieveParam = [
            //'for-user-id' => '619b7b7842acae456a3a52f1' // OPTIONAL
        ];
        preDump(Invoice::retrieveAll($retrieveParam));
        return response()->json(array('success' => true, 'data' => Invoice::retrieveAll($retrieveParam)));
    }

    public function successInvoicePayment($orderId){//thank you page
        $data['orderId'] = $orderId;
        $data['page'] = 'success';
        return response()->json(array('success' => true, 'data' => $data));
    }

    public function failInvoicePayment($orderId){//payment failed page
        $data['orderId'] = $orderId;
        $data['page'] = 'fail';
        return response()->json(array('success' => true, 'data' => $data));
    }

    public function createFeeRule(){
        $feeRuleParams = [
            'name' => 'Blitznet Charges',
            'description' => 'Blitznet platform charges',
            'unit' => 'flat',
            'amount' => 200,
            'currency' => 'IDR'
        ];
        $createFeeRule = Platform::createFeeRule($feeRuleParams);
        return response()->json(array('success' => true, 'data' => $createFeeRule));
    }

    public function xenPlatformCallback(Request $request){
        $data['method'] = $request->method();
        $data['response'] = $request->all();
        Storage::disk('local')->put('invoice/xp_'.$request->id.'_'.$request->status.'.txt', json_encode($data));
    }

    public function payLaterCallback(Request $request){
        $data['method'] = $request->method();
        $data['response'] = $request->all();
        Storage::disk('local')->put('invoice/paylat_'.$request->id.'_'.$request->status.'.txt', json_encode($data));
    }

    public function getFiles(){
       return response()->json(array('success' => true, 'data' => Storage::disk('public')->files('invoice')));
    }

    public function getCallback($id){
        $filename = 'invoice/'.$id.'.txt';
        if (Storage::disk('local')->exists($filename)) {
            return response()->json(json_decode(Storage::disk('local')->get($filename)));
        }
        return response()->json(array('success' => false, 'data' => []));
    }

}
