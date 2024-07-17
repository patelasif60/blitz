<?php

namespace App\Http\Controllers;

use App\Models\PaymentGroup;
use App\Models\SystemActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Auth;

class AdminPaymentGroupController extends Controller
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
        $this->middleware('permission:create payment groups|edit payment groups|delete payment groups|publish payment groups|unpublish payment groups', ['only'=> ['list']]);
        $this->middleware('permission:create payment groups', ['only' => ['paymentGroupAdd', 'create']]);
        $this->middleware('permission:edit payment groups', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete payment groups', ['only' => ['delete']]);
    }

    function list()
    {
        $payment_groups = PaymentGroup::all()->where('is_deleted',0)->sortDesc();

        /**begin: system log**/
        PaymentGroup::bootSystemView(new PaymentGroup());
        /**end:  system log**/
        return view('admin/payment/paymentGroupList', ['payment_groups' => $payment_groups]);
    }
    function paymentGroupAdd()
    {
        /**begin: system log**/
        PaymentGroup::bootSystemView(new PaymentGroup(), 'Payment Group', SystemActivity::ADDVIEW);
        /**end: system log**/
        return view('admin/payment/paymentGroupAdd');
    }
    function create(Request $request)
    {
        $payment_groups = new PaymentGroup;
        $payment_groups->name = $request->name;
        $payment_groups->description = $request->description;
        $payment_groups->status = $request->status;
        $payment_groups->save();

        /**begin: system log**/
        $payment_groups->bootSystemActivities();
        /**end: system log**/
        return response()->json(array('success' => true));

        return redirect('/admin/payment-groups');

    }
    function edit($id)
    {
        $id = Crypt::decrypt($id);
        $payment_groups = PaymentGroup::find($id);

        /**begin: system log**/
        $payment_groups->bootSystemView(new PaymentGroup(), 'Payment Group', SystemActivity::EDITVIEW, $payment_groups->id);
        /**end: system log**/
        return view('admin/payment/paymentGroupEdit', ['payment_groups' => $payment_groups]);

    }
    function update(Request $request)
    {
        $payment_groups = PaymentGroup::find($request->id);
        $payment_groups->name = $request->name;
        $payment_groups->description = $request->description;
        $payment_groups->status = $request->status;
        $payment_groups->save();

        /**begin: system log**/
        $payment_groups->bootSystemActivities();
        /**end: system log**/
        return response()->json(array('success' => true));

        return redirect('/admin/payment-groups');

    }
    function delete(Request $request)
    {
        $payment_groups = PaymentGroup::find($request->id);
        $payment_groups->is_deleted = 1;
        $payment_groups->save();
        /**begin: system log**/
        $payment_groups->bootSystemActivities();
        /**end: system log**/
    }
}
