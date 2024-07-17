<?php

namespace App\Http\Controllers\Buyer\Credit;

use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Credit\KoinWorks\KoinWorkController;
use App\Jobs\buyer\Credit\KoinWorks\KoinworksLoanApplyJob;
use App\Models\LoanApply;
use App\Models\Order;
use App\Models\LoanProviderApiResponse;
use App\Models\LoanProvider;
use App\Models\LoanApplication;
use App\Models\SystemActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Log;
use App\Http\Controllers\Admin\OrderController;
use App\Models\Quote;
use Carbon\Carbon;

class LoanController extends Controller
{
    //place order using Loan provider credit
    public function placeOrder(Request $request){
        $inputs = (object)$request->except('_token');

        $dashController = new DashboardController();
        $result = null;
        DB::transaction(function () use (&$result, $dashController, $inputs) {
            //order related entries
            $result = $dashController->setOrder($inputs);
            //loan related entries
            $loanCal = getLoanCalculation($result->quote_id);
            $loan = LoanApply::createOrUpdateLoanApply([
                            'quote_id'=>$result->quote_id,
                            'order_id'=>$result->id,
                            'loan_confirm_amount'=>$result->payment_amount,
                            'loan_repay_amount'=>$loanCal['payable_amount'],
                            'interest'=>$loanCal['interest_amount'],
                            'additional_amount'=>$loanCal['repayment_charges'],
                            'status_id'=>LOAN_STATUS['ASSESSMENT_APPROVED']
                        ]);
            $application = $loan->loanApplications()->first();
            $application->decrement('remaining_amount', $loan->loan_amount);
            /**begin: system log**/
            $loan->bootSystemActivities();
            /**end: system log**/
        });
        if (empty($result)){
            return response()->json(array('success' => false,'message'=>__('admin.something_went_wrong')));
        }

        $ordersCount = Order::where('is_deleted', 0);
        /*********begin: set permissions based on custom role.**************/
        $isOwner = User::checkCompanyOwner();
        if (Auth::user()->hasPermissionTo('list-all buyer orders') || $isOwner == true) {
            $ordersCount->where('company_id', Auth::user()->default_company)->count();
        }else {
            $ordersCount->where('user_id', Auth::user()->id)->where('company_id', Auth::user()->default_company)->count();
        }
        /*********end: set permissions based on custom role.**************/
        $request = request();
        $html = $dashController->getDashboardOrders($request)->getData()->html;
        return response()->json(array('success' => true, 'ordersCount' => $ordersCount, 'html' => $html, 'lastOrderId' => $result->id));
    }
     /*Create loan starting .
     *
     * @param  \App\Models\LoanApplication  $loanApplications
     * @return \Illuminate\Http\JsonResponse
     */
    public function transactionView($id)
    {
        $id = Crypt::decrypt($id);
        $loanApply = LoanApply::getLoanDetails($id);
        $orderController = new OrderController();
        $amountDetails = $orderController->getQuoteChargeWithAmount($loanApply->quote_id);
        $quote = Quote::where('id', $loanApply->quote_id)->first(['id','tax','tax_value','final_amount']);
        $loanIntrestCal = getLoanCalculation($loanApply->quote_id);
        $paid_date='';
        $repayTransaction = $loanApply->loanTransactions->where('transaction_type_id',TRANSACTION_TYPES['BUYER_REPAYMENT'])->first();
        if( $repayTransaction!=''){
            $paid_date= Carbon::parse($repayTransaction->created_at)->format('d-m-Y');
        }
        /**begin: system log**/
        $loanApply->bootSystemView(new LoanApply(), 'LoanApply', SystemActivity::RECORDVIEW, $loanApply->id);
        /**end: system log**/
        $loanView = view('buyer/credit/loan_view',['loanApply'=>$loanApply, 'amountDetails' => $amountDetails, 'quote' => $quote, 'loanIntrestCal' => $loanIntrestCal,"paid_date"=>$paid_date])->render();
        return response()->json(array('success' => true, 'loanView' => $loanView));

    }

