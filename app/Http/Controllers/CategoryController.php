<?php

namespace App\Http\Controllers;

use App\Models\ModelHasCustomPermission;
use App\Models\Rfq;
use App\Models\RfqProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Unit;
use App\Models\categoryUnits;
use App\Models\Groups;
use App\Models\Product;
use App\Models\SystemActivity;
use App\Models\User;
use Auth;

class CategoryController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('permission:create category|edit category|delete category|publish category|unpublish category', ['only' => ['list']]);
        $this->middleware('permission:create category', ['only' => ['create', 'categoryAdd']]);
        $this->middleware('permission:edit category', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete category', ['only' => ['delete']]);
    }

    function list()
    {
        $categories = Category::all()->where('is_deleted', 0)->sortDesc();

        //Agent category permission
        if (Auth::user()->hasRole('agent')) {

            $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();

            $categories = Category::all()->whereIn('id', $assignedCategory)->where('is_deleted', 0)->sortDesc();

        }

        /**begin: system log**/
        Category::bootSystemView(new Category());
        /**end:  system log**/

        return view('admin/categoriesList', ['categories' => $categories]);
    }

    function categoryAdd()
    {
        $categories = Category::all()->where('is_deleted', 0);
        $units = Unit::all()->where('is_deleted', 0);

        return view('admin/categoryAdd', ['units' => $units]);
    }

    function create(Request $request)
    {
        if(isset($request->name)) {
            $duplicateData = checkDuplication('categories',trim($request->name));
        }
        if($duplicateData == true) {
            $category = new Category;
            $category->name = $request->name;
            $category->description = $request->description;
            $category->status = $request->status;
            $category->added_by =  Auth::id();
            $categoryData = $category->save();
            if ($categoryData) {
                if (count($request['units']) > 0) {
                    foreach ($request['units'] as $unit) {
                        $categoryUnits = new categoryUnits;
                        $categoryUnits->category_id = $category->id;
                        $categoryUnits->unit_id = $unit;
                        $categoryUnits->save();
                    }
                }
            }

            /**begin: system log**/
            $category->bootSystemActivities();
            /**end: system log**/

            return response()->json(array('success' => true));
        } else {
            return response()->json(array('success' => false));
        }
        return redirect('/admin/categories');

    }

    function edit($id)
    {
        $id = Crypt::decrypt($id);
        $category = Category::find($id);
        $units = Unit::all()->where('is_deleted', 0);
        if ($category) {
            $categoryUnits = DB::table('categories_units')
                ->where('category_id', $id)
                ->get('unit_id');
            $catUnit = array();
            foreach ($categoryUnits as $value)
                $catUnit[] = $value->unit_id;

            /**begin: system log**/
            $category->bootSystemView(new Category(), 'Category', SystemActivity::EDITVIEW, $category->id);
            /**end: system log**/

            return view('/admin/categoryEdit', ['category' => $category, 'categoryUnits' => $catUnit, 'units' => $units]);
        } else {
            return redirect('/admin/categories');
        }
    }

    function update(Request $request)
    {
        if(isset($request->name)) {
            $duplicateData = checkDuplication('categories',trim($request->name));
        }
        //check if category exist
        $categoryExist = Category::where('name', trim($request->name))->whereNotIn('id', [$request->id])->count();
        $duplicateData = $categoryExist>0 ? false : true;

        if($duplicateData == true) {
            $category = Category::find($request->id);
            $category->name = $request->name;
            $category->description = $request->description;
            $category->status = $request->status;
            $category->updated_by =  Auth::id();
            $categoryData = $category->save();
            if ($categoryData) {
                $res = categoryUnits::where('category_id', $category->id)->delete();
                if (count($request['units']) > 0) {
                    foreach ($request['units'] as $unit) {
                        $categoryUnits = new categoryUnits;
                        $categoryUnits->category_id = $category->id;
                        $categoryUnits->unit_id = $unit;
                        $categoryUnits->save();
                    }
                }
            }

            /**begin: system log**/
            $category->bootSystemActivities();
            /**end: system log**/

            return response()->json(array('success' => true));
        } else {
            return response()->json(array('success' => false));
        }
        return redirect('/admin/categories');
    }

    function delete(Request $request)
    {
        $categoryID = $request->id;
        $category = Category::find($request->id);
        $rfqCount = RfqProduct::where('category_id',$categoryID)->where('is_deleted',0)->count();
        $categoryCount = DB::table('supplier_products')
            ->leftJoin('products', 'supplier_products.product_id', '=', 'products.id')
            ->leftJoin('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->where('sub_categories.category_id', $categoryID)
            ->where('supplier_products.is_deleted', 0)
            ->count();

        if ($categoryCount==0 && $rfqCount==0) {
            $category->is_deleted = 1;
            $category->deleted_by =  Auth::id();
            $category->save();
            return response()->json(['success' => true, 'message' => __('admin.sub_categories_delete_alert_ok_text')]);
        }

        /**begin: system log**/
        $category->bootSystemActivities();
        /**end: system log**/
        return response()->json(['success' => false, 'message' => __('admin.cannot_delete_this_category')]);
        //return $request->id;
    }

    function searchCategory($category)
    {

        $filterData = DB::table('categories')
            ->join('sub_categories', 'categories.id', '=', 'sub_categories.category_id')
            ->where('categories.name', 'LIKE', '%' . $category . '%')
            ->orWhere('sub_categories.name', 'LIKE', '%' . $category . '%')
            ->where('categories.is_deleted', 0)
            ->select(DB::raw("CONCAT(categories.name,' - ' ,sub_categories.name) AS name,categories.name as category_name,sub_categories.name as sub_categories_name,sub_categories.id as sub_categories_id,categories.id as categories_id"))
            ->get();
        return response()->json(array('success' => true, 'filterData' => $filterData));
    }

    function searchProduct(Request $request)
    {
        $product = $request->product;

        $categoryId = $request->categoryId;
        $sub_category_id = $request->subCategoryId;
        if ($categoryId) {
            $filterData = DB::table('categories')
                ->join('sub_categories', 'categories.id', '=', 'sub_categories.category_id')
                ->join('products', 'sub_categories.id', '=', 'products.subcategory_id')
                ->where('products.name', 'LIKE', '%' . $product . '%')
                ->where('categories.id', '=', $categoryId)
                ->where('sub_categories.id', '=', $sub_category_id)
                ->where('categories.is_deleted', 0)
                ->where('sub_categories.is_deleted', 0)
                ->where('products.is_deleted', 0)
                ->where('products.is_verify', 1)
                ->where('products.status', 1)
                ->get(['products.*']);
        } else {
            $filterData = DB::table('products')
                ->where('name', 'LIKE', '%' . $product . '%')
                ->where('is_deleted', 0)
                ->where('is_verify', 1)
                ->where('status', 1)
                ->get(['products.*']);
        }
        // $subCategoryId = $request->subCategoryId;
        // if ($subCategoryId) {
        //     $filterData = DB::table('products')
        //         ->where('name', 'LIKE', '%' . $product . '%')
        //         ->where('subcategory_id', '=', $subCategoryId)
        //         ->where('is_deleted', 0)
        //         ->get();
        // } else {
        //     $filterData = DB::table('products')
        //         ->where('name', 'LIKE', '%' . $product . '%')
        //         ->where('is_deleted', 0)
        //         ->get();
        // }
        return response()->json(array('success' => true, 'filterData' => $filterData));
    }

    function searchProductDescription(Request $request)
    {
        $productDescription = $request->productDescription;
        $product = $request->product;
        if ($product) {
            $filterData = DB::table('products')
                ->where('description', 'LIKE', '%' . $productDescription . '%')
                // ->where('name', 'LIKE', '%' . $product . '%')
                ->where('name', $product)
                ->where('is_deleted', 0)
                ->where('is_verify', 1)
                ->get();
        } else {
            $filterData = DB::table('products')
                ->where('description', 'LIKE', '%' . $productDescription . '%')
                ->where('is_deleted', 0)
                ->where('is_verify', 1)
                ->get();
        }
        return response()->json(array('success' => true, 'filterData' => $filterData));
    }

    function getSubCategory($categoryId)
    {
        $subCategory = DB::table('sub_categories')
            ->where('category_id', $categoryId)
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->orderBy('name', 'desc')
            ->get();

        if ($categoryId) {
            $unit = DB::table('categories_units')
                ->join('units', 'categories_units.unit_id', '=', 'units.id')
                ->where('categories_units.category_id', $categoryId)
                ->where('units.is_deleted', 0)
                ->orderBy('units.name', 'desc')
                ->select('units.id', 'units.name')
                ->get();
        } else {
            $unit = DB::table('units')
                ->where('units.is_deleted', 0)
                ->orderBy('units.name', 'desc')
                ->select('units.id', 'units.name')
                ->get();
        }
        return response()->json(array('success' => true, 'subCategory' => $subCategory, 'unit' => $unit));
    }

    function getBrandAndGrade(Request $request)
    {
        //print_r($request->productName);
        if ($request->productName && $request->subCategoryId) {
            $products = DB::table('products')
                ->where('name', $request->productName)
                ->where('subcategory_id', $request->subCategoryId)
                ->get();

            if (count($products) && $request->subCategoryId) {
                $productId = $products[0]->id;
                $brands = DB::table('product_brands')
                    ->join('brands', 'brands.id', '=', 'product_brands.brand_id')
                    ->where('product_brands.product_id', $productId)
                    ->where('brands.is_deleted', 0)
                    ->orderBy('brands.name', 'desc')
                    ->get(['brands.*']);
            } else {
                $brands = '';
            }

            $grades = DB::table('grades')
                ->where('subcategory_id', $request->subCategoryId)
                ->where('is_deleted', 0)
                ->orderBy('name', 'desc')
                ->get();
        } else {
            $brands = '';
            $grades = '';
        }

        // $products = DB::table('products')
        //     ->where('subcategory_id', $subCategoryId)
        //     ->where('is_deleted', 0)
        //     ->orderBy('name', 'desc')
        //     ->get();
        return response()->json(array('success' => true, 'brands' => $brands, 'grades' => $grades));
    }

    function getUnit(Request $request)
    {
        // if ($request->categoryId) {
        //     $unit = DB::table('categories_units')
        //         ->join('units', 'categories_units.unit_id', '=', 'units.id')
        //         ->where('categories_units.category_id', $request->categoryId)
        //         ->where('units.is_deleted', 0)
        //         ->orderBy('units.name', 'desc')
        //         ->select('units.id', 'units.name')
        //         ->get();
        // } else {
        //     $unit = DB::table('units')
        //         ->where('units.is_deleted', 0)
        //         ->orderBy('units.name', 'desc')
        //         ->select('units.id', 'units.name')
        //         ->get();
        // }
        $unit = DB::table('units')
            ->where('units.is_deleted', 0)
            ->orderBy('units.name', 'desc')
            ->select('units.id', 'units.name')
            ->get();
        return response()->json(array('success' => true, 'unit' => $unit));
    }

    function getCategoryWithSubcat()
    {
        // $categoryWithSubCat = DB::table('categories')
        //     ->join('sub_categories', 'sub_categories.category_id', '=', 'categories.id')
        //     ->where('categories.is_deleted', 0)
        //     ->where('sub_categories.is_deleted', 0)
        //     ->select(DB::raw("CONCAT(categories.name,' - ' ,sub_categories.name) AS name,categories.name as category_name,sub_categories.name as sub_categories_name,sub_categories.id as sub_categories_id,categories.id as categories_id"))
        //     ->orderBy('name', 'ASC')
        //     ->get();
        $categoryWithSubCat = DB::table('categories')
            //->join('sub_categories', 'sub_categories.category_id', '=', 'categories.id')
            ->where('categories.is_deleted', 0)
            //->where('sub_categories.is_deleted', 0)
            //->select(DB::raw("CONCAT(categories.name,' - ' ,sub_categories.name) AS name,categories.name as category_name,sub_categories.name as sub_categories_name,sub_categories.id as sub_categories_id,categories.id as categories_id"))
            ->orderBy('name', 'ASC')
            ->get();
        return response()->json(array('success' => true, 'categoryWithSubCat' => $categoryWithSubCat));
    }

    //Get subcategorirs by category array (Ronak - 08/04/2022)
    public function getAllSubCategoriesByCatId(Request $request) {
        //dd(array_filter($request->categoriesArr));
        $group_subCategories = Groups::select('subCategory_id')->groupBy('subCategory_id')->orderBy('subCategory_id','ASC')->get()->pluck('subCategory_id')->toArray();
        if($request->ajax()) {
            if(empty($request->categoriesArr)) {
                $subCategories = SubCategory::where('is_deleted',0)->groupBy('name')->get(['id','name as subCatName']);
            } else {
                $categories = $request->categoriesArr;
                $all_subCategories = SubCategory::select('id')->whereIn('category_id', $categories)->where('is_deleted',0)->groupBy('name')->get()->pluck('id')->toArray();
                $subCatResult = array_intersect($all_subCategories,$group_subCategories);
                // $subCategories = SubCategory::whereIn('category_id', $categories)->where('is_deleted', 0)->get(['id','name as subCatName']);
                $subCategories = SubCategory::select('id','name as subCatName')->whereIn('id',$subCatResult)->where('is_deleted',0)->groupBy('name')->get();
            }
            return response()->json(array('success' => true, 'subCategories' => $subCategories));
        }
    }

    //Get products by category and sub category ids (Ronak - 08/04-2022)
    public function getProductByCatSubcatId(Request $request) {
        $group_products = Groups::select('product_id')->groupBy('product_id')->orderBy('product_id','ASC')->get()->pluck('product_id')->toArray();
        if($request->ajax()) {
            if(empty($request->categoriesArr) || empty($request->subCategoriesArr)) {
                $products = Product::where('is_deleted',0)->groupBy('name')->get(['id','name as productName']);
            } else {
                $categories = $request->categoriesArr;
                $subCategories = $request->subCategoriesArr;
                $all_products = Product::select('id')->whereIn('subcategory_id', $subCategories)->where('is_deleted',0)->groupBy('name')->get()->pluck('id')->toArray();
                $productResult = array_intersect($all_products,$group_products);
                $products = Product::leftJoin('sub_categories','products.subcategory_id','=','sub_categories.id')
                    ->leftJoin('categories','sub_categories.category_id','=','categories.id')
                    ->whereIn('products.id', $productResult)->where('products.is_deleted',0)->groupBy('products.name')->get(['products.id','products.name as productName']);
            }
        }
        //dd($products->toArray());
        return response()->json(array('success' => true, 'products' => $products));
    }
}
