<?php

namespace App\Http\Controllers\Buyer\Credit;

use App\Http\Controllers\Buyer\Xendit\LoanTransaction\XenditLoanTransactionController;
use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\LoanApply;
use App\Models\Order;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use View;
use Illuminate\Support\Facades\Log;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:utilize buyer company credit',['only' =>['index']]);
    }

    public function index()
    {
        try {
            $creditInfoDetail = LoanApplication::getCreditDatail();

            if (empty($creditInfoDetail)) {
                abort('404');
            }

            return View::make('buyer.credit.index')->with(compact('creditInfoDetail'));

        } catch(\Exception $e) {
            abort('404');
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

    /**
     * Credit List Component
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function creditList()
    {
        $authUser = \Auth::user();

        $isOwner = User::checkCompanyOwner();

        $query = Order::with('quote','quote.quoteChargesWithAmounts','quote.quoteItems','quote.quoteItems.rfqProduct','loanApply','loanApply.paymentLink','companyDetails','orderItems','loanTransactions','loanTransactions.loanCharge.loanProviderChargesType')->where('is_deleted',0)->whereHas('loanApply',function ($query){
            return $query->whereIn('status_id',[LOAN_STATUS['ONGOING'],LOAN_STATUS['NPL']]);
        });

        if ($isOwner || $authUser->hasPermissionTo('list-all buyer company credits')) {
            $query->where('company_id', $authUser->default_company);

        } else {
            $query->where('user_id', $authUser->id);

        }

        $query = $query->whereHas('loanApply');

        return $query;
    }

    /**
     * Credit payment link
     *
     * @param $loanApplyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentLink(Request $request)
    {
        try {
            $loanId = Crypt::decrypt($request->id);

            $loan = LoanApply::findOrFail($loanId);

            $data = (new XenditLoanTransactionController())->generateXenditPaymentLink($loan);

            return $data;

        } catch (\Exception $exception) {
            Log::critical('Code - 503 | ErrorCode:B024 Get Payment Link');

            return response()->json(['success' => false, 'message' => __('admin.please_try_again_later'), 'data' => []]);

        }
    }
}
