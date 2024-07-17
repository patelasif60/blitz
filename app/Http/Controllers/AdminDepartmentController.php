<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\SystemActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AdminDepartmentController extends Controller
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
        $this->middleware('permission:create department|edit department|delete department|publish department|unpublish department', ['only'=> ['list']]);
        $this->middleware('permission:create department', ['only' => ['departmentAdd', 'create']]);
        $this->middleware('permission:edit department', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete department', ['only' => ['delete']]);
    }
    function list()
    {
        $department = Department::all()->where('is_deleted',0)->sortDesc();

        /**begin: system log**/
        Department::bootSystemView(new Department());
        /**end:  system log**/
        return view('admin/department/departmentList', ['department' => $department]);
    }
    function departmentAdd()
    {
        /**begin: system log**/
        Department::bootSystemView(new Department(), 'Department', SystemActivity::ADDVIEW);
        /**end: system log**/
        return view('admin/department/departmentAdd');
    }
    function create(Request $request)
    {
        $department = new Department();
        $department->name = $request->name;
        $department->status = $request->status;
        $department->added_by =  Auth::id();
        $department->save();

        /**begin: system log**/
        $department->bootSystemActivities();
        /**end: system log**/
        return response()->json(array('success' => true));

        return redirect('/admin/departments');

    }
    function edit($id)
    {
        $id = Crypt::decrypt($id);
        $department = Department::find($id);

        /**begin: system log**/
        $department->bootSystemView(new Department(), 'Department', SystemActivity::EDITVIEW, $department->id);
        /**end: system log**/
        return view('admin/department/departmentEdit', ['department' => $department]);

    }
    function update(Request $request)
    {
        $department = Department::find($request->id);
        $department->name = $request->name;
        $department->status = $request->status;
        $department->updated_by =  Auth::id();
        $department->save();

        /**begin: system log**/
        $department->bootSystemActivities();
        /**end: system log**/
        return response()->json(array('success' => true));

        return redirect('/admin/departments');

    }
    function delete(Request $request)
    {
        $department = Department::find($request->id);
        $department->is_deleted = 1;
        $department->deleted_by =  Auth::id();
        $department->save();
        /**begin: system log**/
        $department->bootSystemActivities();
        /**end: system log**/
    }
}
