<?php

namespace App\Http\Controllers\Admin\Loan;

use App\Events\LoanEvent;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Credit\KoinWorks\KoinWorkController;
use App\Models\Company;
use App\Models\LoanApplication;
use App\Models\LoanApply;
use App\Models\LoanProvider;
use App\Models\LoanProviderApiResponse;
use App\Models\LoanTransaction;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Quote;
use App\Models\Role;
use App\Models\SystemActivity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Contracts\Encryption\DecryptException;

class LoanController extends Controller
{
    /** @ekta
     * index: LOAN list with datatable pagination, sorting and search
     */
    function index(Request $request)
    {

        if ($request->ajax()) {
            $draw               = $request->get('draw');
            $start              = $request->get("start");
            $length             = $request->get("length");
            $sort               = $request->get('order')[0]['dir'];
            $search             = $request->get('search')['value'];

            $columnIndex_arr    = $request->get('order');
            $columnName_arr     = $request->get('columns');
            $columnIndex        = $columnIndex_arr[0]['column'];
            $column             = $columnName_arr[$columnIndex]['data'];

            $query = LoanApply::with('orders:id,order_number')
                ->with('loanStatus:id,status_display_name')
                ->with('companies:id,name')
                ->with(['loanApplicants'=>function($q){
                    $q->select(['id','first_name','last_name'])->with('loanApplicantBusiness:id,applicant_id,email,phone_number,phone_code');
                }])
                ->with('loanApplications:id,senctioned_amount')
                ->where('order_id', '<>',null);

            //Filters
            if (!empty($request->filterData)) {
                //Order Number
                if (Arr::exists($request->filterData, 'order')) {
                    $query->whereHas('orders', function($query) use($request){
                        $query->whereIn('order_number', $request->filterData['order']);
                    });
                }

                //Loan Number
                if (Arr::exists($request->filterData, 'loan_number')) {
                    $query->whereIn('loan_number', $request->filterData['loan_number']);
                }

                //company name
                if (Arr::exists($request->filterData, 'company_name')) {
                    $query->whereHas('companies', function($query) use($request){
                        $query->whereIn('name', $request->filterData['company_name']);
                    });
                }

                //email
                if (Arr::exists($request->filterData, 'email')) {
                    $query->whereHas('loanApplicantBusiness', function($query) use($request){
                        $query->whereIn('email', $request->filterData['email']);
                    });
                }

                //phone
                if (Arr::exists($request->filterData, 'mobile')) {
                    $query->whereHas('loanApplicantBusiness', function($query) use($request){
                        $query->whereIn('phone_number', $request->filterData['mobile']);
                    });
                }

                //loan Status
                if (Arr::exists($request->filterData, 'loan_status')) {
                    $query->whereHas('loanStatus', function($query) use($request){
                        $query->whereIn('status_display_name', $request->filterData['loan_status']);
                    });
                }

            }

            // here will search query
            if ($search != "") {
                $query->where(function($q) use($search){
                    $q->orWhere('loan_number', 'LIKE', "%$search%")
                    ->orWhere('provider_loan_id', 'LIKE', "%$search%")
                    ->orWhere('loan_confirm_amount', 'LIKE', "%$search%")
                    ->orWhereHas('orders', function($query) use($search){
                        $query->where('order_number', 'LIKE',"%$search%");
                    })
                    ->orWhereHas('loanStatus', function($query) use($search){
                        $query->where('status_display_name', 'LIKE',"%$search%");
                    })
                    ->orWhereHas('companies', function($query) use($search){
                        $query->where('name', 'LIKE',"%$search%");
                    })
                    ->orWhereHas('loanApplications', function($query) use($search){
                        $query->where('senctioned_amount', 'LIKE',"%$search%");
                    })
                    ->orWhereHas('loanApplicants', function($query) use($search){
                        $query->where('email', 'LIKE',"%$search%")
                              ->orWhere('phone_number', 'LIKE', "%$search%");
                    });
                });
            }
            // here will search query

            // Sorting
            if (!empty($column)) {

                //loan applies tabel
                if($column == 'loan_confirm_amount' || $column == 'provider_loan_id') {
                    $query = $query->orderBy($column, $sort);
                }

                if($column == 'loan_number') {
                    $query = $query->orderBy('id', $sort);
                }

                // order table
                if ($column == 'order_number') {
                    $query = $query->orderBy('order_id', $sort);
                }

                //loan application table
                if ($column == 'loan_limit') {
                    $query = $query->orderBy(LoanApplication::select('senctioned_amount')
                        ->whereColumn('loan_applications.id', 'loan_applies.application_id'),$sort);
                }

            } else {
                $query->orderBy('id', $sort);
            }

            // Total Display records
            $totalRecords = $query->count();

            $totalDisplayRecords = $query->count();

            $loans = $query->skip($start)->take($length)->get();

            return response()->json([

                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalDisplayRecords,
                "aaData" => $loans->map(function ($loan) {
                    $orderNumber = '<a href="javascript:void(0);" style="text-decoration: none; color: #000" class="viewOrderModal hover_underline" data-bs-toggle="modal" data-bs-target="#viewOrderModal" data-id="'.$loan->order_id.'">'.$loan->orders->order_number.'</a>';
                    $loanNumber = '<a href="javascript:void(0);" style="text-decoration: none; color: #000" class="loanModalView hover_underline" data-bs-toggle="modal" data-bs-target="#limitModal" data-id="'.Crypt::encrypt($loan->id).'">'.$loan->loan_number.'</a>';
                    $viewBtn = '<a class="ps-2 cursor-pointer loanModalView" data-id="'.Crypt::encrypt($loan->id).'" data-bs-toggle="modal" data-bs-target="#loanModal" data-toggle="tooltip" ata-placement="top" title="'.__('admin.view').'"><i class="fa fa-eye"></i></a>';
                    $editBtn  =   '<a href="'. route('loan-edit', ['id' => Crypt::encrypt($loan->id)]) .'" class="show-icon ps-2" data-toggle="tooltip" ata-placement="top" title="'.__('admin.edit').'"><i class="fa fa-edit" aria-hidden="true"></i></a>';

                    $action = $viewBtn.$editBtn;

                    $statusClass = '';
                    if($loan->loanStatus->status_display_name == 'Cancel'){
                        $statusClass = "danger";
                    }else{
                        $statusClass = "success";
                    }

                    $status = '<span class="badge badge-pill badge-'.$statusClass.'">'.$loan->loanStatus->status_display_name.'</span>';
                    $availableAmount = $loan->loanApplications->senctioned_amount - $loan->loan_confirm_amount;
                    return [
                        'order_number'          =>  $loan->orders->order_number,
                        'loan_number'           =>  $loan->loan_number,
                        'provider_loan_id'      =>  $loan->provider_loan_id,
                        'company_name'          =>  $loan->companies->name,
                        'email'                 =>  $loan->loanApplicants->loanApplicantBusiness->email,
                        'mobile'                =>  '+'.$loan->loanApplicants->loanApplicantBusiness->phone_code.' '.$loan->loanApplicants->loanApplicantBusiness->phone_number,
                        'senctioned_amount'     =>  'Rp '.number_format($loan->loanApplications->senctioned_amount,2),
                        'loan_confirm_amount'   =>  'Rp '.number_format($loan->loan_confirm_amount,2),
                        'available_amount'      =>  'Rp '.number_format($availableAmount,2),
                        'status'                =>  $status,
                        'action'                =>  $action
                    ];

                })
            ]);
        }

        $query = LoanApply::with('orders:id,order_number')
            ->with('loanStatus:id,status_display_name')
            ->with('companies:id,name')
            ->with(['loanApplicants'=>function($q){
                $q->select(['id','first_name','last_name'])->with('loanApplicantBusiness:id,applicant_id,email,phone_number,phone_code');
            }])
            ->with('loanApplications:id,loan_limit')
            ->where('order_id', '<>',null);
        $loans     = $query->get();

        $mobiles  = $loans;
        $mobiles  = $mobiles->unique('loanApplicants.loanApplicantBusiness.phone_number');

        $companies  = $loans;
        $companies  = $companies->unique('companies.name');

        $status  = $loans;
        $status = $status->unique('status_id');

        /**begin: system log**/
        LoanApply::bootSystemView(new LoanApply(), 'LoanApply', SystemActivity::VIEW);
        /**end:  system log**/
        //return View::make('admin.loan.index');
        return View::make('admin.loan.index')->with(compact(['loans','mobiles','companies','status']));
    }

