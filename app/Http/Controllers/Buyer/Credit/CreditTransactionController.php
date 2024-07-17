<?php

namespace App\Http\Controllers\Buyer\Credit;

use App\Http\Controllers\Controller;
use App\Models\LoanApply;
use App\Models\LoanTransaction;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use View;

class CreditTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            return View::make('buyer.transaction.index');

        } catch(\Exception $e) {
            abort('404');
        }
    }

    /**
     * Display a AJAX listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listJson(Request $request)
    {

        $authUser       = \Auth::user();

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

            $isOwner = User::checkCompanyOwner(); //Is Company Owner

            $query = LoanApply::with('orders','loanTransactions')->whereIn('status_id',[LOAN_STATUS['COMPLETED'],LOAN_STATUS['BUYER_REPAID']]);

            if ($isOwner == true) {
                $query->where('company_id', $authUser->default_company);
            } else {
                $query->where('company_id', $authUser->default_company)->where('user_id', $authUser->id);
            }

            // Total records
            $totalRecords = $query->count();

            if ($search!="") {
                $query = $query->where(function($query) use($search){
                    return $query->where('loan_number', 'LIKE', "%$search%")
                        ->orWhere('paid_amount','LIKE', "%$search%")
                        ->orWhere(Order::select('order_number')->whereColumn('loan_applies.order_id', 'orders.id'),'LIKE', "%$search%");
                })
                ->whereHas('orders')
                ->whereIn('status_id',[LOAN_STATUS['COMPLETED'],LOAN_STATUS['BUYER_REPAID']]);
            }

            if ($column=='order_number') {
                $query = $query->orderBy(Order::select('order_number')
                    ->whereColumn('loan_applies.order_id', 'orders.id'),$sort);
            }

            if ($column=='loan_id') {
                $query = $query->orderBy('id',$sort);
            }

            // Total Display records
            $totalDisplayRecords = $query->count();

            $loans = $query->skip($start)->take($length);

            $loans = $query->get();

            return response()->json([

                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalDisplayRecords,
                "aaData" => $loans->map(function ($loan) {
                    $viewBtn    =   '<a  class="px-2 loanCalculation" data-id="'.\Crypt::encrypt($loan->id).'" data-bs-toggle="modal" data-bs-target="#viewLimitModal" data-toggle="tooltip" ata-placement="top" title="'.__('admin.view').'"><i class="fa fa-eye"></i></a>';

                    $action     =   $viewBtn;

                    $repayTransaction = $loan->loanTransactions->where('transaction_type_id',TRANSACTION_TYPES['BUYER_REPAYMENT'])->first();

                    return [
                        'loan_id'           =>  $loan->loan_number,
                        'order_number'      =>  !empty($loan->orders) ? $loan->orders->order_number : '',
                        'loan_amount'       =>  'Rp '.(!empty($loan->loan_confirm_amount) ? number_format($loan->loan_confirm_amount,2) : 0.00),
                        'paid_amount'       =>  'Rp '.(!empty($loan->paid_amount) ? number_format($loan->paid_amount,2) : 0.00),
                        'paid_date'         =>  !empty($repayTransaction) ? Carbon::parse($repayTransaction->created_at)->format('d-m-Y') : '',
                        'action' =>  $action,
                        'quote_id'=>$loan->orders->quote_id,
                    ];

                })
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
}