    function getCreateLoanCalculation($quote_id){

        $quotes_charges_with_amounts = DB::table('quotes')
        ->leftjoin('quotes_charges_with_amounts', 'quotes.id', '=', 'quotes_charges_with_amounts.quote_id')
        ->where('quotes.id', $quote_id)
        ->orderBy('quotes_charges_with_amounts.created_at', 'desc')
        ->get();

       $key=[];
       $val=[];
       $final_amount='';
       $final_amount_prefix='';
       $supplier_final_amount_prefix='';
       $supplier_final_amount='';
       $tax_value='';
       foreach ($quotes_charges_with_amounts as $charges){

        $final_amount_prefix=__("rfqs.total_amount");//'Total Amount';
        $supplier_final_amount_prefix=__("rfqs.product");//'Product';
        if(!in_array($supplier_final_amount_prefix,$key)){
        array_push($val, 'Rp'.' '.$charges->final_amount);
        array_push($key,$supplier_final_amount_prefix);
        }

               if ($charges->type == 0)
                    $charge_name= $charges->charge_name . ' ' . $charges->charge_value .'%';
                else
                    $charge_name =$charges->charge_name;

                  $charge_sign= $charges->addition_substraction == 0 ? '- ' : '+ ' ;
                  $charge_amount=  $charge_sign.''.'Rp'.' '.number_format($charges->charge_amount, 2) ;

                array_push($key,$charge_name);
                array_push($val,$charge_amount);
                $tax_value= $charge_sign.''.'Rp'.' '.number_format($charges->tax_value, 2);


       }
       $interst_rate_prefix=__("rfqs.30_days_intrest");//'2% Interest for 30 Days';
       $repayment_charge_prefix=__("rfqs.repayment_charges");//'Repayment Charges';
       $loancharges=getLoanCalculation($quote_id);
       $intrest=$loancharges['interest_amount'];
       $repayment_charge=$loancharges['repayment_charges'];
       if($tax_value!='')
       {
        array_push($val,$tax_value);
        array_push($key,__("rfqs.tax").' '.$charges->tax.'%');
       }
       if(!in_array($final_amount_prefix,$key)){
            array_push($val,'Rp'.' '.number_format($charges->final_amount,2));
            array_push($key,$final_amount_prefix);
        }

            array_push($val,'+'.' '.'Rp'.' '.number_format($intrest,2));
            array_push($key, $interst_rate_prefix);
            array_push($val, '+'.' '.'Rp'.' '.number_format($repayment_charge,2));
            array_push($key,$repayment_charge_prefix);
            $payableAmount=$charges->final_amount+$repayment_charge+$intrest;
            array_push($val, 'Rp'.' '.number_format($payableAmount,2));
            array_push($key,__("rfqs.paybal_amount"));
        $final_arr=array_combine($key,$val);
        return   json_encode($final_arr);
    }