    /** @ekta
     * LOAN view model
     */
    public function view($id) {
        $id = Crypt::decrypt($id);
        $loanApply = LoanApply::getLoanDetails($id);
        $orderController = new OrderController();
        $amountDetails = $orderController->getQuoteChargeWithAmount($loanApply->quote_id);
        $quote = Quote::where('id', $loanApply->quote_id)->first(['id','tax','tax_value','final_amount']);
        $loanIntrestCal = getLoanCalculation($loanApply->quote_id);

        /**begin: system log**/
        $loanApply->bootSystemView(new LoanApply(), 'LoanApply', SystemActivity::RECORDVIEW, $loanApply->id);
        /**end: system log**/
        $loanView = view('admin/loan/loan_view',['loanApply'=>$loanApply, 'amountDetails' => $amountDetails, 'quote' => $quote, 'loanIntrestCal' => $loanIntrestCal])->render();
        return response()->json(array('success' => true, 'loanView' => $loanView));
    }

    /** @ekta
     * LOAN edit
     */
    function edit($id){
        $id = Crypt::decrypt($id);
        $loanApply = LoanApply::getLoanDetails($id);
        $loanTransaction = $loanApply->LoanTransactions()->get();
        $providerLoanId = Crypt::encrypt($loanApply->provider_loan_id);
        $orderId = Crypt::encrypt($loanApply->order_id);
        $loanApplication = LoanApplication::where('id', $loanApply->application_id)->get()->first();
        $providerUserId = Crypt::encrypt($loanApplication->provider_user_id);
        $orderController = new OrderController();
        $amountDetails = $orderController->getQuoteChargeWithAmount($loanApply->quote_id);
        $quote = Quote::where('id', $loanApply->quote_id)->first(['id','tax','tax_value','final_amount']);
        $loanIntrestCal = getLoanCalculation($loanApply->quote_id);

        $loanCancelBtnVisible = true;
        if(Auth::user()->role_id == 1){
          
            $loanOrderStatus = $loanApply->orders->order_status;
            if($loanOrderStatus == 4){ // status - order in progress
                $orderItemsCount = $loanApply->orders->orderItems->whereNotNull('order_item_status_id')->count();
                if($orderItemsCount > 0){
                    $loanCancelBtnVisible = false;
                }
            }elseif( in_array($loanOrderStatus, [3,5,6,7,8,9,10]) ){  // statuses - after order in progress
                $loanCancelBtnVisible = false;
            }
        }

        /**begin: system log**/
        $loanApply->bootSystemView(new LoanApply(), 'LoanApply', SystemActivity::EDITVIEW, $loanApply->id);
        /**end: system log**/

        return view('admin/loan/loan_edit',['loanApply' => $loanApply, 'amountDetails' => $amountDetails, 'quote' => $quote, 'loanIntrestCal' => $loanIntrestCal, 'loanTransaction' => $loanTransaction, 'providerLoanId' => $providerLoanId, 'providerUserId' => $providerUserId, 'orderId' => $orderId, 'loanCancelBtnVisible' => $loanCancelBtnVisible]);
    }

