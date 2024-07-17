<?php

namespace App\Http\Controllers\API\Xendit;

use App\Http\Controllers\Admin\NotificationController;
use App\Jobs\buyer\Credit\KoinWorks\DisbursementBlitzToKoinworksJob;
use App\Jobs\buyer\Credit\KoinWorks\KoinworksLoanStatusJob;
use App\Models\LoanApply;
use App\Models\LoanTransaction;
use App\Models\PaymentProvider;
use App\Models\PaymentProviderAccount;
use App\Models\PaymentProviderTransaction;
use App\Models\User;
use App\Models\XenditPaymentInvoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class XenditPaymentInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($params)
    {
        $response = XenditPaymentInvoice::create($params);

        $response->bootSystemActivities();

        return $response->wasRecentlyCreated;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get Xendit invoice payment callback
     *
     * @param $status
     * @param $model
     * @param $model_id
     */
    public function getPaymentInvoiceStatus($status,$model,$model_id)
    {
        try {

            if ($status=='success') {
                return (new XenditTemplateController())->renderPaymentSuccess($model,$model_id);

            } else {
                return (new XenditTemplateController())->renderPaymentFail($model,$model_id);
            }

        } catch (\Exception $exception) {

            Log::critical('Code - 404 | ErrorCode:B007 Xendit Buyer Re-Payment');

            abort('404');
        }

        abort('404');
    }

    /**
     * Xendit Payment Invoice Callback
     *
     * @param $data
     */
    public function setPaymentInvoiceCallback($data)
    {
        try {

            $xenditInvoices = XenditPaymentInvoice::where('xendit_id', $data['id'])->first();

            $loanApply = LoanApply::with('user')->where('loan_number', $data['external_id'])->first();

            $repaymentAccount = PaymentProviderAccount::koinworksXenDebit();

            XenditPaymentInvoice::where('related_to_id', $loanApply->id)->where('related_to_type', LoanApply::class)->update([
                'xendit_id'         =>  $data['id'],
                'status'            =>  ($data['status']=='PAID' || $data['status']=='SETTLED') ? 1 : 0,
                'response'          =>  json_encode($data)
            ]);
            $activity = '';
            $notiTransKey = '';
            if ($data['status'] == 'PAID' || $data['status'] == 'SETTLED') {

                DB::transaction(function () use (&$data, &$loanApply, &$repaymentAccount) {

                    LoanApply::where('id', $loanApply->id)->update([
                        'paid_amount'   =>  $data['amount'],
                        'status_id'     =>  LOAN_STATUS['BUYER_REPAID']
                    ]);

                    PaymentProviderTransaction::create([
                        'payment_provider_id'       =>  PAYMENT_PROVIDERS['XENDIT'],
                        'users_type'                =>  User::class,
                        'users_id'                  =>  $loanApply->user_id,
                        'company_id'                =>  $loanApply->company_id,
                        'transaction_type_id'       =>  TRANSACTION_TYPES['BUYER_REPAYMENT'],
                        'credit_ac_id'              =>  $repaymentAccount->payment_provider_ac_id,
                        'credit_ac_type'            =>  PaymentProviderAccount::KOINWORKDB,
                        'debit_ac_id'               =>  '',
                        'debit_ac_type'             =>  '',
                        'transfer_id'               =>  $data['id'],
                        'related_type'              =>  LoanApply::class,
                        'related_id'                =>  $loanApply->id,
                        'amount'                    =>  $data['amount'],
                        'response_by_provider'      =>  json_encode($data),
                        'created_type'              =>  User::class,
                        'created_id'                =>  \Auth::check() ? \Auth::user()->id : '1'
                    ]);

                    LoanTransaction::create([
                        'loan_id'                   =>  $loanApply->id,
                        'order_id'                  =>  $loanApply->order_id,
                        'applicant_id'              =>  $loanApply->applicant_id,
                        'application_id'            =>  $loanApply->application_id,
                        'users_type'                =>  User::class,
                        'users_id'                  =>  $loanApply->user_id,
                        'company_id'                =>  $loanApply->company_id,
                        'transaction_reference_id'  =>  $data['id'],
                        'transaction_amount'        =>  $data['amount'],
                        'transaction_status'        =>  LOAN_STATUS['COMPLETED'],
                        'transaction_ac_type'       =>  LOAN_TRANSACTION['CREDIT'],
                        'transaction_type_id'       =>  TRANSACTION_TYPES['BUYER_REPAYMENT']
                    ]);

                });

                $loanApplyUpdated = LoanApply::findOrFail($loanApply->id);

                if ($loanApplyUpdated->status_id == LOAN_STATUS['BUYER_REPAID']) {
                    dispatch(new DisbursementBlitzToKoinworksJob($loanApplyUpdated));
                }
                // repayment notification
                $activity = "Loan Repayment";
                $notiTransKey = "loan_repay_notification";
            } else {
                $activity = "Loan Repayment Failed";
                $notiTransKey = "loan_repay_failed_notification";

                /**begin: Update Xendit Payment Links**/
                $linkStatus = ($data['status']=='EXPIRED') ? 3 : ($data['status']=='FAIL') ? 2 : ($data['status']=='PAID') ? 1 : 0;
                if (!empty($xenditInvoices)) {
                    XenditPaymentInvoice::where('xendit_id',$xenditInvoices->xendit_id)->update([
                        'status' => $linkStatus,
                    ]);
                }
                /**end: Update Xendit Payment Links**/
            }

            /** start notification: repayment **/
            $loanStatus = $loanApply->loanStatus->status_display_name;
            $updatedBy = \Auth::check() ? \Auth::user()->full_name : "Blitznet Team";
            buyerNotificationInsertWithoutAuth($loanApply->user_id, $activity, $notiTransKey, 'loan', $loanApply->id, ['loan_number' => $loanApply->loan_number, 'status' => $loanStatus, 'updated_by' => $updatedBy, 'icons' => 'fa-gear']);
            $repayData = [
                'user_activity' => $activity,
                'translation_key' => $notiTransKey,
                'type_id' => $loanApply->id,
                'loan_number' => $loanApply->loan_number,
                'status' => $loanStatus
            ];
            (new NotificationController)->addLoanNotification($repayData);
            /** end notification: repayment **/

            $loanApply->status = ($data['status'] == 'PAID' || $data['status'] == 'SETTLED') ? 1 : 0;

            dispatch(new KoinworksLoanStatusJob([$loanApply->user->email], $loanApply)); // Notify user for loan status

            Log::info('Code - 200 | Success:B002 Xendit Invoice Callback - Loan');

            return response()->json(['success' => true, 'message' => 'Get Invoice Callback']);

        } catch (\Exception $exception) {
            Log::critical('Code - 500 | ErrorCode:B022 Xendit Invoice Callback - Loan');
            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);

        }
        Log::critical('Code - 400 | ErrorCode:B021 Xendit Invoice Callback - Loan');

        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')]);


    }
}
