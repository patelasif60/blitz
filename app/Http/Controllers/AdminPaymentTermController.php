<?php

namespace App\Http\Controllers;
use App\Models\PaymentGroup;
use App\Models\PaymentTerms;
use App\Models\SystemActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Auth;

class AdminPaymentTermController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role_id == 3){
                return redirect()->back()->with('error', 'Access Denied.');
            } else {
                return $next($request);
            }
        });
        $this->middleware('permission:create payment terms|edit payment terms|delete payment terms|publish payment terms|unpublish payment terms', ['only'=> ['list']]);
        $this->middleware('permission:create payment terms', ['only' => ['paymentTermAdd', 'create']]);
        $this->middleware('permission:edit payment terms', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete payment terms', ['only' => ['delete']]);
    }

    function list()
    {
        $payment_terms = PaymentTerms::join('payment_groups', 'payment_terms.payment_group_id', '=', 'payment_groups.id')
            ->where('payment_terms.is_deleted',0)
            ->latest()
            ->get(['payment_terms.*', 'payment_groups.name as group_name']);

            /**begin: system log**/
            PaymentTerms::bootSystemView(new PaymentTerms());
            /**end:  system log**/
        return view('admin/payment/paymentTermList', ['payment_terms' => $payment_terms]);
    }
    function paymentTermAdd()
    {
        $p_groups = PaymentGroup::all()->where('is_deleted',0);

        /**begin: system log**/
        PaymentTerms::bootSystemView(new PaymentTerms(), 'Payment Terms', SystemActivity::ADDVIEW);
        /**end: system log**/
        return view('admin/payment/paymentTermAdd',['p_groups' => $p_groups]);
    }
    function create(Request $request)
    {
        $payment_terms = new PaymentTerms;
        $payment_terms->payment_group_id = $request->group;
        $payment_terms->name = $request->name;
        $payment_terms->description = $request->description;
        $payment_terms->status = $request->status;
        $payment_terms->save();

        /**begin: system log**/
        $payment_terms->bootSystemActivities();
        /**end: system log**/
        return response()->json(array('success' => true));

        return redirect('/admin/payment-terms');

    }
    function edit($id)
    {
        $id = Crypt::decrypt($id);
        $payment_terms = PaymentTerms::find($id);
        if ($payment_terms)
        {
			$payment_groups = PaymentGroup::all()->where('is_deleted',0);

            /**begin: system log**/
            $payment_terms->bootSystemView(new PaymentTerms(), 'Payment Terms', SystemActivity::EDITVIEW, $payment_terms->id);
            /**end: system log**/
            return view('admin/payment/paymentTermEdit', ['payment_terms' => $payment_terms,'payment_groups' => $payment_groups]);
        }
        else
        {
            return redirect('/admin/payment-terms');
        }
    }
    function update(Request $request)
    {
        $payment_terms = PaymentTerms::find($request->id);
        $payment_terms->payment_group_id = $request->group;
        $payment_terms->name = $request->name;
        $payment_terms->description = $request->description;
        $payment_terms->status = $request->status;
        $payment_terms->save();

        /**begin: system log**/
        $payment_terms->bootSystemActivities();
        /**end: system log**/
        return response()->json(array('success' => true));

        return redirect('/admin/payment-terms');

    }
    function delete(Request $request)
    {
        $payment_terms = PaymentTerms::find($request->id);
        $payment_terms->is_deleted = 1;
        $payment_terms->save();

        /**begin: system log**/
        $payment_terms->bootSystemActivities();
        /**end: system log**/
    }
}
