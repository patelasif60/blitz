<?php

namespace App\Http\Controllers;

use App\Models\Rfq;
use App\Models\RfqProduct;
use App\Models\SupplierProduct;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SystemActivity;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Auth;

class SubCategoryController extends Controller
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
        $this->middleware('permission:create sub-category|edit sub-category|delete sub-category|publish sub-category|unpublish sub-category', ['only' => ['list']]);
        $this->middleware('permission:create sub-category', ['only' => ['create', 'subCategoryAdd']]);
        $this->middleware('permission:edit sub-category', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete sub-category', ['only' => ['delete']]);
    }

    function list()
    {
        $query = SubCategory::join('categories', 'sub_categories.category_id', '=', 'categories.id');

        //Agent category permissions
        if (Auth::user()->hasRole('agent')) {

            $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();

            $query->whereIn('category_id', $assignedCategory);

        }

        $subCategories = $query->where('sub_categories.is_deleted', 0)
            ->latest()
            ->get(['sub_categories.*', 'categories.name as category_name']);

        /**begin: system log**/
        SubCategory::bootSystemView(new SubCategory());
        /**end:  system log**/
        return view('admin/subCategoryList', ['subCategories' => $subCategories]);
    }

    function subCategoryAdd()
    {
        $categories = Category::all()->where('is_deleted',0);

        //Agent category permissions
        if (Auth::user()->hasRole('agent')) {

            $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();

            $categories = Category::all()->whereIn('id', $assignedCategory)->where('is_deleted',0);

        }

        return view('admin/subCategoryAdd', ['categories' => $categories]);
    }

    function create(Request $request)
    {
        if(isset($request->name)) {
            $duplicateData = checkDuplication('sub_categories',trim($request->name),$request->category);
        }
        if($duplicateData == true) {
            $subCategory = new SubCategory;
            $subCategory->category_id = $request->category;
            $subCategory->name = $request->name;
            $subCategory->description = $request->description;
            $subCategory->status = $request->status;
            $subCategory->added_by =  Auth::id();

            $subCategoryData = $subCategory->save();

            /**begin: system log**/
            $subCategory->bootSystemActivities();
            /**end: system log**/
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('success' => false));
        }
        return redirect('/admin/sub-categories');
    }
    function edit($id)
    {
        $id = Crypt::decrypt($id);
        $subCategory = SubCategory::find($id);
        if ($subCategory) {
            $categories = Category::all()->where('is_deleted',0);

            //Agent category permissions
            if (Auth::user()->hasRole('agent')) {

                $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();

                $categories = Category::all()->whereIn('id', $assignedCategory)->where('is_deleted',0);

            }

            /**begin: system log**/
            $subCategory->bootSystemView(new SubCategory(), 'SubCategory', SystemActivity::EDITVIEW, $subCategory->id);
            /**end: system log**/
            return view('/admin/subCategoryEdit', ['subCategory' => $subCategory, 'categories' => $categories]);
        } else {
            return redirect('/admin/sub-categories');
        }
    }

    function update(Request $request)
    {
        if(isset($request->name)) {
            $duplicateData = checkDuplication('sub_categories',trim($request->name),$request->category);
        }
        //check if subcategory exist
        $subCategoryExist = SubCategory::where('name', trim($request->name))->where('category_id',$request->category)->whereNotIn('id', [$request->id])->count();
        $duplicateData = $subCategoryExist>0 ? false : true;

        if($duplicateData == true) {
            $subCategory = SubCategory::find($request->id);
            $subCategory->category_id = $request->category;
            $subCategory->name = $request->name;
            $subCategory->description = $request->description;
            $subCategory->status = $request->status;
            $subCategory->updated_by =  Auth::id();

            $subCategoryData = $subCategory->save();

            /**begin: system log**/
            $subCategory->bootSystemActivities();
            /**end: system log**/
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('success' => false));
        }
        return redirect('/admin/sub-categories');
    }

    function delete(Request $request)
    {
        $subCategoryID = $request->id;
        $subCategory = SubCategory::find($subCategoryID);

        $rfqCount = RfqProduct::where('sub_category_id',$subCategoryID)->where('is_deleted',0)->count();
        $categoryCount = DB::table('supplier_products')
            ->leftJoin('products', 'supplier_products.product_id', '=', 'products.id')
            ->where('products.subcategory_id', $subCategoryID)
            ->where('supplier_products.is_deleted', 0)
            ->count();

        if ($categoryCount==0 && $rfqCount==0) {
            $subCategory->is_deleted = 1;
            $subCategory->deleted_by =  Auth::id();
            $subCategory->save();
            return response()->json(['success' => true, 'message' => __('admin.sub_categories_delete_alert_ok_text')]);
        }

        /**begin: system log**/
        $subCategory->bootSystemActivities();
        /**end: system log**/
        return response()->json(['success' => false, 'message' => __('admin.cannot_delete_this_sub_category')]);
    }
}