    /** @ekta
     * check loan repay amount api json responce
     */
    function checkRepayAmout($loanApplyId){

        $providerLoanId = LoanApply::where('id' ,$loanApplyId)->pluck('provider_loan_id')->first();
        $kwobj = new KoinWorkController;
        $returnData = $kwobj->repayment($providerLoanId);
        if ($returnData['status']==200 && isset($returnData['data'])){
            $returnHTML = view('admin/loan/repayment', ['returnData' => $returnData['data']])->render();
            return response()->json(array('success' => true, 'html' => $returnHTML));
        }else{
            return response()->json(array('success' => false, 'message' => __('admin.something_error_message')));

        }
    }

    /**
     * loanCancel: loan cancel api call and order convert to credit
     */
    public function loanCancel(Request $request)
    {
        try{
            $orderId = Crypt::decrypt($request->orderId);
            $order = new OrderController;
            $order->setOrderStatusChange(ORDER_STATUS['CREDIT_REJECTED'], $orderId); // Order credit to cash
            return response()->json(['status' => true, 'message' => __('admin.loan_cancel_success')]);
        }catch(\Exception $e) {
            return response()->json(['status' => false, 'message' => __('admin.something_error_message') ]);
        }
    }
    /**
     * laon confirm: loan confirm api call and order convert to credit
     */
    public function loanConfirmation(Order $order)
    {
        try{

            $loan = $order->loanApply()->first();
            $loanCal = getLoanCalculation($order->quote_id);
            LoanTransaction::insert([[
                'loan_id'               =>  $loan->id,
                'order_id'              =>  $loan->order_id,
                'applicant_id'          =>  $loan->applicant_id,
                'application_id'        =>  $loan->application_id,
                'company_id'            =>  $loan->company_id,
                'users_type'            =>  User::class,
                'users_id'              =>  $loan->user_id,
                'transaction_amount'    =>  $loanCal['interest_amount'],
                'transaction_ac_type'   =>  TRANSACTION_AC_TYPE['DEBIT'],
                'transaction_status'    =>  LOAN_STATUS['COMPLETED'],
                'charge_type_id'        =>  LOAN_PROVIDER_CHARGE_TYPE['INTEREST'],
                'transaction_type_id'   =>  TRANSACTION_TYPES['CHARGES'],
                'remarks'               => $loanCal['interest_rate'].'% Interest for '.$loanCal['period_in_days'].' Days',
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now()
            ],[
                'loan_id'               =>  $loan->id,
                'order_id'              =>  $loan->order_id,
                'applicant_id'          =>  $loan->applicant_id,
                'application_id'        =>  $loan->application_id,
                'company_id'            =>  $loan->company_id,
                'users_type'            =>  User::class,
                'users_id'              =>  $loan->user_id,
                'transaction_amount'    =>  $loanCal['repayment_charges'],
                'transaction_ac_type'   =>  TRANSACTION_AC_TYPE['DEBIT'],
                'transaction_status'    =>  LOAN_STATUS['COMPLETED'],
                'charge_type_id'        =>  LOAN_PROVIDER_CHARGE_TYPE['REPAYMENT_CHARGE'],
                'transaction_type_id'   =>  TRANSACTION_TYPES['CHARGES'],
                'remarks'               => 'Repayment Charges',
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now()
            ]]);
            $koinWorkController = new KoinWorkController;
            $payload = ['amount'=>(int)$loan->loan_confirm_amount];
            $returnData = $koinWorkController->loanConfirmation($loan->provider_user_id,$loan->provider_loan_id,$payload);
            $loan->status_id = LOAN_STATUS['LOAN_CONFIRMED'];
            $loan->save();
            LoanProviderApiResponse::createOrUpdateLoanProvideApiResponse(['loan_provider_id'=>LoanProvider::KOINWORKS,'applicant_id'=>$loan->applicant_id,'user_id'=>$loan->user_id,'request_data'=>json_encode($payload),'response_code'=>$returnData['status'],'response_data'=>json_encode($returnData)]);

            /**begin: loan confirmed notification**/
            $loanStatus = $loan->loanStatus->status_display_name;
            $transferData = [
                'user_activity' => 'Loan Confirmed',
                'translation_key' => 'loan_confirm',
                'type_id' => $loan->id,
                'loan_number' => $loan->loan_number,
                'status' => $loanStatus
            ];
            (new NotificationController)->addLoanNotification($transferData);
            /**end: loan confirmed notification**/

        }catch(\Exception $e) {}
    }
}
