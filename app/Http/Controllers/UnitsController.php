<?php

namespace App\Http\Controllers;

use App\Models\SystemActivity;
use Illuminate\Http\Request;
use App\Models\Unit;
use Auth;
use Illuminate\Support\Facades\Crypt;

class UnitsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create units|edit units|delete units|publish units|unpublish units', ['only' => ['list']]);
        $this->middleware('permission:create units', ['only' => ['create', 'unitAdd']]);
        $this->middleware('permission:edit units', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete units', ['only' => ['delete']]);
    }



    function list()
    {
        $units = Unit::all()->where('is_deleted', 0)->sortDesc();

        /**begin: system log**/
        Unit::bootSystemView(new Unit());
        /**end:  system log**/

        return view('admin/unitsList', ['units' => $units]);
    }


    function create(Request $request)
    {
        if(isset($request->name)) {
            $duplicateData = checkDuplication('units',trim($request->name));
        }
        if($duplicateData == true) {
            $unit = new Unit;
            $unit->name = $request->name;
            $unit->description = $request->description;
            $unit->status = $request->status;
            $unit->added_by = Auth::id();

            $unitData = $unit->save();

            /**begin: system log**/
            $unit->bootSystemActivities();
            /**end: system log**/
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('success' => false));
        }
        return redirect('/admin/units');
    }

    function edit($id)
    {
        $id = Crypt::decrypt($id);
        $unit = Unit::find($id);

        /**begin: system log**/
        $unit->bootSystemView(new Unit(), 'Units', SystemActivity::EDITVIEW, $unit->id);
        /**end: system log**/
        if ($unit) {
            return view('/admin/unitEdit', ['unit' => $unit]);
        } else {
            return redirect('/admin/units');
        }
    }

    function update(Request $request)
    {
         if(isset($request->name)) {
             $duplicateData = checkDuplication('units',trim($request->name));
         }
        //check if units exist
        $unitExist = Unit::where('name', trim($request->name))->whereNotIn('id', [$request->id])->count();
        $duplicateData = $unitExist>0 ? false : true;

        if($duplicateData == true) {
            $unit = Unit::find($request->id);
            $unit->name = $request->name;
            $unit->description = $request->description;
            $unit->status = $request->status;
            $unit->updated_by =Auth::id();

            $unitData = $unit->save();

            /**begin: system log**/
            $unit->bootSystemActivities();
            /**end: system log**/
            return response()->json(array('success' => true));
         } else {
             return response()->json(array('success' => false));
         }

        return redirect('/admin/units');
    }

    function delete(Request $request)
    {
        $unit = Unit::find($request->id);
        $unit->is_deleted = 1;
        $unit->deleted_by = Auth::id();

        $unit->save();

        /**begin: system log**/
        $unit->bootSystemActivities();
        /**end: system log**/
        return $request->id;
    }
}
