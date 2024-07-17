<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\XenditController;
use App\Http\Requests\Admin\Disbursement\GroupSupplierDisbursementRequest;
use App\Jobs\BlitznetCommissionJob;
use App\Jobs\GroupCommissionJob;
use App\Jobs\SupplierTransactionFeesJob;
use App\Models\CommissionType;
use App\Models\Disbursements;
use App\Models\Groups;
use App\Models\Order;
use App\Models\OtherCharge;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Supplier;
use App\Models\SupplierTransactionCharge;
use App\Models\XenBalanceTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class DisbursementController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create disbursement list|edit disbursement list|delete disbursement list|publish disbursement list|unpublish disbursement list', ['only'=> ['index']]);
        $this->middleware('permission:create disbursement list', ['only' => ['create']]);
        $this->middleware('permission:edit disbursement list', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete disbursement list', ['only' => ['delete']]);
    }


    public function index(){
        $disbursements = DB::table('disbursements')
                        ->join('orders','orders.id','=','disbursements.order_id')
                        ->leftJoin('suppliers','disbursements.user_id','=','suppliers.xen_platform_id')
                        ->get(['disbursements.*','orders.order_number', 'suppliers.name as supplier_company']);

        /**begin: system log**/
        Disbursements::bootSystemView(new Disbursements());
        /**end:  system log**/
        return view('admin/disbursement/index', ['disbursements' => $disbursements]);
    }

    public function getOrderChargesDetails($orderId)
    {
        $order = Order::where('orders.id', $orderId)->first();

        $supplier = $order->supplier()->first(['id','name','contact_person_name','email','contact_person_phone','xen_platform_id']);
        $supplierBank = $supplier->supplierBank()->where('is_primary',1)->with('bankDetail:id,name,logo')->first();
        $lastSupplierTransaction = $supplier->supplierTransactionCharge()->latest()->first(['paid_date','created_at']);
        $paymentReceivedDate = $order->orderTransaction()->where('status','PAID')->value('created_at');

        $quotesCharges['platform_charges'] = $order->quote->quotePlatformCharges()->get();
        $quotesCharges['logistic_charges'] = $order->quote->quoteLogisticCharges()->get();
        $quotesCharges['transaction_charges'] = $order->quote->quotePaymentFees()->where('charge_id',10)->pluck('charge_amount')->first();
        $quote_items = QuoteItem::join('rfq_products', 'quote_items.rfq_product_id', '=', 'rfq_products.id')->where('quote_id', $order->quote_id)->get(['quote_items.*', 'rfq_products.category', 'rfq_products.sub_category', 'rfq_products.product'])->toArray();

        $data = ['order' => $order,
                 'quoteItems'=>$quote_items,
                 'quotes_charges' => $quotesCharges,
                 'supplier'=>$supplier,
                 'supplier_bank'=>$supplierBank,
                 'payment_received_date'=>$paymentReceivedDate?changeDateTimeFormat($paymentReceivedDate):'',
                 'last_supplier_transaction_date'=>isset($lastSupplierTransaction->created_at)?changeDateTimeFormat($lastSupplierTransaction->created_at):'',
                 'supplier_transaction_fees'=>getSupplierTransactionFees($lastSupplierTransaction->paid_date??null)];
        //dd($data);
        return response()->json(array('success' => true, 'data' => $data));
    }

    public function getGroupOrderChargesDetails(Request $request)
    {
        $inputs = $request->all();
        $order = Order::where(['group_id'=>$inputs['group_id'],'id'=>$inputs['order_id']])->first();
        if (empty($order)){
            return response()->json(array('success' => false, 'message' => __('order.order_not_found')));
        }
        $group = $order->group()->first();
        $supplier = $order->supplier()->first(['id','name','contact_person_name','email','contact_person_phone','xen_platform_id']);
        $supplierBank = $supplier->supplierBank()->where('is_primary',1)->with('bankDetail:id,name,logo')->first();
        $lastSupplierTransaction = $supplier->supplierTransactionCharge()->latest()->first(['paid_date','created_at']);
        $paymentReceivedDate = $order->groupTransaction()->where('status','PAID')->value('created_at');

        $quoteItems = QuoteItem::where('quote_id', $order->quote_id)->get();

        $data = [
            'group' => $group,
            'order' => $order,
            'quoteItems'=>$quoteItems,
            'supplier'=>$supplier,
            'supplierBank'=>$supplierBank,
            'paymentReceivedDate'=>$paymentReceivedDate?changeDateTimeFormat($paymentReceivedDate):'',
            'lastSupplierTransactionDate'=>isset($lastSupplierTransaction->created_at)?changeDateTimeFormat($lastSupplierTransaction->created_at):'',
            'totalBlitznetCommissions'=>$group->blitznetCommissions()->sum('paid_amount'),
            'totalGroupPayments'=>$group->order()->sum('payment_amount'),
            'totalDisbursement'=>$group->disbursement()->where('status','COMPLETED')->sum('amount'),
            'supplierTransactionFees'=>getSupplierTransactionFees($lastSupplierTransaction->paid_date??null),
            //'allBuyerRefundAmount' => getAllBuyerRefundAmountByGroup($group->id),
            'platformCharges' => $order->quote->quotePlatformCharges()->get(),
            'logisticCharges' => $order->quote->quoteLogisticCharges()->get(),
            'transactionCharges' => $order->quote->quotePaymentFees()->where('charge_id',10)->pluck('charge_amount')->first()
        ];

        $html = view('admin/group_transaction/group_disbursement_popup', $data)->render();
        return response()->json(array('success' => true, 'order' => $order, 'html' => $html));
    }

    public function settlement(Request $request){
        $inputs = $request->all();
        $order = Order::find($inputs['order_id']);
        $orderTransaction = $order->orderTransaction()->where('status','SETTLED')->orWhere('status','PAID')->first();
        if (empty($orderTransaction)){
            return redirect('admin/transactions')->with('error',__('admin.order_transaction_not_paid_or_offline_settled'));
        }
        $supplier = $order->supplier()->first(['id']);
        $isPrimaryBankExist = $supplier->supplierBank()->where('is_primary',1)->count();
        if (empty($isPrimaryBankExist)){
            return redirect('admin/transactions')->with('error',__('admin.no_primary_account_message'));
        }
        /*$inputs['supplierTransactionFees'] = getSupplierTransactionFees($supplier->supplierTransactionCharge()->orderBy('id','DESC')->value('paid_date'));*/
        $payableAmount = Disbursements::getPayableAmount($order,$inputs);
        $minDisbursementAmount = getMinDisbursementAmount();
        if ($payableAmount<$minDisbursementAmount){
            return redirect('admin/transactions')->with('error',sprintf(__('admin.payable_amount_greater'),$minDisbursementAmount));
        }

        /*get buyer side blitznet commission*/
        $blitznetCommission = $order->quote->quotePaymentFees()->where('charge_id',(config('app.env')=='live'?13:18))->pluck('charge_amount')->first();

        $xendit = new XenditController;
        $data = $xendit->createDisbursement($order,$payableAmount);
        if (isset($data['id'])) {
            $data['order_id'] = $inputs['order_id'];
            $data['bank_account_number'] = $supplier->supplierBank()->where('is_primary',1)->value('bank_account_number');
            $disbursements = Disbursements::createOrUpdateDisbursement($data);
            /*if ($inputs['supplierTransactionFees']>0){
                dispatch(new SupplierTransactionFeesJob($supplier->id,$disbursements->id));
            }*/
            if (!empty($blitznetCommission)&&$blitznetCommission>0){
                dispatch(new BlitznetCommissionJob([
                    'commission_type_id'=>CommissionType::BLITZNET_COMMISSION,
                    'supplier_id'=>$supplier->id,
                    'disbursement_id'=>$disbursements->id,
                    'order_id'=>$data['order_id'],
                    'reference'=>'BC-'.$data['order_id'] . '/D'. $disbursements->id
                ],$blitznetCommission));
            }
            return redirect('admin/disbursements')->with('success', __('admin.disbursement_successful'));
        }else{
            return redirect('admin/transactions')->with('error', __('admin.something_error_message'));
        }
    }

    public function groupSupplierSettlement(GroupSupplierDisbursementRequest $request){
        $inputs = $request->all();
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        $order = Order::find($inputs['order_id']);
        $orderTransaction = $order->groupTransaction()->where('status','SETTLED')->orWhere('status','PAID')->first();
        $redirectUrl = 'admin/group-transactions';
        if (empty($orderTransaction)){
            return redirect($redirectUrl)->with('error',__('admin.order_transaction_not_paid_or_offline_settled'));
        }

        $groupId = $order->group_id;
        $supplier = $order->supplier()->first(['id']);
        $isPrimaryBankExist = $supplier->supplierBank()->where('is_primary',1)->count();
        if (empty($isPrimaryBankExist)){
            return redirect($redirectUrl)->with('error', __('admin.no_primary_account_message'));
        }
        if (isset($inputs['supplier_transaction_fees'])) {
            $inputs['supplier_transaction_fees'] = getSupplierTransactionFees($supplier->supplierTransactionCharge()->orderBy('id', 'DESC')->value('paid_date'));
        }else{
            $inputs['supplier_transaction_fees'] = 0;
        }
        //calculate final disburse amount
        $finalDisburseData = Disbursements::getGroupPayableAmount($order,$inputs,1);

        if ($inputs['disbursement_amount_type']==1){//for flat amount disbursement
            $payableAmount = (int)$inputs['final_disburse_amount'];
        }else{//percentage wise disbursement
            $payableAmount = $finalDisburseData['total'];
        }

        /*get buyer side blitznet commission*/
        $blitznetCommission = $order->quote->quotePaymentFees()->where('charge_id',(config('app.env')=='live'?13:18))->pluck('charge_amount')->first();

        $xendit = new XenditController;
        $data = $xendit->createDisbursement($order,$payableAmount,'create_group_supplier_disbursement');
        if (isset($data['id'])) {
            $data['order_id'] = $inputs['order_id'];
            $data['group_id'] = $groupId;
            $data['bank_account_number'] = $supplier->supplierBank()->where('is_primary',1)->value('bank_account_number');
            $disbursements = Disbursements::createOrUpdateDisbursement($data);
            if ($inputs['supplier_transaction_fees']>0){
                dispatch(new SupplierTransactionFeesJob($supplier->id,$disbursements->id));
            }
            if ($finalDisburseData['commission']>0){
                dispatch(new GroupCommissionJob($disbursements->id,$finalDisburseData['commission'],(int)$inputs['blitznet_commission_type'],(float)$inputs['blitznet_commission_per']));
            }
            if (!empty($blitznetCommission)&&$blitznetCommission>0){
                dispatch(new BlitznetCommissionJob([
                    'commission_type_id'=>CommissionType::BLITZNET_COMMISSION,
                    'supplier_id'=>$supplier->id,
                    'disbursement_id'=>$disbursements->id,
                    'order_id'=>$data['order_id'],
                    'reference'=>'BC-'.$data['order_id'] . '/D'. $disbursements->id
                ],$blitznetCommission));
            }
            return redirect('admin/disbursements')->with('success', __('admin.disbursement_successful'));
        }else{
            return redirect($redirectUrl)->with('error', __('admin.something_error_message'));
        }
    }

    public function buyerRefund(Request $request){
        $inputs = $request->all();

        $order = Order::find($inputs['order_id']);
        if (empty($order) || empty($order->group_id)){
            return redirect('admin/groups-list')->with('error', __('admin.something_error_message'));
        }
        $redirectUrl = 'admin/group-edit/'.Crypt::encrypt($order->group_id);
        $orderTransaction = $order->groupTransaction()->where('status','SETTLED')->orWhere('status','PAID')->count();
        if (empty($orderTransaction)){
            return redirect($redirectUrl)->with('error',__('admin.order_transaction_not_paid_or_offline_settled'));
        }
        $buyerDisbursement = $order->disbursement()->where('status','COMPLETED')->where('buyer_user_id',$order->user_id)->first();
        if (!empty($buyerDisbursement)){
            return redirect($redirectUrl)->with('error',__('admin.buyer_refund_already_completed'));
        }
        $groupStatus = $order->group->group_status;

        if ($groupStatus != Groups::CLOSED){
            return redirect($redirectUrl)->with('error',__('admin.group_not_close_yet'));
        }
        $groupId = $order->group_id;
        $buyerRefundAmount = getBuyerRefundAmount($order->quote_id);
        $payableAmount = $buyerRefundAmount-getDisbursementCharge();

        $buyer = $order->user()->first(['id']);
        $isPrimaryBankExist = $buyer->buyerBank()->where('is_primary',1)->count();
        if (empty($isPrimaryBankExist)){
            return redirect($redirectUrl)->with('error', __('admin.buyer_no_primary_account_message'));
        }

        $xendit = new XenditController;
        $data = $xendit->createBuyerDisbursement($order,$payableAmount);
        if (isset($data['id'])) {
            $data['order_id'] = $inputs['order_id'];
            $data['group_id'] = $groupId;
            $data['buyer_user_id'] = $order->user_id;
            $data['bank_account_number'] = $buyer->buyerBank()->where('is_primary',1)->value('account_number');
            Disbursements::createOrUpdateDisbursement($data);
            return redirect($redirectUrl)->with('success', __('admin.disbursement_successful'));
        }else{
            return redirect($redirectUrl)->with('error', __('admin.something_error_message'));
        }
    }

}
