<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Models\Brand;
use App\Models\SystemActivity;
use Auth;

class BrandController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:create brands|edit brands|delete brands|publish brands|unpublish brands', ['only' => ['list']]);
        $this->middleware('permission:create brands', ['only' => ['create','brandAdd']]);
        $this->middleware('permission:edit brands', ['only' => ['edit','update']]);
        $this->middleware('permission:delete brands', ['only' => ['delete']]);
    }

    function list()
    {
        $brands = Brand::all()->where('is_deleted', 0)->sortDesc();

        /**begin: system log**/
        Brand::bootSystemView(new Brand());
        /**end:  system log**/
        return view('admin/brandsList', ['brands' => $brands]);
    }


    function create(Request $request)
    {
        if(isset($request->name)) {
            $duplicateData = checkDuplication('brands',trim($request->name));
        }
        if($duplicateData == true) {
            $brand = new Brand;
            $brand->name = $request->name;
            $brand->description = $request->description;
            $brand->status = $request->status;
            $brand->added_by = Auth::id();
            $brandData = $brand->save();

            /**begin: system log**/
            $brand->bootSystemActivities();
            /**end: system log**/
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('success' => false));
        }
        return redirect('/admin/brands');
    }

    function edit($id)
    {
        $id = Crypt::decrypt($id);
        $brand = Brand::find($id);

        /**begin: system log**/
        $brand->bootSystemView(new Brand(), 'Brand', SystemActivity::EDITVIEW, $brand->id);
        /**end: system log**/
        if ($brand) {
            return view('/admin/brandEdit', ['brand' => $brand]);
        } else {
            return redirect('/admin/brands');
        }
    }

    function update(Request $request)
    {
         if(isset($request->name)) {
             $duplicateData = checkDuplication('brands',trim($request->name));
         }
        //check if brand exist
        $brandExist = Brand::where('name', trim($request->name))->whereNotIn('id', [$request->id])->count();
        $duplicateData = $brandExist>0 ? false : true;

        if($duplicateData == true) {
            $brand = Brand::find($request->id);
            $brand->name = $request->name;
            $brand->description = $request->description;
            $brand->status = $request->status;
            $brand->updated_by =Auth::id();

            $brandData = $brand->save();

            /**begin: system log**/
            $brand->bootSystemActivities();
            /**end: system log**/
            return response()->json(array('success' => true));
         } else {
             return response()->json(array('success' => false));
         }
        return redirect('/admin/brands');
    }

    function delete(Request $request)
    {
        $brand = Brand::find($request->id);
        $brand->is_deleted = 1;
        $brand->deleted_by = Auth::id();

        $brand->save();

        /**begin: system log**/
        $brand->bootSystemActivities();
        /**end: system log**/
        return $request->id;
    }
}
