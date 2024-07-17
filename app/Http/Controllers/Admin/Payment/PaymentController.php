<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Buyer\Xendit\LoanTransaction\XenditLoanTransactionController;
use App\Http\Controllers\Controller;
use App\Models\LoanApply;
use App\Models\LoanStatus;
use App\Models\SystemActivity;
use App\Models\UserSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class PaymentController extends Controller
{
    /**
     * paymentDueList: payment due list, filter, sorting and dynamic search
     */
    public function paymentDueList(Request $request)
    {
        $loanStatusIds = [LOAN_STATUS['LOAN_CONFIRMED'],LOAN_STATUS['NPL'],LOAN_STATUS['ONGOING']];

        if ($request->ajax() && $request->get('draw')) {

            $draw               = $request->get('draw');
            $start              = $request->get("start");
            $length             = $request->get("length");
            $sort               = !empty($request->get('order')) ? $request->get('order')[0]['dir'] : 'asc';
            $search             = !empty($request->get('search')) ? $request->get('search')['value'] : '';

            $columnIndex_arr    = $request->get('order');
            $columnName_arr     = $request->get('columns');
            $columnIndex        = !empty($columnIndex_arr) ? $columnIndex_arr[0]['column'] : '';
            $column             = !empty($columnIndex_arr) ? $columnName_arr[$columnIndex]['data'] : '';

            $query = LoanApply::with('orders:id,order_number','user:id,firstname,lastname,email','loanApplicantBusiness:id,applicant_id,email')
                ->whereIn('status_id', $loanStatusIds);

            // Server side search
            if ($search != "") {
                $query->where(function($q) use($search){
                    $q->where('loan_number' , 'LIKE', "%$search%")
                    ->orWhere('provider_loan_id' , 'LIKE', "%$search%")
                    ->orWhere('provider_user_id' , 'LIKE', "%$search%")
                    ->orWhere('loan_amount' , 'LIKE', "%$search%")
                    ->orWhere('loan_confirm_amount' , 'LIKE', "%$search%")
                    ->orWhere('loan_repay_amount' , 'LIKE', "%$search%")
                    ->orWhereHas('orders', function($query) use($search){
                        $query->where('order_number', 'LIKE',"%$search%");
                    })
                    ->orWhereHas('loanApplicantBusiness', function($query) use($search){
                        $query->where('email', 'LIKE',"%$search%");
                    })
                    ->orWhereHas('user', function($query) use($search){
                        $query->where(DB::raw('CONCAT(firstname, " ", lastname)'), 'LIKE',"%".$search."%");
                    });
                });
            }

            //Filters
            if (!empty($request->filterData)) {

                //Loan Id
                if (Arr::exists($request->filterData, 'loan_ids')) {
                    $query->whereIn('loan_number', $request->filterData['loan_ids']);
                }

                //Order Number
                if (Arr::exists($request->filterData, 'order')) {
                    $query->whereIn('order_id', $request->filterData['order']);
                }

                //Company Number
                if (Arr::exists($request->filterData, 'company')) {
                    $query->whereHas('companies', function($query) use($request){
                        $query->whereIn('name',$request->filterData['company']);
                    });
                }

                //Due Date
                if (Arr::exists($request->filterData, 'start_date') && Arr::exists($request->filterData, 'end_date')) {
                    $start_date = Carbon::createFromFormat('d-m-Y', $request->filterData['start_date'])->format('Y-m-d 00:00:00');
                    $end_date = Carbon::createFromFormat('d-m-Y', $request->filterData['end_date'])->format('Y-m-d 23:59:59');
                    $query->whereBetween('due_date', [$start_date, $end_date]);
                }
            }

            // Sorting
            if (!empty($column)) {

                if($column == 'loan_repay_amount' || $column == 'loan_amount' || $column == 'loan_confirm_amount'){
                    $query->orderByRaw('CONVERT('.$column.', SIGNED) '.$sort);
                }

                if ($column == 'provider_loan_id' || $column == 'provider_user_id' || $column == 'due_date') {
                    $query->orderBy($column, $sort);
                }

                if ($column == 'order_number') {
                    $query->orderBy('order_id', $sort);
                }

                if ($column == 'loan_number') {
                    $query->orderBy('id', $sort);
                }

            } else {
                $query->orderBy('id', $sort);
            }

            $totalRecords = $query->count();

            // Total Display records
            $totalDisplayRecords = $query->count();

            $payments = $query->skip($start)->take($length)->get();

            return response()->json([

                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalDisplayRecords,
                "aaData" => $payments->map(function ($payment) use($request){

                    $loanNumber = '<a href="javascript:void(0);" style="text-decoration: none; color: #000" class="loanModalView hover_underline" data-bs-toggle="modal" data-bs-target="#limitModal" data-id="'.Crypt::encrypt($payment->id).'">'.$payment->loan_number.'</a>';
                    $paymentLink = '';
                    if(!empty($payment->paymentLink->where('status',0)->first())) {
                        $paymentLink = '<a name="repayment" id="repayment" data-id="'.Crypt::encrypt($payment->id).'" href="'.$payment->paymentLink->where('status',0)->first()->payment_link.'" role="button" data-toggle="tooltip" title="'.__('admin.payback').'" target="_blank">
                                            <img src="'.URL::asset("front-assets/images/icons/credit-card.png").'" data-toggle="tooltip" alt="'.__('admin.payback').'" title="'.__('admin.payback').'" srcset>
                                        </a>';

                    } else {
                        $paymentLink = '<a name="generatePayment" id="generatePayment" class="generate-payment-link" data-id="'.Crypt::encrypt($payment->id).'" href="javascript:void(0)" role="button" data-toggle="tooltip" title="'.__('admin.generate_payment_link').'">
                                            <img class="generate-payment-link-img" data-toggle="tooltip" src="'.URL::asset("assets/icons/generate_pay_link.png").'" alt="'.__('admin.generate_payment_link').'" title="'.__('admin.generate_payment_link').'" srcset>
                                            <div class="link-loading d-none" role="status">
                                                <img src="'.URL::asset("front-assets/images/icons/timer.gif").'" alt="'.__('admin.loading').'..." data-toggle="tooltip" title="'.__('admin.loading').'..." srcset>
                                            </div>
                                        </a>
                                        <a name="repayment" id="repayment" data-id="'.Crypt::encrypt($payment->id).'" class="payment-link d-none" href="#" role="button" data-toggle="tooltip" title="'.__('admin.payback').'" target="_blank">
                                            <img src="'.URL::asset("front-assets/images/icons/credit-card.png").'" alt="'.__('admin.payback').'" srcset>
                                        </a>';
                    }

                    if ($payment->status_id == LOAN_STATUS['LOAN_CONFIRMED']) { // When Loan requested link should not display
                        $paymentLink = '';
                    }

                    $action = $paymentLink;

                    $dueDate = '-';
                    if($payment->due_date){
                        $dueDateCarbon = Carbon::createFromFormat('Y-m-d H:i:s', $payment->due_date);
                        $dueDate = Carbon::createFromFormat('Y-m-d H:i:s', $payment->due_date)->format('Y-m-d');
                        if($dueDateCarbon < Carbon::now() && $dueDateCarbon->diffInDays(Carbon::now()) != 0){
                            $dayDiff = $dueDateCarbon->diffInDays(Carbon::now());
                            $dueDate = $dayDiff > 1 ? $dayDiff.' '.__('admin.days_ago') : $dayDiff.' '.__('admin.day_ago') ;
                        }
                    }

                    $buyerName = '-';
                    if(isset($payment->user->firstname) && isset($payment->user->lastname)){
                        $buyerName = $payment->user->firstname.' '.$payment->user->lastname;
                    }

                    return [
                        'loan_number' => $loanNumber,// loan_id
                        'provider_loan_id' => $payment->provider_loan_id,// koinworks_loan_id
                        'provider_user_id' => $payment->provider_user_id,// user_id
                        'order_number' => $payment->orders->order_number, //order_number
                        'buyer_name' =>  $buyerName, // buyer_name
                        'email' => $payment->loanApplicantBusiness->email ?? '-', // email
                        'loan_amount' => "Rp ".number_format($payment->loan_amount, 2), // Amount Requested
                        'loan_confirm_amount' => "Rp ".number_format($payment->loan_confirm_amount, 2), // Disburse amount
                        'loan_repay_amount' => "Rp ".number_format($payment->loan_repay_amount, 2), // Repayment Amount
                        'due_date' => $dueDate,
                        'actions' => $action
                    ];
                })
            ]);
        }

        $query = LoanApply::with('orders:id,order_number','user:id,firstname,lastname,email','companies:id,name')
            ->select(['loan_number','provider_loan_id','provider_user_id','order_id','user_id','loan_provider_id','loan_amount','loan_confirm_amount','due_date','company_id'])
            ->whereIn('status_id', $loanStatusIds);

        $loanNumbers = $query->get()->unique('loan_number');
        $orders = $query->get()->unique('orders.order_number');
        $companies  = $query->get()->unique('companies.name');

        /**begin: system log**/
        LoanApply::bootSystemView(new LoanApply(), 'Payment Due', SystemActivity::VIEW);
        /**end: system log**/

        return View::make('admin.payment.paymentDue')->with(compact(['orders','loanNumbers','companies']));
    }

    /**
     * disbursmentList: payment due list, sorting and dynamic search
     */
    public function disburseList(Request $request)
    {

        $loanStatusIds = [ LOAN_STATUS['ONGOING'], LOAN_STATUS['PAID_OFF'], LOAN_STATUS['NPL'], LOAN_STATUS['REPAY'] ];

        if ($request->ajax() && $request->get('draw')) {

            $draw               = $request->get('draw');
            $start              = $request->get("start");
            $length             = $request->get("length");
            $sort               = !empty($request->get('order')) ? $request->get('order')[0]['dir'] : 'asc';
            $search             = !empty($request->get('search')) ? $request->get('search')['value'] : '';

            $columnIndex_arr    = $request->get('order');
            $columnName_arr     = $request->get('columns');
            $columnIndex        = !empty($columnIndex_arr) ? $columnIndex_arr[0]['column'] : '';
            $column             = !empty($columnIndex_arr) ? $columnName_arr[$columnIndex]['data'] : '';

            $query = LoanApply::with('orders:id,order_number','user:id,firstname,lastname,email','loanApplicantBusiness:id,applicant_id,email')
                ->select(['id','loan_number','provider_loan_id','provider_user_id','order_id','user_id','loan_provider_id','loan_amount','loan_confirm_amount','loan_repay_amount','due_date','status_id','disbursed_to_supplier','disbursed_to_koinworks','applicant_id'])
                ->whereIn('status_id',$loanStatusIds);

            // Server side search
            if ($search != "") {
                $query->where(function($q) use($search){
                    $q = $q->where('loan_number' , 'LIKE', "%$search%")
                        ->orWhere('provider_loan_id' , 'LIKE', "%$search%")
                        ->orWhere('provider_user_id' , 'LIKE', "%$search%")
                        ->orWhere('loan_amount' , 'LIKE', "%$search%")
                        ->orWhere('loan_confirm_amount' , 'LIKE', "%$search%")
                        ->orWhere('loan_repay_amount' , 'LIKE', "%$search%")
                        ->orWhereHas('orders', function($query) use($search){
                            $query->where('order_number', 'LIKE',"%$search%");
                        })
                        ->orWhereHas('loanApplicantBusiness', function($query) use($search){
                            $query->where('email', 'LIKE',"%$search%");
                        })
                        ->orWhereHas('user', function($query) use($search){
                            $query->where(DB::raw('CONCAT(firstname, " ", lastname)'), 'LIKE',"%".$search."%");
                        });

                    // Begin: search by statuses
                    $searchLowerCase = strtolower($search);

                    if(in_array($searchLowerCase, ["disbursed by koinworks","settle","disbursed to supplier","disbursed to koinworks","repay",])){
                        if($searchLowerCase == "disbursed by koinworks"){
                            $q = $q->orWhereIn('status_id', [LOAN_STATUS['ONGOING'], LOAN_STATUS['NPL']]);
                        }elseif($searchLowerCase == "settle"){
                            $q = $q->orWhere(function($q2){
                                    $q2->where('status_id',  LOAN_STATUS['PAID_OFF'] )
                                    ->where('disbursed_to_supplier', 1)->where('disbursed_to_koinworks', 1);
                            });
                        }elseif($searchLowerCase == "repay"){
                            $q = $q->orWhere('status_id', LOAN_STATUS['REPAY'] )->where('disbursed_to_koinworks', 0)->where('disbursed_to_supplier', 0);
                        }elseif($searchLowerCase == "disbursed to koinworks"){
                            $q = $q->orWhere('status_id', LOAN_STATUS['REPAY'] )->where('disbursed_to_koinworks', 1);
                        }elseif($searchLowerCase == "disbursed to supplier"){
                            $q = $q->orWhere('status_id', LOAN_STATUS['REPAY'] )->where('disbursed_to_supplier', 1);
                        }
                    }
                    // End: search by statuses
                });
            }

            // Sorting
            if (!empty($column)) {
                if($column == 'loan_amount' || $column == 'loan_confirm_amount' || $column == 'loan_repay_amount'){
                    $query->orderByRaw('CONVERT('.$column.', SIGNED) '.$sort);
                }

                if ($column == 'id' ||$column == 'loan_number' || $column == 'provider_loan_id' || $column == 'provider_user_id' || $column == 'due_date') {
                    $query->orderBy($column, $sort);
                }

                if ($column == 'order_number') {
                    $query->orderBy('order_id', $sort);
                }

            } else {
                $query->orderBy('loan_number', $sort);
            }
            // Total Display records
            $totalRecords = $query->count();
            $totalDisplayRecords = $query->count();

            $payments = $query->skip($start)->take($length)->get();
            return response()->json([

                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalDisplayRecords,
                "aaData" => $payments->map(function ($payment) use($request){
                    $orderNumber = '<a href="javascript:void(0);" style="text-decoration: none; color: #000" class="viewOrderModal hover_underline" data-bs-toggle="modal" data-bs-target="#viewOrderModal" data-id="'.$payment->order_id.'">'.$payment->orders->order_number.'</a>';
                    $loanNumber = '<a href="javascript:void(0);" style="text-decoration: none; color: #000" class="loanModalView hover_underline" data-bs-toggle="modal" data-bs-target="#limitModal" data-id="'.Crypt::encrypt($payment->id).'">'.$payment->loan_number.'</a>';
                    $view = '<a class="ps-2 cursor-pointer loanModalView" data-id="'.Crypt::encrypt($payment->id).'" data-bs-toggle="modal" data-bs-target="#limitModal" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a>';
                    $action = $view;

                    //check is amount disburse or not from koinworks
                    $isKoinworkDisburse = $payment->paymentProviderTransaction()->where(['transaction_status'=>PAYMENT_TRANSACTION_STATUS['COMPLETED'],'transaction_type_id'=>TRANSACTION_TYPES['DISBURSMENT_KOINWORKS_TO_BLITZNET']])->count();
                    if ($isKoinworkDisburse) {
                        //check if disburse to supplier
                        $isDisburseToSupplier = $payment->paymentProviderTransaction()->where(['transaction_status'=>PAYMENT_TRANSACTION_STATUS['COMPLETED'],'transaction_type_id'=>TRANSACTION_TYPES['DISBURSEMENT_TO_SUPPLIER']])->count();
                        if(empty($isDisburseToSupplier)) {
                            $disbursmentBtn = '<a class="bg_icon ms-2" data-bs-toggle="" data-bs-target="#" data-toggle="tooltip" data-placement="top" title="Disburse" data-bs-original-title="' . __('admin.disburse') . '" aria-label="' . __('admin.disburse') . '" href="javascript:void(0);" onclick="disbusementToSupplier($(this),' . $payment->id . ')" ><img src="' . URL::asset('assets/icons/Disbursement.png') . '" alt="disburse"></a>';
                            $action = $view . $disbursmentBtn;
                        }
                    }

                    $dueDate = '-';
                    if($payment->due_date){
                        $dueDateCarbon = Carbon::createFromFormat('Y-m-d H:i:s', $payment->due_date);
                        $dueDate = Carbon::createFromFormat('Y-m-d H:i:s', $payment->due_date)->format('Y-m-d');
                        if($dueDateCarbon < Carbon::now() && $dueDateCarbon->diffInDays(Carbon::now()) != 0){
                            $dayDiff = $dueDateCarbon->diffInDays(Carbon::now());
                            $dueDate = $dayDiff > 1 ? $dayDiff.' '.__('admin.days_ago') : $dayDiff.' '.__('admin.day_ago') ;
                        }
                    }

                    $status = $payment->status_id;
                    if($payment->status_id == LOAN_STATUS['ONGOING'] || $payment->status_id == LOAN_STATUS['NPL']){
                        $status = "Disbursed by koinworks";
                        if($payment->disbursed_to_supplier == 1){
                            $status = "Disbursed to Supplier";
                        }
                    }elseif($payment->status_id == LOAN_STATUS['PAID_OFF']){
                        $status = "Repaid";
                        if($payment->disbursed_to_supplier == 1 && $payment->disbursed_to_koinworks == 1){
                            $status = "Settle";
                        }
                    }elseif($payment->status_id == LOAN_STATUS['REPAY']){
                        $status = "Repaid";
                        if($payment->disbursed_to_koinworks == 1){
                            $status = "Disbursed to koinworks";
                        }
                    }


                    return [
                        'id' => $payment->id,// id
                        'loan_number' => $loanNumber,// loan_id
                        'provider_loan_id' => $payment->provider_loan_id,// koinworks_loan_id
                        'provider_user_id' => $payment->provider_user_id,// user_id
                        'order_number' => $orderNumber, //order_number
                        'buyer_name' => $payment->user->firstname.' '.$payment->user->lastname, // buyer_name
                        'email' => $payment->loanApplicantBusiness->email ?? '-', // email
                        'loan_amount' => "Rp ".number_format($payment->loan_amount, 2), // Amount Requested
                        'loan_confirm_amount' => "Rp ".number_format($payment->loan_confirm_amount, 2), // Disburse amount
                        'loan_repay_amount' => "Rp ".number_format($payment->loan_repay_amount, 2), // Repayment Amount
                        'due_date' => $dueDate,
                        'status' => "<span class='badge badge-pill badge-success'>$status</span>",
                        'action' => $action,
                    ];
                })
            ]);
        }

        $query = LoanApply::with('orders:id,order_number','user:id,firstname,lastname,email','company:id,name')
            ->select(['loan_number','provider_loan_id','provider_user_id','order_id','user_id','loan_provider_id','loan_amount','loan_confirm_amount','due_date','company_id'])
            ->whereIn('status_id', $loanStatusIds);

        /**begin: system log**/
        LoanApply::bootSystemView(new LoanApply(), 'Disbursement', SystemActivity::VIEW);
        /**end: system log**/

        return View::make('admin.disbursement.disbursement');
    }

    public function supplierDisburse(Request $request)
    {
        $loan = LoanApply::find($request->id);
        $xenditLoanTransController = new XenditLoanTransactionController;
        return $xenditLoanTransController->supplierDisbursement($loan,0);
    }
}