   /**
    * verifyLimitOTP create by Monika
    *
    * @param  mixed $request
    * @return void
    */
   public function verifyOtpWithPlaceOrder(Request $request)
   {
        $application = LoanApplication::where(['user_id'=>Auth::user()->id,'status_name'=>'Approved'])->first();

        if (empty($application)){
             return response()->json(array('success' => false, 'message' => __('profile.loan_application_not_found')));
         }
         $code = implode('', $request->code);
         if (strlen($code)<6){
             return response()->json(array('success' => false, 'message' => __('admin.enter_valid_otp')));
         }
         $kwobj = new KoinWorkController;
         $providerLoanId = LoanApply::where(['user_id'=>Auth::user()->id,'quote_id'=>$request->quoteId])->pluck('provider_loan_id')->first();

         $returnData = $kwobj->verifyOTP($application->provider_user_id,$code,$providerLoanId);
         LoanProviderApiResponse::createOrUpdateLoanProvideApiResponse(['loan_provider_id'=>LoanProvider::KOINWORKS,'applicant_id'=>$application->applicant_id,'user_id'=>$application->user_id,'response_code'=>$returnData['status'],'response_data'=>json_encode($returnData)]);

         if ($returnData['status']==200){
            $inputs = (object)$request->except('_token');
            $dashController = new DashboardController;
            return $dashController->placeOrder($request);

         }elseif ($returnData['status']==400){
             return response()->json(array('success' => false, 'message' => $returnData['message']['en']));
         }
         elseif ($returnData['status']==406){
              return response()->json(array('success' => false, 'message' => $returnData['message']['en']));
          }
          elseif ($returnData['status']==403){
            return response()->json(array('success' => false, 'message' => $returnData['message']['en']));
        }
         else{
             return response()->json(array('success' => false, 'message' => $returnData['message']['en']));
         }

   }
    /**
    * Create Loan Api call, create by Monika
    *
    * @param  mixed $request
    * @return void
    */
   public function createLoan(Request $request)
   {
    $providerLoanIdExist = LoanApply::where(['user_id'=>Auth::user()->id,'quote_id'=>$request->quoteId])->pluck('provider_loan_id')->first();

    if($providerLoanIdExist==''){
       try {
           $application = LoanApplication::where(['user_id'=>Auth::user()->id,'status_name'=>'Approved'])->first();
           if (empty($application)){
               return response()->json(array('success' => false, 'message' => __('profile.loan_application_not_found')));
           }
           $amount= str_replace( ',', '',$request->amount);
           $purpose="Loan required";
           $tenure=30;
           $tenure_unit="day";

          if($application->senctioned_amount < $amount){
               return response()->json(array('success' => false, 'message' => __('profile.loan_amount_need_minimum')));
           }

           $arrCreateLOan=[
               'amount'=> (int)$amount,
               'purpose'=>  $purpose,
               'tenure'=> (int)$tenure,
               'tenure_unit'=>$tenure_unit,
           ];


            $kwobj = new KoinWorkController;
            $returnData = $kwobj->createLoan($application->provider_user_id,$arrCreateLOan);
            LoanProviderApiResponse::createOrUpdateLoanProvideApiResponse(['loan_provider_id'=>LoanProvider::KOINWORKS,'applicant_id'=>$application->applicant_id,'user_id'=>$application->user_id,'response_code'=>$returnData['status'],'request_data'=>json_encode($arrCreateLOan),'response_data'=>json_encode($returnData)]);

           if ($returnData['status']==200){
             $loanApplies = LoanApply::Create([
                'loan_provider_id'                  => $application->loan_provider_id,
                'user_id'                           =>  $application->user_id,
                'provider_user_id'                  =>  $application->provider_user_id,
                'applicant_id'                      =>  $application->applicant_id,
                'application_id'                    =>  $application->id,
                'company_id'                        => $application->company_id,
                'quote_id'                          =>  $request->quoteId,
                'provider_loan_id'                  =>  $returnData['data']['loanID'],
                'loan_amount'                       =>  str_replace( ',', '',$request->amount),
                'status_id'                         =>  12,
                'tenure_days'                       =>  30,

            ]);
            $updateLoanApply = LoanApply::where('id', $loanApplies->id)->update([
                'loan_number' => 'BLOAN-' . $loanApplies->id,
            ]);

            if($application->reserved_amount=='' ||  $application->reserved_amount==0){
            $application->reserved_amount		 = str_replace( ',', '',$request->amount);
            $application->reserved_amount        = str_replace( '-', '',$application->reserved_amount);
            $updateLoanApplication = LoanApplication::where('id', $application->id)->update([
                'reserved_amount' => $application->reserved_amount,
            ]);
            }else{
            $reserved_amount		 = str_replace( ',', '',$request->amount);
            $reserved_amount        = str_replace( '-', '',$reserved_amount);
            $application->increment('reserved_amount', $reserved_amount);
            }

            /**create loan notification */
            $loanDetails = LoanApply::find($loanApplies->id);
            $notiData = [
                'user_activity' => 'Create Loan',
                'translation_key' => 'create_loan',
                'type_id' => $loanDetails->id,
                'loan_number' => $loanDetails->loan_number,
                'status' => $loanDetails->loanStatus->status_display_name
            ];
            (new NotificationController)->addLoanNotification($notiData);
            /**create loan notification */
            return response()->json(array('success' => true, 'message' => __('profile.otp_sent_successfully')));
           }elseif ($returnData['status']==400){
               return response()->json(array('success' => false, 'message' => $returnData['message']['en']));
           }else {
               return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));
           }
       } catch (\Exception $e) {
         return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));
       }
    }else{
        return $this->requestLoantOTP($providerLoanIdExist);
    }
   }
    /**
     * Request Loan APi call ,wriiten by Monika
     * Display the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestLoantOTP($providerLoanId)
    {
       try {
            $application = LoanApplication::where(['company_id'=>Auth::user()->default_company,'status_name'=>'Approved'])->first();
            if (empty($application)){
                return response()->json(array('success' => false, 'message' => __('profile.loan_application_not_found')));
            }
            $kwobj = new KoinWorkController;

            $returnData = $kwobj->requestLoanOTP($application->provider_user_id,$providerLoanId);
            LoanProviderApiResponse::createOrUpdateLoanProvideApiResponse(['loan_provider_id'=>LoanProvider::KOINWORKS,'applicant_id'=>$application->applicant_id,'user_id'=>$application->user_id,'response_code'=>$returnData['status'],'response_data'=>json_encode($returnData)]);
           //dd($returnData);

            if ($returnData['status']==200){
               return response()->json(array('success' => true, 'message' => __('profile.otp_sent_successfully')));
               Log::critical('Code - 200 | Something went wrong!!'. json_encode($returnData));
            }
            else {
                return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));
            }
         } catch (\Exception $e) {
            return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));
        }
    }

     /**
     * Request Loan APi call ,wriiten by Monika
     * Display the specified resource.
     *Loan Repayment check
     * @return \Illuminate\Http\JsonResponse
     */
    public function loanRepaymentCheck()
    {

     //  try{
            $providerLoanIdExist = LoanApply::select('provider_loan_id','application_id')->get();


            foreach ($providerLoanIdExist as $row) {
                Log::info('Code - 200 | provider loan id!!'. json_encode($row));
                if($row['provider_loan_id']){
                    Log::info('Code - 200 | provider loan id!!'. json_encode($row['provider_loan_id']));

                    $kwobj = new KoinWorkController;
                    $returnData =$kwobj->repayment($row['provider_loan_id']);
                    if ($returnData['status']==200 && isset($returnData['data']) && $returnData['data']['loanDetails']['loanStatusValue']=='Paid Off'){

                        Log::info('Code - 200 | User id!!'. json_encode($returnData['data']['loanDetails']['userID']));

                        $limitResponse = $kwobj->userLimit($returnData['data']['loanDetails']['userID']);
                        if(isset($limitResponse['status']) && isset($limitResponse['data']['status']) && $limitResponse['status'] == 200){
                        $remaingAMount = LoanApplication::select('remaining_amount')->where('id', $row['application_id'])->get();
                        $updateLoanApplication = LoanApplication::where('id', $row['application_id'])->update([
                            'remaining_amount'=>  $remaingAMount+$row['loan_confirm_amount'],
                            'reserved_amount' => $limitResponse['data']['reserveAmount'],
                        ]);
                        Log::info('Code 200 | Success:B101 remainingAmount Update'.$row['application_id']);
                        }else{
                            Log::critical('Code 400 | Success:B102 Error Occure Due to data'.json_encode($limitResponse));
                           // return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));

                        }

                    } else{
                            Log::critical('Code 400 | Success:B102 Error Occure Due to data'.json_encode($returnData));
                           // return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));

                    }

                }
            }
      /*  } catch (\Exception $e){
            Log::critical('Code 400 | Success:B102 Error Occure Due to data');
            return response()->json(['success' => false, 'message' => 'Something went wrong'],500);
        }*/
    }


}
