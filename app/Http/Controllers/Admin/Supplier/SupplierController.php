<?php

namespace App\Http\Controllers\Admin\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\Quote;
use App\Models\SubCategory;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\SuppliersBank;
use App\Models\User;
use App\Models\UserSupplier;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        // supplier list
        $this->middleware('permission:create supplier list|edit supplier list|delete supplier list|publish supplier list|unpublish supplier list', ['only' => ['list']]);
        $this->middleware('permission:create supplier list', ['only' => ['viewSupplierAdd', 'create']]);
        $this->middleware('permission:edit supplier list', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete supplier list', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $query = SupplierProduct::with('product.subcategory.category')->where('is_deleted',0)->get();
        $categories = $query->pluck('product.subcategory.category')->unique();
        $sub_category = $query->pluck('product.subcategory')->unique();
        $products = $query->pluck('product');

        return view('admin.supplier.supplier', ['categories' => $categories,'sub_category' => $sub_category, 'products' => $products]);
    }

    /**
     * Supplier list json
     *
     * @param Request $request
     * @return mixed
     */
    public function listJson(Request $request)
    {
        $profile_slug = getSettingValueByKey('slug_prefix');

        if($request->ajax() && $request->get('draw')) {

            $start = $request->get("start");
            $length = $request->get("length");
            $search = (!empty($request->get('search')) ? $request->get('search')['value'] : '');
            $displayoption = !empty($request->get('displayoption')) ? "export" : '';
            $column_order    = $request->get('column_order');
            $order_type     = $request->get('order_type');

            $query = Supplier::with(['supplierBank.bankDetail','trackUpdateData','trackAddData'])
                ->with(['products'=>function($q){
                    $q->where(['products.status'=>1,'products.is_deleted'=>0])->select('products.id','products.subcategory_id')->with('subCategory.category:id,name');
                }])
                ->with(['SupplierDealWithCategories'=>function($q){
                    $q->select('supplier_id','category_id','sub_category_id')->with('category:id,name')->with('subCategory:id,name')->whereNotNull('sub_category_id');
                }])
                ->where('is_deleted',0)
                ->select('id','name','email','mobile','c_phone_code','contact_person_email','added_by','updated_by','created_at','status','profile_username','contact_person_name','contact_person_last_name','contact_person_phone','cp_phone_code','xen_platform_id');

            if (Auth::user()->hasRole('agent')) {
                $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();
                $suppliers = DB::table('suppliers')
                    ->leftJoin('supplier_products', 'suppliers.id', '=','supplier_products.supplier_id')
                    ->leftJoin('products', 'supplier_products.product_id', '=', 'products.id')
                    ->leftJoin('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
                    ->leftJoin('categories', 'sub_categories.category_id', '=', 'categories.id')
                    ->whereIn('categories.id', $assignedCategory)
                    ->where('suppliers.is_deleted', 0)->selectRaw('suppliers.*')->distinct()->get();
            }

            $query = $this->filter($request->category_ids,$request->subcategory_ids,$request->product_ids,$request->status_ids,$request->start_date,$request->end_date,$query);


            // Ordering
            if ($column_order == 'added_at') {
                $query->orderBy('created_at', $order_type);
            }else{
                $query->orderBy($column_order, $order_type);
            }

            $setTotalRecords = $query->count();

            if ($search != "") {
                $query = $query->search($search);
            }
            // Sorting
            $setFilteredRecords = $query->count();
            $suppliers = ($length == -1) ? $query->get() : $query->skip($start)->take($length)->get();

            foreach ($suppliers as $supplier) {
                $hoveList = '';
                $categoryDisplay = '-';
                $supplierDelingCount = count($supplier->SupplierDealWithCategories->pluck('category_id')->toArray());
                $supplierProductCount = count($supplier->products);

                if($supplierDelingCount == 0 && $supplierProductCount == 0){
                    $categoryDisplay = '-';
                }
                else if($displayoption != "export"){

                    $supplierDeling = $supplier->SupplierDealWithCategories;
                    $supplierProduct = $supplier->products->pluck('subCategory');
                    $totalCategories = array_unique(array_merge($supplierDeling->pluck('sub_category_id')->toArray(),$supplierProduct->pluck('id')->toArray()));

                    //subcategory name get
                    $totalSubCategories = array_unique(array_merge($supplierDeling->pluck('sub_category_id')->toArray(),$supplierProduct->pluck('id')->toArray()));
                    $subcatcount = count($totalSubCategories);
                    $categoryDisplay = (count($totalCategories) > 1) ? $subcatcount.' Categories' : $supplier->SupplierDealWithCategories->pluck('category.name')->first();

                    $dataDisplay = [];
                    $supplierDealingCollection = collect();
                    $supplierProductCollection = collect();
                    foreach ($supplierDeling as $sd){
                        $cat = isset($sd->category->name)?$sd->category->name:'-';
                        $sub = isset($sd->subCategory->name)?$sd->subCategory->name:'-';
                        $supplierDealingCollection->push([
                            'category_id' => $sd->category_id,
                            'sub_category_id' => $sd->sub_category_id,
                            'display_name' => $cat.' - '.$sub,
                        ]);
                    }

                    foreach ($supplierProduct as $sp){
                        $catt = isset($sp->category->name)?$sp->category->name:'-';
                        $subb = isset($sp->name)?$sp->name:'-';
                        $supplierProductCollection->push([
                            'category_id' => $sp->category_id,
                            'sub_category_id' => $sp->id,
                            'display_name' => $catt.' - '.$subb,
                        ]);
                    }
                    $mergeCollection =  $supplierDealingCollection->merge($supplierProductCollection);
                    $SubCategoriesall = $mergeCollection->unique('sub_category_id');
                    $sorted = $SubCategoriesall->sortBy('display_name')->toArray();
                    foreach ($sorted as $s){
                        array_push($dataDisplay, $s['display_name']);
                    }
                    $hoveList  =  '<ol>';
                    $hoveList .=  '<li>' . implode( '</li><li>', $dataDisplay) . '</li>';
                    $hoveList .=  '</ol>';
                }

                $supplier->addedBy = !empty($supplier->trackAddData) ? $supplier->trackAddData->full_name : '-';
                $supplier->updatedBy = !empty($supplier->trackUpdateData) ? $supplier->trackUpdateData->full_name : '-';
                $supplier->bank_name = !empty($supplier->supplierBank->bankDetail) ? $supplier->supplierBank->bankDetail->name : '-';
                $supplier->bank_code = !empty($supplier->supplierBank->bankDetail) ? $supplier->supplierBank->bankDetail->code : '-';
                $supplier->bank_account_name = !empty($supplier->supplierBank) ? $supplier->supplierBank->bank_account_name : '-';
                $supplier->bank_account_number = !empty($supplier->supplierBank) ? $supplier->supplierBank->bank_account_number : '-';
                $supplier->added_at = date('d-m-Y H:i:s', strtotime($supplier->created_at));
                $supplier->categories = $categoryDisplay;
                $supplier->hoverCategory = $hoveList;
            }

            $data = $suppliers;

            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) use ($profile_slug) {
                    $buttons = '';
                    $editIdUrl = route('admin.supplier.edit', ['id' => Crypt::encrypt($row->id)]);
                    $supplierProfileUrl = route('supplier.professional.profile', ($profile_slug . ($row->profile_username)));

                    if (empty($row->xen_platform_id)) {
                        if($row->status == 1) {
                            $buttons .= '<a href="javascript:void(0)" class="show-icon ps-1 create-xenaccount" data-id="' . $row->id . '" id="create-xenaccount' . $row->id . '" data-name="' . addslashes($row->name) . '" data-email="' . $row->contact_person_email . '" data-bs-toggle="modal" data-bs-target="#xenditpopup" ><i class="fa fa-chain" area-placement="top" data-bs-toggle="tooltip" title="' . (__('admin.create_xen_account')) . '" ata-placement="top"></i></a>';
                        }
                    }
                    if (!empty($row->profile_username)) {
                        $buttons .= '<a href="' . $supplierProfileUrl . '" class="ps-1 show-icon" area-placement="top" data-bs-toggle="tooltip" data-bs-original-title="Preview" title="' . (__('admin.preview')) . '" target="_blank"><i class="fa fa-rocket" aria-hidden="true"></i></a>';
                    }
                    $buttons .= '<a href="' . $editIdUrl . '" class="ps-1 show-icon" area-placement="top" data-bs-original-title="' . (__('admin.edit')) . '" data-bs-toggle="tooltip" title="' . (__('admin.edit')) . '"><i class="fa fa-edit" aria-hidden="true"></i></a>';
                    $buttons .= '<a class="supplierModalView ps-1 cursor-pointer" data-id="' . $row->id . '"  data-bs-target="#supplierModal" area-placement="top" data-bs-original-title="' . (__('admin.view')) . '" data-bs-toggle="tooltip" title="' . (__('admin.view')) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                    $buttons .= '<a href="javascript:void(0)" id="deleteSupplier_' . $row->id . '" class="deleteSupplier show-icon ps-1" data-name="' . $row->name . '" data-bs-toggle="tooltip" data-bs-placement="top" ata-placement="top" data-bs-original-title="Delete" title="' . (__('admin.delete')) . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    return $buttons;
                })
                ->setOffset($start)
                ->setTotalRecords($setTotalRecords)
                ->setFilteredRecords($setFilteredRecords)
                ->rawColumns(['action'])
                ->make(true);
        }

    }

    public function filter($categoriesIdsArr,$subCategoriesIdsArr,$productsIdsArr,$statusArr,$start_date,$end_date,$query)
    {
        if (!empty($categoriesIdsArr)) {
            $query->whereHas('products', function($query) use($categoriesIdsArr){
                $query->whereHas('subCategory', function($query) use($categoriesIdsArr){
                    $query->whereIn('category_id', $categoriesIdsArr);
                });
            });
        }
        if (!empty($subCategoriesIdsArr)) {
            $query->whereHas('products', function($query) use($subCategoriesIdsArr){
                $query->whereHas('subCategory', function($query) use($subCategoriesIdsArr){
                    $query->whereIn('id', $subCategoriesIdsArr);
                });
            });
        }
        if (!empty($productsIdsArr)) {
            $query->whereHas('products', function($query) use($productsIdsArr){
                $query->whereIn('products.id', $productsIdsArr);
            });
        }
        if (!empty($statusArr)) {
            $query->whereIn('status', $statusArr);
        }
        if (!empty($start_date) && !empty($end_date)) {
            $start_date = Carbon::createFromFormat('d-m-Y', $start_date)->format('Y-m-d 00:00:00');
            $end_date = Carbon::createFromFormat('d-m-Y', $end_date)->format('Y-m-d 23:59:59');
            $query->whereBetween('created_at', [$start_date,$end_date]);
        }
        return $query;
    }

    public function getSupplierSubCategoryByCategory(Request $request)
    {
        $qrySubCategory = SubCategory::where('is_deleted',0);
        if(!empty($request->categoriesArr)){
            $qrySubCategory->whereIn('category_id',$request->categoriesArr);
        }
        $sub_categories = $qrySubCategory->groupBy(["category_id","name"])->get(['id','name as subCatName']);

        $qryProducts = Product::with('subcategory:id,name')->where('is_deleted',0)->whereIn('subcategory_id',$sub_categories->pluck('id')->toArray());
        if(!empty($request->subCategoriesArr)){
            $qryProducts->whereIn('subcategory_id',$request->subCategoriesArr);
        }
        $suppliers_products = $qryProducts->get();

        return response()->json(array('success' => true, 'subCategories' => $sub_categories,'suppliersProducts' => $suppliers_products));
    }

    public function getProductBySubcategory(Request $request) {
        $qryProducts = Product::with('subcategory:id,name')->where('is_deleted',0);
        if(!empty($request->subCategoriesArr)){
            $qryProducts->whereIn('subcategory_id',$request->subCategoriesArr);
        }
        $qryProducts = $qryProducts->get();
        return response()->json(array('success' => true, 'subCategoriesProducts' => $qryProducts));
    }
    public function delete(Request $request)
    {
        if(Quote::where('supplier_id',$request->id)->count() > 0){
            return response()->json(array('success' => false, 'supplierId' => NULL));
        }else{
            $supplier = Supplier::find($request->id);
            $supplier->is_deleted = 1;
            $supplier->save();

            $supplier_products = SupplierProduct::where('supplier_id', $request->id)->get();
            foreach ($supplier_products as $product) {
                $supplierProduct = SupplierProduct::find($product->id);
                $supplierProduct->is_deleted = 1;
                $supplierProduct->deleted_by = Auth::id();
                $supplierProduct->save();
            }

            /**begin: system log**/
            $supplier->bootSystemActivities();
            /**end: system log**/
            return response()->json(array('success' => true, 'supplierId' => $supplier->id));
        }
    }

    public function changeStatus(Request $request)
    {
        $supplierId = $request->id;
        $supplierBankDetails = SuppliersBank::where('supplier_id', $supplierId)->get();
        $supplier_quote = Quote::where('supplier_id', $supplierId)->get();
        if($supplierBankDetails->count() == 0){
            return response()->json(array('success' => false, 'message' => __('admin.supplier_has_no_primary_account')));
        }
        if($supplier_quote->count() > 0 && $request->status == 0){
            return response()->json(array('success' => false,  'message' => __('admin.supplier_has_quotes')));
        }else{
            $supplier = Supplier::find($supplierId);
            $supplier->status = $request->status;
            $supplier->save();
            $user_id = UserSupplier::where('supplier_id', $supplierId)->first();
            if (isset($user_id) && !empty($user_id)){
                $user = User::find($user_id->user_id);
                $user->is_active = $request->status;
                $user->save();
            }
            return response()->json(array('success' => true));
        }
    }
}
