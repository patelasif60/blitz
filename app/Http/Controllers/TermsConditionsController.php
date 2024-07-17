<?php

namespace App\Http\Controllers;

use App\Models\TermsCondition;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App;
use App\Models\SystemActivity;
use File;

class TermsConditionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role_id != 1){
                return redirect()->back()->with('error', 'Access Denied.');
            } else {
                return $next($request);
            }
        });
    }
    function index(){
        $default_tcdoc = [];
        $default_tcdoc = TermsCondition::first();//dd($default_tcdoc);

        /**begin: system log**/
        if (!empty($default_tcdoc)) {
            TermsCondition::bootSystemView(new TermsCondition(), null, SystemActivity::EDITVIEW, $default_tcdoc->id);
        } else {
            TermsCondition::bootSystemView(new TermsCondition(), null, SystemActivity::ADDVIEW);
        }
        /**end:  system log**/

        return view('admin/TermsCondition',['default_tcdoc' => $default_tcdoc]);
    }

    function updateDefaultFiles(Request $request){

        $tcdocId = (isset($request->id) && $request->id!=="") ? ($request->id) : '';
         if($tcdocId==''){
                $rfqAttachmentFilePath = '';
                if ($request->file('buyer_default_tcdoc')) {
                    $rfqAttachmentFileName = Str::random(5) . '_' . time() . '_' . $request->file('buyer_default_tcdoc')->getClientOriginalName();
                    $rfqAttachmentFilePath = $request->file('buyer_default_tcdoc')->storeAs('uploads/tc_docs', $rfqAttachmentFileName, 'public');
                }
                $quoteAttachmentFilePath = '';
                if ($request->file('supplier_default_tcdoc')) {
                    $quoteAttachmentFileName = Str::random(5) . '_' . time() . '_' . $request->file('supplier_default_tcdoc')->getClientOriginalName();
                    $quoteAttachmentFilePath = $request->file('supplier_default_tcdoc')->storeAs('uploads/tc_docs', $quoteAttachmentFileName, 'public');
                }
                $tcdoc = TermsCondition::create([
                    'buyer_default_tcdoc' => $rfqAttachmentFilePath,
                    'supplier_default_tcdoc' => $quoteAttachmentFilePath
                ]);

                return response()->json(array('success' => true));
         }else{
            $getRfq = TermsCondition::where('id',$request->id)->first();
            if ($request->file('buyer_default_tcdoc')) {
                $rfqAttachmentFileName = Str::random(5) . '_' . time() . '_' . $request->file('buyer_default_tcdoc')->getClientOriginalName();
                $rfqAttachmentFilePath = $request->file('buyer_default_tcdoc')->storeAs('uploads/tc_docs', $rfqAttachmentFileName, 'public');
                if (!empty($getRfq->buyer_default_tcdoc)) {
                    Storage::delete('/public/' . $getRfq->buyer_default_tcdoc);
                }
            }else{
                $rfqAttachmentFilePath =$getRfq->buyer_default_tcdoc;
            }
            if ($request->file('supplier_default_tcdoc')) {
                $quoteAttachmentFileName = Str::random(5) . '_' . time() . '_' . $request->file('supplier_default_tcdoc')->getClientOriginalName();
                $quoteAttachmentFilePath = $request->file('supplier_default_tcdoc')->storeAs('uploads/tc_docs', $quoteAttachmentFileName, 'public');
                if (!empty($getRfq->supplier_default_tcdoc)) {
                    Storage::delete('/public/' . $getRfq->supplier_default_tcdoc);
                }
            }else{
                $quoteAttachmentFilePath = $getRfq->supplier_default_tcdoc;
            }
            $updatetcdoc =[
                'buyer_default_tcdoc' => $rfqAttachmentFilePath,
                'supplier_default_tcdoc' => $quoteAttachmentFilePath
            ];
             TermsCondition::find($tcdocId)->update($updatetcdoc);



             /**begin: system log**/
             TermsCondition::bootSystemActivities();
             /**end: system log**/
             return response()->json(array('success' => true));
         }
    }


    //Delete RFQ Attachment
    function deleteDefaultTCFile(Request $request) {//dd($request->all());
        $tcclmn = TermsCondition::find($request->id);
        $columnName = $request->fileName;
        if (isset($tcclmn->$columnName) && !empty($tcclmn->$columnName)) {
            Storage::delete('/public/' . $tcclmn->$columnName);
            $tcclmn->$columnName = '';
            $tcclmn->save();
        }
        return response()->json(array('success' => true,'$tcclmn'=>$tcclmn->$columnName));
    }
}
