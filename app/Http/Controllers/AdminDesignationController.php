<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\SystemActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AdminDesignationController extends Controller
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
        $this->middleware('permission:create designation|edit designation|delete designation|publish designation|unpublish designation', ['only' => ['list']]);
        $this->middleware('permission:create designation', ['only', ['designationAdd', 'create']]);
        $this->middleware('permission:edit designation', ['only', ['edit', 'update']]);
        $this->middleware('permission:delete designation', ['only', ['delete']]);
    }

    function list()
    {
        $designation = Designation::all()->where('is_deleted',0)->sortDesc();


        /**begin: system log**/
        Designation::bootSystemView(new Designation());
        /**end:  system log**/
        return view('admin/designation/designationList', ['designation' => $designation]);
    }
    function designationAdd()
    {
        /**begin: system log**/
        Designation::bootSystemView(new Designation(), 'Designation', SystemActivity::ADDVIEW);
        /**end: system log**/
        return view('admin/designation/designationAdd');
    }
    function create(Request $request)
    {
        $designation = new Designation();
        $designation->name = $request->name;
        $designation->status = $request->status;
        $designation->added_by =  Auth::id();
        $designation->save();

        /**begin: system log**/
        $designation->bootSystemActivities();
        /**end: system log**/
        return response()->json(array('success' => true));

        return redirect('/admin/designations');

    }
    function edit($id)
    {
        $id = Crypt::decrypt($id);
        $designation = Designation::find($id);

        /**begin: system log**/
        $designation->bootSystemView(new Designation(), 'Designation', SystemActivity::EDITVIEW, $designation->id);
        /**end: system log**/
        return view('admin/designation/designationEdit', ['designation' => $designation]);

    }
    function update(Request $request)
    {
        $designation = Designation::find($request->id);
        $designation->name = $request->name;
        $designation->status = $request->status;
        $designation->updated_by =  Auth::id();
        $designation->save();

        /**begin: system log**/
        $designation->bootSystemActivities();
        /**end: system log**/
        return response()->json(array('success' => true));

        return redirect('/admin/designations');

    }
    function delete(Request $request)
    {
        $designation = Designation::find($request->id);
        $designation->is_deleted = 1;
        $designation->deleted_by =  Auth::id();
        $designation->save();

        /**begin: system log**/
        $designation->bootSystemActivities();
        /**end: system log**/
    }
}
