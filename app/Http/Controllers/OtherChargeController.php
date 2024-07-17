<?php

namespace App\Http\Controllers;
use App\Models\OtherCharge;
use App\Models\SystemActivity;
use App\Models\UserCompanies;
use App\Models\XenditCommisionFee;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class OtherChargeController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role_id == 3){
                return redirect()->back()->with('error', 'Access Denied.');
            } else {
                return $next($request);
            }
        });
        $this->middleware('permission:create charges|edit charges|delete charges|publish charges|unpublish charges', ['only' => ['list']]);
        $this->middleware('permission:create charges', ['only' => ['chargesAdd', 'store']]);
        $this->middleware('permission:edit charges', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete charges', ['only' => ['delete']]);
    }

    function list()
    {
        $charges = OtherCharge::all()->where('is_deleted',0)->sortDesc();

        /**begin: system log**/
        OtherCharge::bootSystemView(new OtherCharge());
        /**end:  system log**/

        return view('admin/chargesList', ['charges' => $charges]);
    }
    function create(Request $request)
    {
        if(isset($request->chargeName)) {
            $duplicateData = checkDuplication('other_charges',trim($request->chargeName),$request->chargeValue,$request->chargeType,$request->charges_type,$request->addition_substraction);
        }

        if($duplicateData == true) {
            $data = array(
                'name' => $request->chargeName,
                'description' => $request->description,
                'type' => $request->chargeType,
                'charges_value' => $request->chargeValue,
                'value_on' => 0,
                'status' => $request->status,
                'addition_substraction' => $request->addition_substraction,
                'editable' => ((isset($request->editable) && $request->editable == 1) ? 1 : 0),
                'charges_type' => $request->charges_type,
                'added_by' => Auth::id()
            );
            $charge = OtherCharge::create($data);
            /**begin: system log**/
            OtherCharge::bootSystemActivities();
            /**end: system log**/
            if ($request->charges_type == 2){
                AddNewXenditCommisionFee($charge->id,$data);
            }
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('success' => false));
        }

        return redirect('/admin/charges');
    }

    function edit($id)
    {
        $id = Crypt::decrypt($id);
        $charge = OtherCharge::find($id);

        /**begin: system log**/
        $charge->bootSystemView(new OtherCharge(), 'OtherCharges', SystemActivity::EDITVIEW, $charge->id);
        /**end: system log**/
        if ($charge) {
            return view('/admin/chargesEdit', ['charge'=> $charge]);
        } else {
            return redirect('/admin/charges');
        }
    }

    function update(Request $request)
    {
         if(isset($request->chargeName)) {
             $duplicateData = checkDuplication('other_charges',trim($request->chargeName),$request->chargeValue,$request->chargeType,$request->charges_type,$request->addition_substraction);
         }
        //check if other charges exist
        $otherCharge = OtherCharge::find($request->id);
        $otherChargesExist = OtherCharge::where('name', trim($request->chargeName))->where('charges_value', trim($request->chargeValue))->where('type', trim($request->chargeType))->where('charges_type', trim($request->charges_type))->where('addition_substraction',$request->addition_substraction)->whereNotIn('id', [$request->id])->count();
        $duplicateData = $otherChargesExist>0 ? false : true;
        if($duplicateData == true) {
            $data = array(
                'id' => $request->id,
                'name' => $request->chargeName,
                'description' => $request->description,
                'type' => $request->chargeType,
                'charges_value' => $request->chargeValue,
                'value_on' => 0,
                'status' => $request->status,
                'addition_substraction' => $request->addition_substraction,
                'editable' => ((isset($request->editable) && $request->editable == 1) ? 1 : 0),
                'charges_type' => $request->charges_type,
                'updated_by' => Auth::id()
            );

            $charge = OtherCharge::where('id', $request->id)->update($data);
            //Update All Xendit Commission Fee
            $request->editable = isset($request->editable) ? $request->editable : 0;
            if ($otherCharge->charges_type == 2 && $request->editable == 0){
                XenditCommisionFee::where('charge_id', $otherCharge->id)->update(['is_delete' => 0, 'charges_value' => $request->chargeValue, 'type'=>$request->chargeType, 'addition_substraction'=>$request->addition_substraction]);
            }
            /**begin: system log**/
            OtherCharge::bootSystemActivities();
            /**end: system log**/

            if ($otherCharge->charges_type == 2 && $otherCharge->charges_type != $request->charges_type){
                //remove all xendit commision fee for all company
                XenditCommisionFee::where('charge_id', $otherCharge->id)->delete();
            } elseif ($request->charges_type == 2 && $otherCharge->charges_type != $request->charges_type){
                //add xendit commion fee
                AddNewXenditCommisionFee($request->id,$data);
            }

            return response()->json(array('success' => true));
        } else {
            return response()->json(array('success' => false));
        }
        return redirect('/admin/charges');
    }
    function delete(Request $request)
    {
        $charge = OtherCharge::find($request->id);
        $charge->is_deleted = 1;
        $charge->deleted_by = Auth::id();
        $charge->save();
        if ($charge->charges_type == 2){
            XenditCommisionFee::where('charge_id', $request->id)->delete();
        }
        /**begin: system log**/
        $charge->bootSystemActivities();
        /**end: system log**/
        return $charge->id;
    }
}
