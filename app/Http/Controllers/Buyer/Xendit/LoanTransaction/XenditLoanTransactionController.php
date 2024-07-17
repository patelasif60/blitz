<?php

namespace App\Http\Controllers\Buyer\Xendit\LoanTransaction;

use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\API\Xendit\XenditApiController;
use App\Http\Controllers\API\Xendit\XenditPaymentInvoiceController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\XenditController;
use App\Jobs\BlitznetCommissionJob;
use App\Jobs\buyer\Credit\KoinWorks\KoinworksLoanLateFeeNotifyJob;
use App\Jobs\Credit\KoinWorks\LoanBlitznetCommissionJob;
use App\Models\BankDetails;
use App\Models\CommissionType;
use App\Models\Disbursements;
use App\Models\LoanApply;
use App\Models\LoanProvider;
use App\Models\LoanProviderCharges;
use App\Models\LoanTransaction;
use App\Models\Notification;
use App\Models\Order;
use App\Models\PaymentProvider;
use App\Models\PaymentProviderAccount;
use App\Models\PaymentProviderTransaction;
use App\Models\Supplier;
use App\Models\User;
use App\Models\XenditRequestResponse;
use Carbon\Carbon;
use GPBMetadata\Google\Api\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class XenditLoanTransactionController extends Controller
{
    private $xenditApiContrller;

    /**
     * XenditLoanTransactionController constructor.
     */
    public function __construct( )
    {
        $this->xenditApiContrller = new XenditApiController();
    }

    /**
     * Generate Xendit Loan Repayment Link
     *
     * @param LoanApply $loan
     */
    public function generateXenditPaymentLink(LoanApply $loan)
    {
        try {

            $redirectModel = Crypt::encrypt(LoanApply::class);
            $redirectModelId = Crypt::encrypt($loan->id);

            $expiredTime = Carbon::createFromTime('23','59','59')->format('Y-m-d H:i:s');
            $expiredSeconds = Carbon::now()->diffInSeconds($expiredTime);

            $repaymentAccount = PaymentProviderAccount::koinworksXenDebit();

            $xenditInvoiceSettings  = json_decode(getSettingValueByKey('xendit_invoice'),true);
            $senderDetail           = User::findOrFail($loan->user_id);
            $recieverAccountId      = $repaymentAccount->payment_provider_ac_id;
            $blitznetInvoiceNumber  = $loan->loan_number; // Loan Number
            $invoiceDuration        = $expiredSeconds; // Invoice durations are in seconds
            $description            = $loan->loan_number; // Get xendit description
            $payment_amount         = $loan->loan_repay_amount; // Loan repay amount
            $successCallbackUrl     = url('xendit/payment/success/'.$redirectModel.'/'.$redirectModelId); // Where should redirect after success
            $failurCallbackUrl      = url('xendit/payment/fail/'.$redirectModel.'/'.$redirectModelId); // Where should redirect after failure
            $reminderTimeUnit       = $xenditInvoiceSettings['invoice_reminder_time_unit'];
            $reminderTime           = (int)$xenditInvoiceSettings['invoice_reminder_time'];

            $params = [
                'for-user-id'           =>  $recieverAccountId,
                'external_id'           =>  $blitznetInvoiceNumber,
                'payer_email'           =>  $senderDetail->email,
                'description'           =>  $description,
                'amount'                =>  $payment_amount,
                'should_send_email'     =>  true,
                'customer'              =>  [
                    'given_names' => $senderDetail->firstname.' '.$senderDetail->lastname,
                    'email' => $senderDetail->email,
                    'mobile_number' => $senderDetail->phone_code.$senderDetail->mobile,
                ],
                'invoice_duration'      =>  $invoiceDuration,
                'success_redirect_url'  =>  $successCallbackUrl,
                'failure_redirect_url'  =>  $failurCallbackUrl,
                'reminder_time_unit'    =>  $reminderTimeUnit,
                'reminder_time'         =>  $reminderTime

            ];

            /**begin: Log xendit request @var  $xenditRequest */
            $xenditRequest = XenditRequestResponse::createXenditRequestResponse(['type'=>'create_invoice','data'=>json_encode($params)]);
            /**end: Log xendit request @var  $xenditRequest */

            $data = $this->xenditApiContrller->getInvoice($params,'single');

            if (!empty($data) && Arr::exists($data,'id')) {
                $xenditInvoiceParams = [
                    'related_to_type'   =>  LoanApply::class,
                    'related_to_id'     =>  $loan->id,
                    'xendit_id'         =>  $data['id'],
                    'payment_link'      =>  $data['invoice_url'],
                    'expiry_date'       =>  $data['expiry_date'],
                    'response'          =>  json_encode($data)
                ];

                $xenditInvoice = (new XenditPaymentInvoiceController())->create($xenditInvoiceParams);

                if ($xenditInvoice) {

                    /**begin: Update xendit log request @var  $xenditRequest */
                    $xenditRequest->xendit_id = $data['id'];
                    $xenditRequest->save();
                    /**end: Update xendit log request @var  $xenditRequest */

                    return response()->json(['success' => true, 'message' => 'Data fetched', 'data' => $data['invoice_url']]);
                }
            }

        } catch (\Exception $exception) {
            Log::critical('Code - 503 | ErrorCode:B025 Generate Payment Link');

            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong'), 'data' => []]);

        }
        Log::critical('Code - 503 | ErrorCode:B026 Generate Payment Link');

        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong'), 'data' => []]);


    }

    /**
     * Get xendit buyer payment response
     *
     */
    public function syncXenditPaymentStatus($status,$model,$model_id)
    {
        try {
            $model = Crypt::decrypt($model);
            $model_id = Crypt::decrypt($model_id);

            if ($model==LoanApply::class) {
                $modelObj = LoanApply::findOrFail($model_id);
            }

            if (!empty($modelObj)) {

                return (new XenditPaymentInvoiceController())->getPaymentInvoiceStatus($status,$model,$model_id);

            }


        } catch (\Exception $exception) {

            abort('404');
        }

        abort('404');

    }

    /**
     * Disbursed to Koinworks After Re-Payment
     *
     * @param LoanApply $loan
     * @return \Illuminate\Http\JsonResponse
     */
    public function disbursementToKoinworks(LoanApply $loan)
    {
        try {

            $koinworksBank = BankDetails::where('loan_provider_id', LoanProvider::KOINWORKS)->where('is_deleted', 0)->where('status', 1)->first();

            $payableAccount = PaymentProviderAccount::koinworksXenDebit();

            if (!empty($koinworksBank)) {

                $recieverAccountId          =   $payableAccount->payment_provider_ac_id;
                $blitznetInvoiceNumber      =   'LOAN-DISB-'.$loan->id; // Loan Id
                $payableAmount              =   $this->getKoiwnorksDisbursementAmount($loan);
                $bankCode                   =   $koinworksBank->bank_code;
                $payableAccountHolderName   =   $koinworksBank->ac_name;
                $payableAccountNumber       =   $koinworksBank->ac_no;
                $description                =   'Blitznet Loan '.$loan->provider_loan_id.' Disbursement | LOAN-DISB-'.$loan->id;

                $params = [
                    'for-user-id'           =>  $recieverAccountId,
                    'external_id'           =>  $blitznetInvoiceNumber,
                    'amount'                =>  round($payableAmount),
                    'bank_code'             =>  $bankCode,
                    'account_holder_name'   =>  $payableAccountHolderName,
                    'account_number'        =>  $payableAccountNumber,
                    'description'           =>  $description,
                ];

                /**begin: Log xendit request @var  $xenditRequest */
                $xenditRequest = XenditRequestResponse::createXenditRequestResponse(['type' => 'create_disbursement', 'data' => json_encode($params)]);
                /**end: Log xendit request @var  $xenditRequest */

                $data = $this->xenditApiContrller->requestDisbursement($params);

                if (!empty($data) && Arr::exists($data, 'id')) {
                    DB::transaction(function () use(&$loan, &$koinworksBank,&$data){
                        PaymentProviderTransaction::create([
                            'payment_provider_id'       =>  PAYMENT_PROVIDERS['XENDIT'],
                            'users_type'                =>  User::class,
                            'user_id'                   =>  $loan->user_id,
                            'company_id'                =>  $loan->company_id,
                            'transaction_type_id'       =>  TRANSACTION_TYPES['DISBURSEMENT_BLITZNET_TO_KOINWORKS'],
                            'credit_ac_id'              =>  $koinworksBank->ac_no,
                            'credit_ac_type'            =>  LoanProvider::KOINWORKSTEXT,
                            'debit_ac_id'               =>  getSettingValueByKey('xendit_main_account'),
                            'debit_ac_type'             =>  getSettingValueByKey('app_name'),
                            'transfer_id'               =>  $data['id'],
                            'related_type'              =>  LoanApply::class,
                            'related_id'                =>  $loan->id,
                            'amount'                    =>  $data['amount'],
                            'response_by_provider'      =>  json_encode($data),
                            'created_type'              =>  User::class,
                            'created_id'                =>  \Auth::check() ? \Auth::user()->id : '1'
                        ]);

                        LoanApply::where('id',$loan->id)->update([
                            'disbursed_to_koinworks' => 1
                        ]);
                    });

                    PaymentProviderTransaction::bootSystemActivities();

                    $disburseData = [
                        'user_activity' => 'Blitznet to Koinworks disbursed',
                        'translation_key' => 'disbursement_blitznet_to_koinworks',
                        'type_id' => $loan->id,
                        'loan_number' => $loan->loan_number,
                        'status' => $loan->loanStatus->status_display_name
                    ];
                    (new NotificationController)->addLoanNotification($disburseData); // send notification
                }

                /**begin: Update xendit log request @var  $xenditRequest */
                $xenditRequest->xendit_id = $data['id'];
                $xenditRequest->save();
                /**end: Update xendit log request @var  $xenditRequest */

                Log::info('Code 200 | Success:B001 Disbursement Koinworks');
                return response()->json(['success' => true, 'message' => 'Data fetched', 'data' => $data]);

            }

        } catch(\Exception $exception) {
            Log::critical('Code 500 | ErrorCode:B023 Disbursement Koinworks');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong'), 'data' => []]);

        }

        Log::critical('Code 400 | ErrorCode:B020 Disbursement Koinworks');
        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong'), 'data' => []]);

    }

    /**
     * Koinworks payable amount
     *
     * @param LoanApply $loan
     * @return float
     */
    public function getKoiwnorksDisbursementAmount(LoanApply $loan)
    {
        $totalLoanAmount = (float)$loan->loan_confirm_amount;
        $totalInterest = (float)$loan->interest;
        $totalLateFee = 0.00;

        $loanFees = LoanTransaction::where('id', $loan->id)->where('transaction_type_id', LOAN_PROVIDER_CHARGE_TYPE['LATE_FEE'])->get();

        foreach ($loanFees as $loanFee) {
            $totalLateFee += (float)$loanFee->transaction_amount;
        }

        $totalPaybleAmount = $totalLoanAmount+$totalInterest+$totalLateFee;

        return $totalPaybleAmount;
    }

    /**
     * Koinworks Loan balanced amount Disbursement to Blitznet Xendit V/A account
     * e.g Internal Charges
     *
     * @param LoanApply $loan
     * @return \Illuminate\Http\JsonResponse
     */
    public function internalTransferToBlitznet(LoanApply $loan)
    {
        try {

            $payableAccount = PaymentProviderAccount::koinworksXenDebit();

            $payableAmount = $this->getBlitznetDisbursementAmount($loan);

            if (!empty($payableAccount) && ($payableAmount>0.00)) {

                $sourceAccountId            =   $payableAccount->payment_provider_ac_id;
                $destinationAccountId       =   getSettingValueByKey('xendit_main_account');
                $blitznetInvoiceNumber      =   'BIC-'.$loan->id; // Loan Id

                $params = [
                    'source_user_id'        =>  $sourceAccountId,
                    'reference'             =>  $blitznetInvoiceNumber,
                    'amount'                =>  round($payableAmount),
                    'destination_user_id'   =>  $destinationAccountId,

                ];

                /**begin: Log xendit request @var  $xenditRequest */
                $xenditRequest = XenditRequestResponse::createXenditRequestResponse(['type' => 'create_internal_transfer', 'data' => json_encode($params)]);
                /**end: Log xendit request @var  $xenditRequest */

                $data = $this->xenditApiContrller->requestInternalTransfer($params);

                if (!empty($data) && Arr::exists($data, 'id')) {
                    PaymentProviderTransaction::create([
                        'payment_provider_id'       =>  PAYMENT_PROVIDERS['XENDIT'],
                        'users_type'                =>  User::class,
                        'user_id'                   =>  $loan->user_id,
                        'company_id'                =>  $loan->company_id,
                        'transaction_type_id'       =>  TRANSACTION_TYPES['INTERNAL_CHARGES_TRANSFER_TO_BLITZNET'],
                        'credit_ac_id'              =>  getSettingValueByKey('xendit_main_account'),
                        'credit_ac_type'            =>  getSettingValueByKey('app_name'),
                        'debit_ac_id'               =>  $sourceAccountId,
                        'debit_ac_type'             =>  LoanProvider::KOINWORKSTEXT,
                        'transfer_id'               =>  $data['id'],
                        'related_type'              =>  LoanApply::class,
                        'related_id'                =>  $loan->id,
                        'amount'                    =>  $data['amount'],
                        'response_by_provider'      =>  json_encode($data),
                        'created_type'              =>  User::class,
                        'created_id'                =>  \Auth::check() ? \Auth::user()->id : '1'
                    ]);

                    PaymentProviderTransaction::bootSystemActivities();
                }

                /**begin: Update xendit log request @var  $xenditRequest */
                $xenditRequest->xendit_id = $data['transfer_id'];
                $xenditRequest->save();
                /**end: Update xendit log request @var  $xenditRequest */

                Log::info('Code 200 | Success:B003 Loan Balance Amount Disbursed To Blitznet ');
                return response()->json(['success' => true, 'message' => 'Data fetched', 'data' => $data]);

            }

            if (!$payableAmount>0) {
                Log::critical('Code 200 | Success:B004 Loan Balance Amount is 0.00, It can not Disbursed To Blitznet');
                return response()->json(['success' => true, 'message' => 'Data fetched', 'data' => []]);

            }

        } catch(\Exception $exception) {
            Log::critical('Code 500 | ErrorCode:B027 Loan Balance Amount Disbursed To Blitznet');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong'), 'data' => []]);

        }

        Log::critical('Code 400 | ErrorCode:B028 Loan Balance Amount Disbursed To Blitznet');
        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong'), 'data' => []]);

    }

    /**
     * Blitznet payable amount after re-payment
     *
     * @param LoanApply $loan
     * @return float
     */
    public function getBlitznetDisbursementAmount(LoanApply $loan)
    {

        $internalCharges = LoanProviderCharges::where('charges_type_id',LoanProviderCharges::INTERNAL_CHARGES)->pluck('value')->first();

        return (int)$internalCharges ?? 0;

    }

    /**
     * credit xen ac to supplier xen ac transfer
     *
     * @param LoanApply $loan
     * @return bool
     */
    public function creditXenAcToSupplierXenAcTransfer(LoanApply $loan, Order $order, Supplier $supplier,$loanStatus,$isCron)
    {

        try {
            if ($order->payment_status == 3){//if already transfer
                return true;
            }
            /*get loan credit xen account*/
            $sourceUser = PaymentProviderAccount::find(PAYMENT_PROVIDER_ACCOUNT['CREDIT'])->first(["id","name","payment_provider_ac_id","description"]);
            $xendit = new XenditController;
            /* this code will generate fva simulate payment for beta server */
            if (config('app.env') == 'staging') {
                $externalId = json_decode($sourceUser->description, true)['external_id'] ?? '';
                if ($externalId) {
                    $xendit->fvaSimulatePayment($sourceUser->payment_provider_ac_id, $externalId, $loan->loan_confirm_amount);
                    sleep(1);//it will take 1 second delayed for successfully fva simulate payment
                }
            }

            /*get buyer side blitznet commission*/
            $blitznetCommission = $order->quote->quotePaymentFees()->where('charge_id',(config('app.env')=='live'?13:18))->pluck('charge_amount')->first();
            //transfer amount = (confirm amount - blitznet commission) - xedit money in amount
            $transferAmount = round(($loan->loan_confirm_amount - $blitznetCommission) - XEN_MONEY_IN_AMOUNT);
            //set parameters for xendit
            $btData = [
                'reference' => 'CSPTRF/'.strtotime('now').'/'.$loan->id,
                'amount' => $transferAmount,
                'destination_user_id' => $supplier->xen_platform_id,
                'source_user_id' => $sourceUser->payment_provider_ac_id,
            ];

            //xendit api call
            $result = $xendit->balanceTransfer($btData);

            $paymentTransData = [
                'users_type' => User::class,
                'users_id' => $loan->user_id,
                'company_id' => $loan->company_id,
                'payment_provider_id' => PAYMENT_PROVIDERS['XENDIT'], // xendit - foreign key from payment_providers table
                'transaction_status' => PAYMENT_TRANSACTION_STATUS['COMPLETED'], // completed - loan_status
                'transaction_type_id' => TRANSACTION_TYPES['INTERNAL_TRANSFER_XEN'], // Disbursement koinworks to blitznet  - transaction_type table
                'credit_ac_id' => $supplier->xen_platform_id, // need to confirm // borrower account id
                'credit_ac_type' => $supplier->name, // need to confirm // borrower account name
                'debit_ac_id' => $sourceUser->payment_provider_ac_id, //  koinworks account id
                'debit_ac_type' => $sourceUser->name, // koinworks
                'transfer_id' => $result['transfer_id']??'',// xendit transfer id
                'related_type' => LoanApply::class, // eg Loan::class, Order::class
                'related_id' => $loan->id, // modal id
                'amount' => $transferAmount, // need to confirm
                'response_by_provider' => json_encode($result??''), // api response
                'created_type' => User::class, // morph model name
                'created_id' =>  \Auth::check() ? \Auth::user()->id : '1'
            ];

            $loanTransData = [
                'loan_id' => $loan->id,
                'order_id' => $loan->order_id,
                'applicant_id' => $loan->applicant_id,
                'application_id' => $loan->application_id,
                'users_type' => User::class, // morph user class
                'users_id' => \Auth::check() ? \Auth::user()->id : '1', // morph model id
                'company_id' => $loan->company_id,
                'transaction_reference_id' => $result['transfer_id']??'',
                'transaction_amount' => $transferAmount, // need to confirm , nullable
                'transaction_status' => LOAN_STATUS['COMPLETED'], // completed - loan_status
                'transaction_ac_type' =>TRANSACTION_AC_TYPE['DEBIT'],
                'transaction_type_id' => TRANSACTION_TYPES['INTERNAL_TRANSFER_XEN'], // Disbursement koinworks to blitznet  - transaction_type table
                'remarks' => 'Xendit Credit ac debit and Supplier Xen platform ac Credit',
            ];
            //if api call failed
            if (empty($result)){
                $paymentTransData['transaction_status'] = PAYMENT_TRANSACTION_STATUS['FAILED'];
                $loanTransData['transaction_status'] = LOAN_STATUS['FAILED'];
            }else{//successfully transfer
                $order->payment_status = 3;//loan provider paid
                $order->payment_date = Carbon::now();
                $order->save();
                //blitznet commission
                if ($blitznetCommission>0) {
                    dispatch(new LoanBlitznetCommissionJob($order, $sourceUser, $blitznetCommission));
                }
            }

            $loanTransRes = LoanTransaction::updateOrCreate($loanTransData);
            $loanTransRes->bootSystemActivities();

            $paymentTransRes = PaymentProviderTransaction::updateOrCreate($paymentTransData);
            $paymentTransRes->bootSystemActivities();
            if (empty($result)){
                if (!empty($isCron)) {
                    $transferData = [
                        'user_activity' => 'Supplier xendit amount transfer failed',
                        'translation_key' => 'supplier_xendit_transfer_failed',
                        'type_id' => $loan->id,
                        'loan_number' => $loan->loan_number,
                        'status' => $loanStatus
                    ];
                    (new NotificationController)->addLoanNotification($transferData); // send notification
                }
                return false;
            }
            return true;
        }catch (\Exception $e){

            if (!empty($isCron)) {
                $transferData = [
                    'user_activity' => 'Supplier xendit amount transfer failed',
                    'translation_key' => 'supplier_xendit_transfer_failed',
                    'type_id' => $loan->id,
                    'loan_number' => $loan->loan_number,
                    'status' => $loanStatus
                ];
                (new NotificationController)->addLoanNotification($transferData); // send notification
            }
            return false;
        }
    }

    /**
     * Supplier Disbursement
     *
     * @param LoanApply $loan
     * @return \Illuminate\Http\JsonResponse or boolean
     */
    public function supplierDisbursement(LoanApply $loan, $isCron=1)
    {
        try {
            $order = $loan->orders()->first();
            $supplier = $order->supplier()->first(['id','name','contact_person_email','alternate_email','xen_platform_id']);
            $loanStatus = $loan->loanStatus->status_display_name;

            if($order->order_status==4) {//if order status is  order in progress
                $orderController = new OrderController();
                $orderController->setOrderStatusChange(3, $order->id, []);//change status to paid
            }
            //check xen account exist or not
            if (empty($supplier->xen_platform_id)){
                if (!empty($isCron)) {

                    $xenData = [
                        'user_activity' => 'There is no Xen-account for the supplier',
                        'translation_key' => 'create_xen_account_for_supplier',
                        'type_id' => $loan->id,
                        'loan_number' => $loan->loan_number,
                        'status' => $loanStatus
                    ];
                    (new NotificationController)->addLoanNotification($xenData); // send notification

                    return false;
                } else {
                    return response()->json(['success' => false, 'message' => __('admin.create_xen_account_for_supplier')]);
                }
            }
            //check is amount not disburse from koinworks when request is not from cron
            if (empty($isCron)) {
                $isKoinworkDisburse = $loan->paymentProviderTransaction()->where(['transaction_status' => PAYMENT_TRANSACTION_STATUS['COMPLETED'], 'transaction_type_id' => TRANSACTION_TYPES['DISBURSMENT_KOINWORKS_TO_BLITZNET']])->count();
                if (empty($isKoinworkDisburse)) {
                    return response()->json(['success' => false, 'message' => __('admin.no_disburse_from_koinworks')]);
                }
            }

            //check is amount transfer to supplier xen ac
            $isTransferToSupplier = $loan->paymentProviderTransaction()->where(['transaction_status'=>PAYMENT_TRANSACTION_STATUS['COMPLETED'],'transaction_type_id'=>TRANSACTION_TYPES['INTERNAL_TRANSFER_XEN']])->count();
            if ($order->payment_status != 3 && empty($isTransferToSupplier)){
                $res = $this->creditXenAcToSupplierXenAcTransfer($loan,$order,$supplier,$loanStatus,$isCron);
                if (empty($isCron) && $res === false){
                    return response()->json(['success' => false, 'message' => __('admin.not_transfer_to_supplier_xen_ac')]);
                }elseif ($res === false){
                    return $res;
                }
            }

            $supplierBank = $supplier->supplierBank()->where('is_primary',1)->first(['bank_id','bank_account_name','bank_account_number']);
            if (empty($supplierBank)){
                if (!empty($isCron)) {

                    $bankData = [
                        'user_activity' => 'Supplier primary bank account not exist',
                        'translation_key' => 'supplier_primary_bank_not_exist',
                        'type_id' => $loan->id,
                        'loan_number' => $loan->loan_number,
                        'status' => $loanStatus
                    ];
                    (new NotificationController)->addLoanNotification($bankData); // send notification

                    return false;
                } else {
                    return response()->json(['success' => false, 'message' => __('admin.no_primary_account_message')]);
                }
            }

            $payableAmount = $order->quote->supplier_final_amount;
            $minDisbursementAmount = getMinDisbursementAmount();
            if ($payableAmount<$minDisbursementAmount){
                if (!empty($isCron)) {
                    $amountData = [
                        'user_activity' => 'Supplier payable amount is less',
                        'translation_key' => 'supplier_payable_amount_is_less',
                        'type_id' => $loan->id,
                        'loan_number' => $loan->loan_number,
                        'status' => $loanStatus
                    ];
                    (new NotificationController)->addLoanNotification($amountData); // send notification
                    return false;
                } else {
                    return response()->json(['success' => false, 'message' => sprintf(__('admin.payable_amount_greater'),$minDisbursementAmount)]);
                }
            }

            $customerCompanyName        =   $order->user->company->name;
            $recieverAccountId          =   $supplier->xen_platform_id;
            $blitznetInvoiceNumber      =   'DISB-'.$order->id; // order disburse Id
            $bankCode                   =   $supplierBank->bankDetail->code;
            $payableAccountHolderName   =   $supplierBank->bank_account_name;
            $payableAccountNumber       =   $supplierBank->bank_account_number;
            $description                =   'B'.$order->id.' '.$customerCompanyName;
            $notifyEmailTo              =   $supplier->contact_person_email;
            $ccEmailTo                  =   $supplier->alternate_email;

            $params = [
                'for-user-id'           =>  $recieverAccountId,
                'external_id'           =>  $blitznetInvoiceNumber,
                'amount'                =>  round($payableAmount),
                'bank_code'             =>  $bankCode,
                'account_holder_name'   =>  $payableAccountHolderName,
                'account_number'        =>  $payableAccountNumber,
                'description'           =>  $description,
                'email_to'              =>  [$notifyEmailTo],
            ];
            if (!empty($ccEmailTo)){
                $params['email_cc'] = [$ccEmailTo];
            }

            /**begin: Log xendit request @var  $xenditRequest */
            $xenditRequest = XenditRequestResponse::createXenditRequestResponse(['type'=>'create_disbursement','data'=>json_encode($params)]);
            /**end: Log xendit request @var  $xenditRequest */

            $data = $this->xenditApiContrller->requestDisbursement($params);

            if (!empty($data) && Arr::exists($data,'id')) {
                /**begin: Update xendit log request @var  $xenditRequest */
                $xenditRequest->xendit_id = $data['id'];
                $xenditRequest->save();
                /**end: Update xendit log request @var  $xenditRequest */
                PaymentProviderTransaction::create([
                    'payment_provider_id'       =>  PAYMENT_PROVIDERS['XENDIT'],
                    'users_type'                =>  User::class,
                    'users_id'                  =>  $loan->user_id,
                    'company_id'                =>  $loan->company_id,
                    'transaction_status'        =>  PAYMENT_TRANSACTION_STATUS['PENDING'],
                    'transaction_type_id'       =>  TRANSACTION_TYPES['DISBURSEMENT_TO_SUPPLIER'],
                    'credit_ac_id'              =>  $payableAccountNumber,
                    'credit_ac_type'            =>  $payableAccountHolderName,
                    'debit_ac_id'               =>  $recieverAccountId,
                    'debit_ac_type'             =>  $supplier->name,
                    'transfer_id'               =>  $data['id'],
                    'related_type'              =>  LoanApply::class,
                    'related_id'                =>  $loan->id,
                    'amount'                    =>  $data['amount'],
                    'response_by_provider'      =>  json_encode($data),
                    'created_type'              =>  User::class,
                    'created_id'                =>  \Auth::check() ? \Auth::user()->id : '1'
                ]);

                if (empty($isCron)){
                    return response()->json(['success' => true, 'message' => __('admin.disbursement_successful')]);
                }
            }
        }catch (\Exception $exception){
            if (empty($isCron)){
                return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);
            }else{
                $disbursementData = [
                    'user_activity' => 'Disbursement failed',
                    'translation_key' => 'disbursement_failed',
                    'type_id' => $loan->id,
                    'loan_number' => $loan->loan_number,
                    'status' => $loanStatus
                ];
                (new NotificationController)->addLoanNotification($disbursementData); // send notification
            }
        }
    }

    /**
     * blitznet commission for loan orders
     *
     * @param Order $order
     * @param PaymentProviderAccount $sourceUser
     * @param $commission
     * @return void
     */
    public function blitznetCommission(Order $order,PaymentProviderAccount $sourceUser,$commission){
        try {
            $btData = [
                'reference' => 'BC-'. $order->id,
                'amount' => $commission,
                'source_user_id' => $sourceUser->payment_provider_ac_id,
            ];
            $xendit = new XenditController;
            $data = $xendit->balanceTransfer($btData);
            if (!empty($data) && Arr::exists($data,'transfer_id')) {
                PaymentProviderTransaction::create([
                    'payment_provider_id' => PAYMENT_PROVIDERS['XENDIT'],
                    'users_type' => User::class,
                    'users_id' => $order->user_id,
                    'company_id' => $order->company_id,
                    'transaction_status' => PAYMENT_TRANSACTION_STATUS['COMPLETED'],
                    'transaction_type_id' => TRANSACTION_TYPES['COMMISSION_TRANSFER_TO_BLITZNET'],
                    'credit_ac_id' => getSettingValueByKey('xendit_main_account'),
                    'credit_ac_type' => getSettingValueByKey('app_name'),
                    'debit_ac_id' => $sourceUser->payment_provider_ac_id,
                    'debit_ac_type' => $sourceUser->name,
                    'transfer_id' => $data['transfer_id'],
                    'related_type' => Order::class,
                    'related_id' => $order->id,
                    'amount' => $commission,
                    'response_by_provider' => json_encode($data),
                    'created_type' => User::class,
                    'created_id' => \Auth::check() ? \Auth::user()->id : '1'
                ]);

                PaymentProviderTransaction::bootSystemActivities();
            }
        }catch (\Exception $exception){}
    }

    /**
     * Buyer Loan Late Fee Transaction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncLoanLateFeeCharges()
    {
        try {
            $appliedLoans = LoanApply::with('user', 'loanTransactions')->whereIn('status_id', [LOAN_STATUS['ONGOING'], LOAN_STATUS['PAID_OFF'], LOAN_STATUS['NPL']])->get();

            foreach ($appliedLoans as $loan) {

                $dueDate = Carbon::parse($loan->disbursement_date)->addDay($loan->tenure)->format('d-m-Y');

                $lateFeeCharge = $this->getLateFeeAmount($loan->loan_confirm_amount);

                if ($dueDate < Carbon::now()->format('d-m-Y')) {

                    $loan->status_id = LOAN_STATUS['NPL'];
                    $loan->save();

                    LoanTransaction::create([
                        'loan_id'               =>  $loan->id,
                        'order_id'              =>  $loan->order_id,
                        'applicant_id'          =>  $loan->applicant_id,
                        'application_id'        =>  $loan->application_id,
                        'company_id'            =>  $loan->company_id,
                        'user_type'             =>  User::class,
                        'user_id'               =>  $loan->user_id,
                        'transaction_amount'    =>  $lateFeeCharge,
                        'transaction_ac_type'   =>  LOAN_TRANSACTION['DEBIT'],
                        'transaction_status'    =>  LOAN_STATUS['COMPLETED'],
                        'transaction_type_id'   =>  TRANSACTION_TYPE['LATE_FEE']
                    ]);

                    dispatch(new KoinworksLoanLateFeeNotifyJob([$loan->user->email], $loan)); // Late fee applied notification

                }

            }

            return response()->json(['success' => true, 'message' => 'Buyer Loan Late Fee']);

        } catch (\Exception $exception) {
            Log::critical('Code - 400 | ErrorCode:B001 -  Buyer Loan Late Fee');

            return response()->json(['success' => false, 'message' => 'Code - 500 | ErrorCode:B001 -  Buyer Loan Late Fee']);
        }

        Log::critical('Code - 400 | ErrorCode:B002 -  Buyer Loan Late Fee');

        return response()->json(['success' => false, 'message' => 'Code - 500 | ErrorCode:B002 -  Buyer Loan Late Fee']);
    }

    /**
     * Loan late fee calculations
     *
     * @param $loanAmount
     * @return float|int
     */
    public function getLateFeeAmount($loanAmount)
    {
        $lateFeeCharge = LoanProviderCharges::where('loan_provider_id', LoanProvider::KOINWORKS)->where('charges_type_id',LOAN_PROVIDER_CHARGE_TYPE['LATE_FEE'])->first();

        $lateFeeAmount = (float)0.00;

        if ($lateFeeCharge->ammount_type == 0) {
            $lateFeeAmount = ((float)$loanAmount * (float)$lateFeeCharge->value) / 100;

        } else if ($lateFeeCharge->ammount_type == 1) {
            $lateFeeAmount = (float)$lateFeeCharge->value;

        }

        return $lateFeeAmount;
    }

}
