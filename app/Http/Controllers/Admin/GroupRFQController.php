<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rfq;
use App\Models\UserSupplier;
use App\Models\AdminFeedbackReasons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class GroupRFQController extends Controller
{
   //Get Group RFQs only
   public function groupRFQlist() {
    if (auth()->user()->role_id == 3) {
        $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
        $groupRFQs = Rfq::join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
        ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
        ->join('group_members','rfqs.id','=','group_members.rfq_id')
        ->join('group_suppliers','group_members.group_id','=','group_suppliers.group_id')
        ->where('group_suppliers.supplier_id',$supplier_id)
        ->where('group_members.is_deleted',0)
        ->where('rfqs.group_id','!=',null)
        ->orderBy('rfqs.id', 'desc')
        ->get(['rfqs.id', 'rfqs.firstname', 'rfqs.lastname', 'rfqs.created_at', 'rfqs.reference_number', 'rfqs.is_require_credit', 'rfq_products.category', 'rfq_products.sub_category', 'rfq_products.product', 'rfq_products.product_description', 'rfq_status.backofflice_name as status_name','rfq_status.id as rfq_status_id','group_members.group_id']);
    } else {
        $groupRFQs = Rfq::join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
        ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
        ->join('group_members','rfqs.id','=','group_members.rfq_id')
        ->where('rfqs.group_id','!=',null)
        ->orderBy('rfqs.id', 'desc')
        ->get(['rfqs.id', 'rfqs.firstname', 'rfqs.lastname', 'rfqs.created_at', 'rfqs.reference_number', 'rfqs.is_require_credit', 'rfq_products.category', 'rfq_products.sub_category', 'rfq_products.product', 'rfq_products.product_description', 'rfq_status.backofflice_name as status_name','rfq_status.id as rfq_status_id','group_members.group_id']);
    }
    $groupCount = array_unique($groupRFQs->pluck('group_id')->toArray());
    foreach ($groupRFQs as $rfq){
        $rfqItems = DB::table('rfq_products')->where('rfq_id',$rfq->id)->get();
        $rfq->product = $rfqItems->count().' '.__('admin.products');
        if ($rfqItems->count()==1){
            $rfq->product = get_product_name_by_id($rfqItems[0]->id,1);
        }
    }
    //dd($groupRFQs->toArray());
    $user_rfq = DB::table('user_rfqs')->get(['rfq_id']);
    $users = [];
    foreach ($user_rfq as $user)
    $users[] = $user->rfq_id;

    $rfqDataHtml = view('admin/rfq/rfqTableData', ['rfqs' => $groupRFQs, 'rfq_user' => $users])->render();
    $getFeedbackReasions = AdminFeedbackReasons::where('reasons_type', 1)->get();
    return view('admin/rfq/group_rfq_list', ['rfqs' => $groupRFQs, 'rfqDataHtml' => $rfqDataHtml, 'groupCount' => $groupCount, 'feedbackReasions' => $getFeedbackReasions]);
}
}
