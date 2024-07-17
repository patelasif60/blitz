<?php

namespace App\Http\Controllers;

use App\Export\SupplierExport;
use App\Http\Requests\Admin\Supplier\AdminCompanyDetailsRequest;
use App\Jobs\ChatAddNewSupplierAsUser;
use App\Jobs\SupplierNotifyCategoryWiseRfqlistJob;
use App\Models\AvailableBank;
use App\Models\CompanyDetails;
use App\Models\CompanyHighlights;
use App\Models\CompanyMembers;
use App\Models\QuoteItem;
use App\Models\Rfq;
use App\Models\Settings;
use Faker\Extension\Helper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Payment\XenditController;
use App\Jobs\InviteBuyerActivationJob;
use App\Jobs\SendActivationMailToSupplierEmailJob;
use App\Mail\SendActivationMailToUser;
use App\Models\Company;
use App\Models\CountryOne;
use App\Models\Department;
use Carbon\Carbon;
use App\Models\State;
use App\Models\SupplierProductDiscountRange;
use App\Models\SuppliersBank;
use App\Models\SupplierTransactionCharge;
use App\Models\User;
use App\Models\UserActivity;
use App\Models\UserAddresse;
use App\Models\UserCompanies;
use App\Models\UserSupplier;
use App\Models\XenSubAccount;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PDF;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\OtherCharge;
use App\Models\SupplierCharge;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\City;
use App\Models\Grade;
use App\Models\Unit;
use App\Models\Product;
use App\Models\SupplierProduct;
use App\Models\SupplierProductImage;
use App\Models\SupplierProductBrand;
use App\Models\InviteBuyer;
use App\Models\Quote;
use App\Models\Role;
use App\Models\SupplierAddress;
use App\Models\SupplierProductGrade;
use App\Models\SystemActivity;
use App\Models\CompanyAddress;
use App\Models\SupplierGallery;
use App\Models\SupplierDealWithCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use URL;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Http\Requests\Supplier\SupplierProfessionalProfileRequest;
use DataTables;
use App\Http\Requests\Supplier\CompanyDetailsRequest;
use App\Models\RfqProduct;
use Illuminate\Support\Facades\Response;

class AdminSupplierController extends Controller
{
    public function __construct()
    {
        // supplier transaction charge
        $this->middleware('permission:create supplier transaction charges|edit supplier transaction charges|delete supplier transaction charges|publish supplier transaction charges|unpublish supplier transaction charges', ['only' => ['supplierTransactionList']]);

        // supplier list
        $this->middleware('permission:create supplier list|edit supplier list|delete supplier list|publish supplier list|unpublish supplier list', ['only' => ['list']]);
        $this->middleware('permission:create supplier list', ['only' => ['viewSupplierAdd', 'create']]);
        $this->middleware('permission:edit supplier list', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete supplier list', ['only' => ['delete']]);

        // invite supplier
        $this->middleware('permission:create invite supplier|edit invite invite supplier|publish invite supplier|unpublish invite supplier', ['only' => ['inviteSupplierList']]);
        $this->middleware('permission:create invite supplier', ['only' => ['inviteSupplierCreate', 'inviteSupplierAdd']]);
        $this->middleware('permission:edit invite supplier', ['only' => ['inviteSupplierEdit', 'inviteSupplierUpdate']]);
        $this->middleware('permission:delete invite supplier', ['only' => ['inviteSupplierDelete']]);

        // invite buyer
        $this->middleware('permission:create invite buyer|edit invite buyer|delete invite buyer|publish invite buyer|unpublish invite buyer', ['only' => ['inviteBuyerList']]);
        $this->middleware('permission:create invite buyer', ['only' => ['inviteBuyerCreate', 'inviteBuyerAdd']]);
        $this->middleware('permission:edit invite buyer', ['only' => ['inviteBuyerEdit', 'inviteBuyerUpdate']]);
        $this->middleware('permission:delete invite buyer', ['only' => ['inviteBuyerDelete']]);

        // supplier address
        $this->middleware('permission:create supplier address|edit supplier address|delete supplier address|publish supplier address|unpublish supplier address', ['only' => ['supplierAddressList']]);
        $this->middleware('permission:create supplier address', ['only' => ['addSupplierAddress', 'createSupplierAddress']]);
        $this->middleware('permission:edit supplier address', ['only' => ['editSupplierAddress', 'updateSupplierAddress']]);
        $this->middleware('permission:delete supplier address', ['only' => ['deleteSupplierAddress']]);
    }

    /**
     * Show Filtered data
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|string
     */
    function list(Request $request)
    {
        $condition = [];
        if($request->ajax()){
            if($request->category_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'categories.id', 'value' => $request->category_ids]);
            }

            if($request->subcategory_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'sub_categories.id', 'value' => $request->subcategory_ids]);
            }

            if($request->product_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'products.id', 'value' => $request->product_ids]);
            }
            if($request->status_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'suppliers.status', 'value' => $request->status_ids]);
            }

            if($request->start_date && $request->end_date){
                $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d 00:00:00');
                $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d 23:59:59');
                array_push($condition, ['condition' => 'wherebetween', 'column_name' => 'suppliers.created_at', 'value' => [$start_date,  $end_date] ]);
            }

        }

        $select_columns = [];
        $select_columns = ['suppliers.*','suppliers.id as supplier_id','products.id as product_id','products.name as product_name','products.subcategory_id as product_subcategory','sub_categories.id as subcategory_id','sub_categories.name as subcategory_name','sub_categories.category_id as scategory_id','categories.id as c_category_id','categories.name as category_name'];

        $suppliers = Supplier::leftJoin('supplier_products', function ($join) {
                    $join->on('supplier_products.supplier_id', '=', 'suppliers.id');
                })->leftJoin('products', function ($join) {
                    $join->on('supplier_products.product_id', '=', 'products.id');
                })->leftJoin('sub_categories', function ($join) {
                    $join->on('products.subcategory_id', '=', 'sub_categories.id');
                })->leftJoin('categories', 'sub_categories.category_id', '=', 'categories.id')
                ->where('suppliers.is_deleted' , 0 );

        if(sizeof($condition) > 0){
            foreach($condition as $key => $value){
                if(strtoupper($value['condition']) == "WHEREIN"){
                    $suppliers = $suppliers->whereIn($value['column_name'], $value['value']);
                }
                if(strtoupper($value['condition']) == "WHEREBETWEEN"){
                    $suppliers = $suppliers->whereBetween($value['column_name'], $value['value']);
                }
            }
        }
        $category_obj = clone $suppliers;
        $categories = $category_obj->groupBy('categories.id')->whereNotNull('categories.id')->get(['categories.id as id','categories.name as category_name']);

        $subcategory_obj = clone $suppliers;
        $sub_category = $subcategory_obj->groupBy('sub_categories.id')->whereNotNull('sub_categories.id')->get(['sub_categories.id as id','sub_categories.name as subcategory_name']);

        $product_obj = clone $suppliers;
        $products = $product_obj->orderBy('products.id','asc')->groupBy('products.id')->whereNotNull('products.id')->get(['products.id as id','products.name as product_name','sub_categories.name as subCatsName']);
        $suppliers = $suppliers->orderBy('suppliers.id','desc')->groupBy('suppliers.id')->get($select_columns);

        //Agent category permission
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

        foreach ($suppliers as $supplier) {
            //dd($supplier->interested_in);
            $pieces = explode(",", $supplier->interested_in);
            if (count($pieces) > 1) {
                $supplier->category_name_new = $pieces[0];
            } else {
                $supplier->category_name_new = '-';
            }
            if(isset($pieces[1])){
                $supplier->category_count_new = 2;
            }else{
                $supplier->category_count_new = 0;
            }
            $supplierProduct = DB::table('supplier_products')
                ->join('products', 'supplier_products.product_id', '=', 'products.id')
                ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
                ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
                ->where('supplier_products.supplier_id', $supplier->id)
                ->get(['categories.name', 'supplier_products.supplier_id']);

            if (count($supplierProduct)) {
                $supplier->category_name = $supplierProduct[0]->name;
            } else {
                $supplier->category_name = '-';
            }
            $uniqueCount = count(array_unique(array_column($supplierProduct->toArray(), 'name')));
            // $supplier->category_count = count($supplierProduct);
            $supplier->category_count = $uniqueCount;
        }

        /**begin: system log**/
          Supplier::bootSystemView(new Supplier());
        /**end:  system log**/

        $supplierDataHtml = view('admin/supplier/supplierTableData', ['suppliers' => $suppliers])->render();
        if($request->ajax()){
            return $supplierDataHtml;
        }
        return view('admin/supplier/supplierList', ['supplierDataHtml'=> $supplierDataHtml,'suppliers' => $suppliers,'categories' => $categories,'sub_category' => $sub_category,'products' => $products]);
    }
    /**
     * Get subcategorirs by category array for filter
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubcategoryByCategory(Request $request) {
        $suppliers = DB::table('suppliers')
                ->join('supplier_products', 'suppliers.id', '=','supplier_products.supplier_id')
                ->join('products', 'supplier_products.product_id', '=', 'products.id')
                ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
                ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
                ->where('suppliers.is_deleted', 0);

        $suppliers_subCategories_obj = clone $suppliers;
        $suppliers_subCategories = $suppliers_subCategories_obj->groupBy('sub_categories.id')->orderBy('sub_categories.id','ASC')->pluck('sub_categories.id')->toArray();

        $suppliers_products_obj = clone $suppliers;
        $suppliers_products = $suppliers_products_obj->orderBy('products.id','asc')->groupBy('products.id')->get(['products.id as subCatProductsid','products.name as subCatProductsName','sub_categories.name as subCatsName']);

        if($request->ajax()) {
            if(empty($request->categoriesArr)) {
                $subCategories = SubCategory::where('is_deleted',0)->groupBy('name')->get(['id','name as subCatName']);
            } else {
                $categories = $request->categoriesArr;
                $all_subCategories = SubCategory::select('id')->whereIn('category_id', $categories)->where('is_deleted',0)->groupBy('name')->get()->pluck('id')->toArray();
                $subCatResult = array_intersect($all_subCategories,$suppliers_subCategories);
                // $subCategories = SubCategory::whereIn('category_id', $categories)->where('is_deleted', 0)->get(['id','name as subCatName']);
                $subCategories = SubCategory::select('id','name as subCatName')->whereIn('id',$subCatResult)->where('is_deleted',0)->groupBy('name')->get();
            }

            return response()->json(array('success' => true, 'subCategories' => $subCategories,'suppliersProducts' => $suppliers_products));
        }
    }

    /**
     * Get subcategorirs by category array for filter
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductBySubcategory(Request $request) {
        $suppliers_subCategoriesProducts = DB::table('suppliers')
                ->join('supplier_products', 'suppliers.id', '=','supplier_products.supplier_id')
                ->join('products', 'supplier_products.product_id', '=', 'products.id')->where('suppliers.is_deleted', 0)->groupBy('products.id')->orderBy('products.id','ASC')->pluck('products.id')->toArray();

        if($request->ajax()) {
            if(empty($request->subCategoriesArr)) {
                //$subCategoriesProducts = Product::where('is_deleted',0)->groupBy('name')->get(['id','name as subCatProductsName']);
                $subCategoriesProducts =  DB::table('products')
                    ->join('sub_categories', 'products.subcategory_id', '=','sub_categories.id')
                   ->where('products.is_deleted',0)->get(['products.id','products.name as subCatProductsName','sub_categories.name as subCatsName']);
            } else {
                $subCategories = $request->subCategoriesArr;
                $all_subCategoriesProducts = Product::select('id')->whereIn('subcategory_id', $subCategories)->where('is_deleted',0)->groupBy('name')->get()->pluck('id')->toArray();
                $subCatProductsResult = array_intersect($all_subCategoriesProducts,$suppliers_subCategoriesProducts);
                $subCategoriesProducts = DB::table('products')
                ->join('sub_categories', 'products.subcategory_id', '=','sub_categories.id')
                    ->whereIn('products.id',$subCatProductsResult)->where('products.is_deleted',0)->get(['products.id','products.name as subCatProductsName','sub_categories.name as subCatsName']);
            }
            return response()->json(array('success' => true, 'subCategoriesProducts' => $subCategoriesProducts));
        }

    }

    function create(Request $request)
    {
        $logoFilePath = '';
        $catalogFilePath = '';
        $pricingFilePath = '';
        $productFilePath = '';
        $commercialConditionFilePath = '';
        $nibFilePath = '';
        $npwpFilePath = '';
        if ($request->file('logo')) {
            $logoFileName = Str::random(10) . '_' . time() . 'logo_' . $request->file('logo')->getClientOriginalName();
            $logoFilePath = $request->file('logo')->storeAs('uploads/supplier', $logoFileName, 'public');
        }

        if ($request->file('catalog')) {
            $catalogFileName = Str::random(10) . '_' . time() . 'catalog_' . $request->file('catalog')->getClientOriginalName();
            $catalogFilePath = $request->file('catalog')->storeAs('uploads/supplier', $catalogFileName, 'public');
        }

        if ($request->file('pricing')) {
            $pricingFileName = Str::random(10) . '_' . time() . 'pricing_' . $request->file('pricing')->getClientOriginalName();
            $pricingFilePath = $request->file('pricing')->storeAs('uploads/supplier', $pricingFileName, 'public');
        }

        if ($request->file('product')) {
            $productFileName = Str::random(10) . '_' . time() . 'product_' . $request->file('product')->getClientOriginalName();
            $productFilePath = $request->file('product')->storeAs('uploads/supplier', $productFileName, 'public');
        }

        if ($request->file('commercialCondition')) {
            $commercialConditionFileName = Str::random(5) . '_' . time() . 'termsconditions_file_' . $request->file('commercialCondition')->getClientOriginalName();
            $commercialConditionFilePath = $request->file('commercialCondition')->storeAs('uploads/supplier', $commercialConditionFileName, 'public');
        }

        if ($request->file('nib_file')) {
            $nibFileName = Str::random(10) . '_' . time() . 'nib_file_' . $request->file('nib_file')->getClientOriginalName();
            $nibFilePath = $request->file('nib_file')->storeAs('uploads/supplier', $nibFileName, 'public');
        }

        if ($request->file('npwp_file')) {
            $npwpFileName = Str::random(10) . '_' . time() . 'npwp_file_' . $request->file('npwp_file')->getClientOriginalName();
            $npwpFilePath = $request->file('npwp_file')->storeAs('uploads/supplier', $npwpFileName, 'public');
        }

        $supplier = new Supplier;
        $supplier->name = $request->name;
        $supplier->email = $request->email;
        $supplier->c_phone_code = $request->c_phone_code?'+'.$request->c_phone_code:'';
        $supplier->mobile = $request->mobile;
        $supplier->website = $request->website;
        $supplier->logo = $logoFilePath;
        $supplier->nib = $request->nib;
        $supplier->nib_file = $nibFilePath;
        $supplier->npwp = $request->npwp;
        $supplier->npwp_file = $npwpFilePath;
        $supplier->description = $request->description;
        $supplier->group_margin = $request->group_margin;
        $supplier->address = $request->address;
        $supplier->salutation = $request->salutation;
        $supplier->contact_person_name = $request->contactPersonName;
        $supplier->contact_person_last_name = $request->contactPersonLastName;
        $supplier->contact_person_email = trim($request->contactPersonEmail);
        $supplier->cp_phone_code = $request->cp_phone_code?'+'.$request->cp_phone_code:'';
        $supplier->contact_person_phone = $request->contactPersonMobile;
        $supplier->alternate_email = $request->alternate_email?trim($request->alternate_email):'';
        $supplier->catalog = $catalogFilePath;
        $supplier->pricing = $pricingFilePath;
        $supplier->product = $productFilePath;
        $supplier->commercialCondition = $commercialConditionFilePath;
        $supplier->added_by =  Auth::id();

        $supplier->save();

        /**begin: system log**/
        $supplier->bootSystemActivities();
        /**end: system log**/

        //return redirect('/admin/supplier');
        $request->session()->flash('status', 'Supllier Added Successfully');
        if (Auth::user()) {
            return redirect('/admin/supplier-add');
        } else {
            return redirect('/supplier-add');
        }
    }

    function createAjax(Request $request)
    {
        $logoFilePath = '';
        $catalogFilePath = '';
        $pricingFilePath = '';
        $productFilePath = '';
        $commercialConditionFilePath = '';
        $nibFilePath = '';
        $npwpFilePath = '';
        $pkpFilePath = '';
        if ($request->file('logo')) {
            $logoFileName = Str::random(10) . '_' . time() . 'logo_' . $request->file('logo')->getClientOriginalName();
            $logoFilePath = $request->file('logo')->storeAs('uploads/supplier', $logoFileName, 'public');
        }

        if ($request->file('catalog')) {
            $catalogFileName = Str::random(10) . '_' . time() . 'catalog_' . $request->file('catalog')->getClientOriginalName();
            $catalogFilePath = $request->file('catalog')->storeAs('uploads/supplier', $catalogFileName, 'public');
        }

        if ($request->file('pricing')) {
            $pricingFileName = Str::random(10) . '_' . time() . 'pricing_' . $request->file('pricing')->getClientOriginalName();
            $pricingFilePath = $request->file('pricing')->storeAs('uploads/supplier', $pricingFileName, 'public');
        }

        if ($request->file('product')) {
            $productFileName = Str::random(10) . '_' . time() . 'product_' . $request->file('product')->getClientOriginalName();
            $productFilePath = $request->file('product')->storeAs('uploads/supplier', $productFileName, 'public');
        }

        if ($request->file('commercialCondition')) {
            $commercialConditionFileName = Str::random(5) . '_' . time() . 'termsconditions_file_' . $request->file('commercialCondition')->getClientOriginalName();
            $commercialConditionFilePath = $request->file('commercialCondition')->storeAs('uploads/supplier', $commercialConditionFileName, 'public');
        }

        if ($request->file('nib_file')) {
            $nibFileName = Str::random(10) . '_' . time() . 'nib_file_' . $request->file('nib_file')->getClientOriginalName();
            $nibFilePath = $request->file('nib_file')->storeAs('uploads/supplier', $nibFileName, 'public');
        }

        if ($request->file('npwp_file')) {
            $npwpFileName = Str::random(10) . '_' . time() . 'npwp_file_' . $request->file('npwp_file')->getClientOriginalName();
            $npwpFilePath = $request->file('npwp_file')->storeAs('uploads/supplier', $npwpFileName, 'public');
        }

        //PKP document upload
        if ($request->companyType == 1  && $request->file('pkp_file')) {
            $pkpFileName = Str::random(10) . '_' . time() . 'pkp_file_' . $request->file('pkp_file')->getClientOriginalName();
            $pkpFilePath = $request->file('pkp_file')->storeAs('uploads/supplier', $pkpFileName, 'public');
        }

        $supplier = new Supplier;
        $supplier->name = $request->name;
        $supplier->email = $request->email;
        $supplier->c_phone_code = (!empty($request->mobile) && $request->c_phone_code)?'+'.$request->c_phone_code:'';
        $supplier->mobile = $request->mobile;
        $supplier->website = $request->website;
        $supplier->logo = $logoFilePath;
        $supplier->nib = $request->nib;
        $supplier->nib_file = $nibFilePath;
        $supplier->npwp = $request->npwp;
        $supplier->npwp_file = $npwpFilePath;
        $supplier->description = $request->description;
        $supplier->group_margin = $request->group_margin;
        $supplier->address = $request->address;

        $supplier->salutation = $request->salutation;
        $supplier->contact_person_name = $request->contactPersonName;
        $supplier->contact_person_last_name = $request->contactPersonLastName;

        $supplier->contact_person_email = trim($request->contactPersonEmail);
        $supplier->cp_phone_code = $request->cp_phone_code?'+'.$request->cp_phone_code:'';
        $supplier->contact_person_phone = $request->contactPersonMobile;
        $supplier->alternate_email = $request->alternate_email?trim($request->alternate_email):'';
        $supplier->catalog = $catalogFilePath;
        $supplier->pricing = $pricingFilePath;
        $supplier->product = $productFilePath;
        $supplier->commercialCondition = $commercialConditionFilePath;
        $supplier->pkp_file = $pkpFilePath;
        $supplier->company_type = $request->companyType;
        $supplier->added_by =  Auth::id();

        $supplier->interested_in = $request->interested_in??'';
        $supplier->save();



        //return redirect('/admin/supplier');
        // $request->session()->flash('status', 'Supllier Added Successfully');
        // if (Auth::user()) {
        //     return redirect('/admin/supplier-add');
        // } else {
        //     return redirect('/supplier-add');
        // }
        return response()->json(array('success' => true, 'supplierId' => $supplier->id));
    }

    function edit($ids = '')
    {
        if (!empty($ids)){
            $id = Crypt::decrypt($ids);
        } else {
            $id = UserSupplier::where('user_id', Auth::id())->first()->supplier_id;
        }
        $supplier = Supplier::find($id);

        if ($supplier) {

            //uploaded PKP file for in view page.
            if($supplier->company_type==1 && $supplier->pkp_file){
                $ViewData['pkpFileTitle'] = Str::substr($supplier->pkp_file, stripos($supplier->pkp_file, "pkp_file_") + 9);
                $ViewData['extension_pkp_file'] = getFileExtension($ViewData['pkpFileTitle']);
                $ViewData['pkp_file_filename'] = getFileName($ViewData['pkpFileTitle']);
                if(strlen($ViewData['pkp_file_filename']) > 10){
                    $ViewData['pkp_file_name'] = substr($ViewData['pkp_file_filename'],0,10).'...'.$ViewData['extension_pkp_file'];
                } else {
                    $ViewData['pkp_file_name'] = $ViewData['pkp_file_filename'].$ViewData['extension_pkp_file'];
                }
            }

            $banks = getAllRecordsByCondition('available_banks',['can_disburse'=>1],'id,name,code,logo');
            $supplierBanks = SuppliersBank::where('supplier_id',$id)->get();
            if (!empty($ids) && auth()->user()->role_id != 3){
                $category = Category::all()->where('is_deleted', 0)->where('status', 1);
                $subCategory = SubCategory::all()->where('is_deleted', 0)->where('status', 1);
                $brandData = Brand::select('name')->where('is_deleted', 0)->get()->toArray();
                $gradeData = Grade::select('name')->where('is_deleted', 0)->get()->toArray();
                $unit = Unit::all()->where('is_deleted', 0);
                $products = Product::all()->where('is_deleted', 0);
                $check_as_buyer = UserSupplier::where('supplier_id',$id)->get()->count();

                $supplierProduct = DB::table('supplier_products')
                    ->join('products', 'supplier_products.product_id', '=', 'products.id')
                    ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
                    ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
                    ->where('supplier_products.supplier_id', $id)
                    ->where('supplier_products.is_deleted', 0)
                    ->get(['products.name as product_name', 'products.description as product_description', 'categories.name as category_name', 'sub_categories.name as sub_category_name', 'supplier_products.id as supplier_products_id']);
                $brand = implode(',', array_unique(array_column($brandData, 'name')));
                $grade = implode(',', array_unique(array_column($gradeData, 'name')));

                /**begin: system log**/
                $supplier->bootSystemView(new Supplier(), 'Supplier', SystemActivity::EDITVIEW, $supplier->id);
                /**end: system log**/

                $ViewData['banks']=$banks;
                $ViewData['supplierBanks']=$supplierBanks;
                $ViewData['supplier']=$supplier;
                $ViewData['category']=$category;
                $ViewData['subCategory']=$subCategory;
                $ViewData['brand']=$brand;
                $ViewData['grade']=$grade;
                $ViewData['unit']=$unit;
                $ViewData['products']=$products;
                $ViewData['supplierProduct']=$supplierProduct;
                $ViewData['asBuyer']=$check_as_buyer;

                //return view('admin/supplier/supplierEdit', ['banks'=>$banks,'supplierBanks'=>$supplierBanks,'supplier' => $supplier, 'category' => $category, 'subCategory' => $subCategory, 'brand' => $brand, 'grade' => $grade, 'unit' => $unit, 'products' => $products, 'supplierProduct' => $supplierProduct, 'asBuyer'=> $check_as_buyer]);
                return view('admin/supplier/supplierEdit', $ViewData);
            } elseif(auth()->user()->role_id == 3) {
                $ViewData['banks']=$banks;
                $ViewData['supplierBanks']=$supplierBanks;
                $ViewData['supplier']=$supplier;
                $ViewData['companyDetails']=CompanyDetails::where('model_id',Auth::id())->first();
                $ViewData['supplierAddress'] = CompanyAddress::where('model_id',$id)->get()->toArray();

                $userId = Crypt::encrypt($supplier->user()->id);

                return view('admin/supplier/supplierProfessionalProfile', compact(['ViewData','userId']));
            }
        } else {
            return redirect('/admin/supplier');
        }
    }

    function update(AdminCompanyDetailsRequest $request)
    {
        $supplier = Supplier::find($request->id);
        $supplierDetails = Supplier::with(['user','user.company'])->where('id',$request->id)->first();

        if ($request->file('logo')) {
            Storage::delete('/public/' . $supplier->logo);
            $logoFileName = Str::random(10) . '_' . time() . 'logo_' . $request->file('logo')->getClientOriginalName();
            $logoFilePath = $request->file('logo')->storeAs('uploads/supplier', $logoFileName, 'public');
            $supplier->logo = $logoFilePath;
        }
        if ($request->file('catalog')) {
            Storage::delete('/public/' . $supplier->catalog);
            $catalogFileName = Str::random(10) . '_' . time() . 'catalog_' . $request->file('catalog')->getClientOriginalName();
            $catalogFilePath = $request->file('catalog')->storeAs('uploads/supplier', $catalogFileName, 'public');
            $supplier->catalog = $catalogFilePath;
        }
        if ($request->file('pricing')) {
            Storage::delete('/public/' . $supplier->pricing);
            $pricingFileName = Str::random(10) . '_' . time() . 'pricing_' . $request->file('pricing')->getClientOriginalName();
            $pricingFilePath = $request->file('pricing')->storeAs('uploads/supplier', $pricingFileName, 'public');
            $supplier->pricing = $pricingFilePath;
        }
        if ($request->file('product')) {
            Storage::delete('/public/' . $supplier->product);
            $productFileName = Str::random(10) . '_' . time() . 'product_' . $request->file('product')->getClientOriginalName();
            $productFilePath = $request->file('product')->storeAs('uploads/supplier', $productFileName, 'public');
            $supplier->product = $productFilePath;
        }
        if ($request->file('commercialCondition')) {
            Storage::delete('/public/' . $supplier->commercialCondition);
            $commercialConditionFileName = Str::random(5) . '_' . time() . 'termsconditions_file_' . $request->file('commercialCondition')->getClientOriginalName();
            $commercialConditionFilePath = $request->file('commercialCondition')->storeAs('uploads/supplier', $commercialConditionFileName, 'public');
            $supplier->commercialCondition = $commercialConditionFilePath;
        }
        if ($request->file('nib_file')) {
            Storage::delete('/public/' . $supplier->nib_file);
            $nibFileName = Str::random(10) . '_' . time() . 'nib_file_' . $request->file('nib_file')->getClientOriginalName();
            $nibFilePath = $request->file('nib_file')->storeAs('uploads/supplier', $nibFileName, 'public');
            $supplier->nib_file = $nibFilePath;
        }
        if ($request->file('npwp_file')) {
            Storage::delete('/public/' . $supplier->npwp_file);
            $npwpFileName = Str::random(10) . '_' . time() . 'npwp_file_' . $request->file('npwp_file')->getClientOriginalName();
            $npwpFilePath = $request->file('npwp_file')->storeAs('uploads/supplier', $npwpFileName, 'public');
            $supplier->npwp_file = $npwpFilePath;
        }

        if ($request->companyType == 1  && $request->file('pkp_file')) {
            if($supplier->pkp_file){
              Storage::delete('/public/' . $supplier->pkp_file);
            }
            $pkpFileName = Str::random(10) . '_' . time() . 'pkp_file_' . $request->file('pkp_file')->getClientOriginalName();
            $pkpFilePath = $request->file('pkp_file')->storeAs('uploads/supplier', $pkpFileName, 'public');
            $supplier->pkp_file = $pkpFilePath;
        }

        if($request->companyType == 2){
            if($supplier->pkp_file){
                Storage::delete('/public/' . $supplier->pkp_file);
            }
            $supplier->pkp_file = '';
        }

        $supplier->name = trim($request->name);
        $supplier->email = $request->email;
        $supplier->c_phone_code = $request->c_phone_code?'+'.$request->c_phone_code:'';
        $supplier->mobile = $request->mobile;
        $supplier->website = $request->website;
        $supplier->nib = $request->nib;
        $supplier->npwp = $request->npwp;
        $supplier->group_margin = $request->group_margin;
        //$supplier->address = $request->address;
        $supplier->salutation = $request->salutation;
        $supplier->contact_person_name = $request->contactPersonName;
        $supplier->contact_person_last_name = $request->contactPersonLastName;
        $supplier->contact_person_email = trim($request->contactPersonEmail);
        $supplier->cp_phone_code = $request->cp_phone_code?'+'.$request->cp_phone_code:'';
        $supplier->contact_person_phone = $request->contactPersonMobile;
        $supplier->alternate_email = $request->alternate_email?trim($request->alternate_email):'';
        $supplier->licence = $request->licence;
        $supplier->company_alternative_phone_code = $request->company_alternative_phone_code?'+'.$request->company_alternative_phone_code:'';
        $supplier->company_alternative_phone = $request->company_alternative_phone;
        $supplier->facebook = $request->facebook;
        $supplier->twitter = $request->twitter;
        $supplier->linkedin = $request->linkedin;
        $supplier->youtube = $request->youtube;
        $supplier->instagram = $request->instagram;
        $supplier->profile_username = Str::replace(' ','-',$request->profile_username);
        $supplier->established_date = !empty($request->established_date) ? \Carbon\Carbon::createFromFormat('d-m-Y', $request->established_date)->format('Y-m-d') : '';
        $supplier->updated_by =Auth::id();
        $supplier->interested_in = $request->interested_in??'';
        $supplier->company_type = $request->companyType;
        $supplier->save();

        // Update company name in comapany table by ronak
        // this code comment for live issue (supplier edit then change buyer company name)
		//Company::where('id', auth()->user()->company->id)->update(['name' =>  trim($request->name)]);
		// Update company name in comapany table by ronak

        /**begin: Update supplier company location address and remove previous added addresss*/

        CompanyAddress::where('model_id',$supplier->id)->delete();
        if (!empty($request->address)) {
            foreach ($request->address as $key => $address) {
                if ($request->address[$key]) {
                    $supplierCompany = new CompanyAddress;
                    $supplierCompany->model_type = Supplier::class;
                    $supplierCompany->model_id = $supplier->id;
                    $supplierCompany->user_id =  isset($supplierDetails->user->id) ? $supplierDetails->user->id : null; // get user id by supplier id
                    $supplierCompany->address = $request->address[$key];
                    $supplierCompany->company_id = null;
                    $supplierCompany->is_deleted = 0;
                    $supplierCompany->save();

                    /**begin: system log**/
                    $supplierCompany->bootSystemActivities();
                    /**end: system log**/
                }
            }
        }
        /**end: Update supplier company location address and remove previous added addresss*/

        $user_id = UserSupplier::where('supplier_id', $request->id)->first();
        if (!empty($user_id)){
            $user = User::find($user_id->user_id);
			$user->salutation = $request->salutation;
            $user->firstname = $request->contactPersonName;
			$user->lastname = $request->contactPersonLastName;
            $user->email = trim($request->contactPersonEmail);
            $user->mobile = $request->contactPersonMobile;
            $user->save();
        }

        $needChangeXenCompanyName = false;
        if (!empty($supplier->xen_platform_id) && $supplier->name!=$supplier->xenAccount()->value('business_name')) {
            $needChangeXenCompanyName = true;
        }

        /**begin: system log**/
        $supplier->bootSystemActivities();
        /**end: system log**/
        return response()->json(array('success' => true, 'supplierId' => $supplier->id, 'changeXenCompany'=>$needChangeXenCompanyName));
    }

    function delete(Request $request)
    {
        $supplier_quote = DB::table('quotes')
            ->where('supplier_id', $request->id)
            ->get();

        if($supplier_quote->count() > 0){
            return response()->json(array('success' => false, 'supplierId' => NULL));
        }else{
            $supplier = Supplier::find($request->id);
            $supplier->is_deleted = 1;
            $supplier->save();

            $supplier_products = DB::table('supplier_products')
                ->where('supplier_id', $request->id)
                ->get();
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

    function viewTermAndCondition($name, $companyName)
    {
        return view('admin/supplier/termAndCondition', ['name' => $name, 'companyName' => $companyName]);
    }

    function viewTermAndConditionBlank()
    {
        return view('admin/supplier/termAndCondition', ['name' => '', 'companyName' => '']);
    }


    function fileDelete(Request $request)
    {
        $getOldPkpFile = ''; // use to store old filepath;
        if($request->fileName == "policiesFile"){
            $supplier = CompanyDetails::find($request->id);
        }else{
            $supplier = Supplier::find($request->id);
        }

        if ($request->fileName == "catalogFile") {
            $supplier->catalog = '';
        } else if ($request->fileName == "pricingFile") {
            $supplier->pricing = '';
        } else if ($request->fileName == "productFile") {
            $supplier->product = '';
        } else if ($request->fileName == "commercialConditionFile") {
            $supplier->commercialCondition = '';
        } else if ($request->fileName == "logoFile") {
            $supplier->logo = '';
        } else if ($request->fileName == "nibFile"||$request->fileName == "nib_fileFile") {
            $supplier->nib_file = '';
        } else if ($request->fileName == "npwpFile"||$request->fileName == "npwp_fileFile") {
            $supplier->npwp_file = '';
        } else if ($request->fileName == "pkp_file"||$request->fileName == "pkp_fileFile") {
            $getOldPkpFile = $supplier->pkp_file;
            $supplier->pkp_file = '';
        } else if ($request->fileName == "policiesFile") {
            $supplier->policies_image = '';
        }

        $supplier->save();
        if($request->filePath){
            Storage::delete('/public/' . $request->filePath);
        }else if($getOldPkpFile){ // Remove for pkp file
            Storage::delete('/public/' . $getOldPkpFile);
        }
        return response()->json(array('success' => true));
    }

    function chargesCreateAjax(Request $request)
    {
        // $otherCharge = new OtherCharge;
        // $otherCharge->name = $request->chargeName;
        // $otherCharge->type = $request->chargeType;
        // $otherCharge->charges_value = $request->chargeValue;
        // $otherCharge->value_on = $request->chargeValueOn;
        // $otherCharge->addition_substraction = $request->addition_substraction;
        // $otherCharge->save();

        // $supplierCharge = new SupplierCharge;
        // $supplierCharge->supplier_id = $request->supplier_id;
        // $supplierCharge->other_charges_id = $otherCharge->id;
        // $supplierCharge->save();

        // return response()->json(array('success' => true, 'otherCharge' => $otherCharge));
    }

    function viewSupplierAdd()
    {
        $category = Category::all()->where('is_deleted', 0);
        $subCategory = SubCategory::all()->where('is_deleted', 0);
        $banks = getAllRecordsByCondition('available_banks',['can_disburse'=>1],'id,name,code,logo');
        $brandData = Brand::select('name')->where('is_deleted', 0)->get()->toArray();
        $gradeData = Grade::select('name')->where('is_deleted', 0)->get()->toArray();
        $unit = Unit::all()->where('is_deleted', 0);
        $products = Product::all()->where('is_deleted', 0);
        $brand = implode(',', array_unique(array_column($brandData, 'name')));
        $grade = implode(',', array_unique(array_column($gradeData, 'name')));

        /**begin: system log**/
        Supplier::bootSystemView(new Supplier(), 'Supplier', SystemActivity::ADDVIEW);
        /**end: system log**/
        return view('admin/supplier/supplierAdd', ['category' => $category, 'subCategory' => $subCategory, 'banks' => $banks, 'brand' => $brand, 'grade' => $grade, 'unit' => $unit, 'products' => $products]);
    }

    function viewSupplierAddWithoutLogin()
    {
        $category = Category::all()->where('is_deleted', 0);
        $subCategory = SubCategory::all()->where('is_deleted', 0);
        $brandData = Brand::select('name')->where('is_deleted', 0)->get()->toArray();
        $gradeData = Grade::select('name')->where('is_deleted', 0)->get()->toArray();
        $unit = Unit::all()->where('is_deleted', 0);
        $products = Product::all()->where('is_deleted', 0);
        $brand = implode(',', array_unique(array_column($brandData, 'name')));
        $grade = implode(',', array_unique(array_column($gradeData, 'name')));
        return view('admin/supplier/supplierAdd', ['category' => $category, 'subCategory' => $subCategory, 'brand' => $brand, 'grade' => $grade, 'unit' => $unit, 'products' => $products]);
    }

    function productCreateAjax(Request $request)
    {
        $productImagesFilePath = '';
        if (auth()->user()->role_id == 3) {
            $supplier = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
        } else { $supplier = $request->supplier_id; }
        /*  check this category already added or new added //send mail if new added **/
        $supplierDetails = $categoryArray =  [];
        $categoryArray[0] = $request->supplierSubCategory;
        $supplierDetails = Supplier::where(['is_deleted'=> 0, 'id'=>$supplier])->select('id','contact_person_name','contact_person_last_name','contact_person_email')->first();
        $oldCategoryArray = SupplierDealWithCategory::where(['supplier_id'=>$supplier,'deleted_at'=>null])->pluck('sub_category_id')->toArray();
        $supplierNewaddCategoryList = SupplierProduct::checkSupplierWsieCategoryNewadded($supplier,$categoryArray,$oldCategoryArray); // check this supplier deal in this categories
        if(!empty($supplierNewaddCategoryList[0]['subcat_name']) && !empty($supplierDetails)){
            dispatch(new SupplierNotifyCategoryWiseRfqlistJob($supplierNewaddCategoryList, $supplierDetails));
        }
        $checkCategoryExist = SupplierDealWithCategory::where(['supplier_id'=>$supplier, 'sub_category_id'=>$request->supplierSubCategory, 'deleted_at'=>null])->count();
        if($checkCategoryExist == 0){
            if(!empty($request->supplierSubCategory)){
                $data = array('category_id'=>$request->supplierCategory,'sub_category_id'=>$request->supplierSubCategory,'supplier_id'=>$supplier,'created_at'=>now(),'updated_at'=>now());
                SupplierDealWithCategory::createSupplierDealWithCategory($data);
            }
        }
        /* end mail */

        $supplierProduct = new SupplierProduct;
        $supplierProduct->product_id = $request->supplierProduct;
        $supplierProduct->description = $request->supplierProductDiscription;
        $supplierProduct->supplier_id = $supplier;
        $supplierProduct->price = $request->supplierProductPrice;
        $supplierProduct->min_quantity = $request->supplierProductMinQuantity;
        $supplierProduct->max_quantity = $request->supplierProductMaxQuantity;
        $supplierProduct->quantity_unit_id = $request->supplierProductUnit;
        $supplierProduct->product_ref = $request->productRef;
        $supplierProduct->added_by =  Auth::id();
        $supplierProduct->updated_by =  Auth::id();

        $supplierProduct->save();

        if ($request->file('productImages')) {
            $productImagesFileName = Str::random(10) . '_' . time() . 'productImages_' . $request->file('productImages')->getClientOriginalName();
            $productImagesFilePath = $request->file('productImages')->storeAs('uploads/supplier', $productImagesFileName, 'public');

            $supplierProductImage = new SupplierProductImage;
            $supplierProductImage->supplier_product_id = $supplierProduct->id;
            $supplierProductImage->image = $productImagesFilePath;
            $supplierProductImage->save();
        }


        if ($request->supplierProductBrand) {
            $supplierProductBrand = new SupplierProductBrand();
            $brands = explode(",", $request->supplierProductBrand);

            foreach ($brands as $brandName) {
                $brandName = trim($brandName);
                if ($brandName) {
                    $brand = Brand::where('is_deleted', 0)->where('name', $brandName)->get();
                    if (count($brand)) {
                        $brandId = $brand[0]->id;
                    } else {
                        $brandTable = new Brand();
                        $brandTable->name = $brandName;
                        $brandTable->description = $brandName;
                        $brandTable->status = 1;
                        $brandTable->save();
                        $brandId = $brandTable->id;
                    }

                    $supplierProductBrand = new SupplierProductBrand();
                    $supplierProductBrand->supplier_product_id = $supplierProduct->id;
                    $supplierProductBrand->brand_id = $brandId;
                    $supplierProductBrand->save();
                }
            }
        }


        if ($request->supplierProductGrade) {
            $supplierProductGrade = new SupplierProductGrade();
            $grades = explode(",", $request->supplierProductGrade);

            foreach ($grades as $gradeName) {
                $gradeName = trim($gradeName);
                if ($gradeName) {
                    $grade = Grade::where('is_deleted', 0)->where('name', $gradeName)->where('subcategory_id', $request->supplierSubCategory)->get();
                    if (count($grade)) {
                        $gradeId = $grade[0]->id;
                    } else {
                        $gradeTable = new Grade();
                        $gradeTable->subcategory_id = $request->supplierSubCategory;
                        $gradeTable->name = $gradeName;
                        $gradeTable->description = $gradeName;
                        $gradeTable->status = 1;
                        $gradeTable->save();
                        $gradeId = $gradeTable->id;
                    }

                    $supplierProductGrade = new SupplierProductGrade();
                    $supplierProductGrade->supplier_product_id = $supplierProduct->id;
                    $supplierProductGrade->grade_id = $gradeId;
                    $supplierProductGrade->save();
                }
            }
        }

        // added supplier ranges
        $discount_options = [];
        if (!empty($request->min_qty)){
            foreach ($request->min_qty as $key => $value){
                $discount_options[$key] = array(
                    'supplier_product_id' => $supplierProduct->id,
                    'product_id' => $request->supplierProduct,
                    'supplier_id' => $supplier,
                    'min_qty' => $value,
                    'max_qty' => $request->max_qty[$key],
                    'unit_id' => $request->supplierProductUnit,
                    'discount' => $request->discount[$key],
                    'discounted_price' => $request->discount_price[$key],
                );
            }
        }
        $supplier_discount_option = SupplierProductDiscountRange::insert($discount_options);

        return response()->json(array('success' => true, 'supplierProduct' => $supplierProduct));
    }

    function productUpdateAjax(Request $request)
    {
        $productImagesFilePath = '';

        if (auth()->user()->role_id == 3) {
            $supplier = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
        } else { $supplier = $request->supplier_id; }

        /*  check this category already exist or new added //send mail if new added **/
        $supplierDetails = $categoryArray =  [];
        $categoryId = $request->supplierSubCategory;
        $categoryArray[0] = $categoryId;
        $supplierDetails = Supplier::where(['is_deleted'=> 0, 'id'=>$supplier])->select('id','contact_person_name','contact_person_last_name','contact_person_email')->first();
        $oldCategoryArray = SupplierDealWithCategory::where(['supplier_id'=>$supplier,'deleted_at'=>null])->pluck('sub_category_id')->toArray();
        $supplierNewaddCategoryList = SupplierProduct::checkSupplierWsieCategoryNewadded($supplier,$categoryArray,$oldCategoryArray); // check this supplier deal in this categories
        if(!empty($supplierNewaddCategoryList[0]['subcat_name']) && !empty($supplierDetails)){
            dispatch(new SupplierNotifyCategoryWiseRfqlistJob($supplierNewaddCategoryList, $supplierDetails));
        }
        $checkCategoryExist = SupplierDealWithCategory::where(['supplier_id'=>$supplier, 'sub_category_id'=>$categoryId, 'deleted_at'=>null])->count();
        if($checkCategoryExist == 0){
            $data = array('category_id'=>$request->supplierCategory,'sub_category_id'=>$categoryId,'supplier_id'=>$supplier,'created_at'=>now(),'updated_at'=>now());
            SupplierDealWithCategory::createSupplierDealWithCategory($data);
        }
        /* end mail */

        $supplierProduct = SupplierProduct::find($request->editSupplierProductId);
        $supplierProduct->product_id = $request->supplierProduct;
        $supplierProduct->description = $request->supplierProductDiscription;
        $supplierProduct->price = $request->supplierProductPrice;
        $supplierProduct->min_quantity = $request->supplierProductMinQuantity;
        $supplierProduct->max_quantity = $request->supplierProductMaxQuantity;
        $supplierProduct->quantity_unit_id = $request->supplierProductUnit;
        $supplierProduct->product_ref = $request->productRef;
        $supplierProduct->updated_by =Auth::id();
        $supplierProduct->save();
        //dd($supplierProduct->id);
        //update supplier range
        $getSupplierProduct = SupplierProductDiscountRange::where('supplier_product_id', $request->editSupplierProductId)->get();
        $old_keys = $getSupplierProduct->pluck('id')->toArray();
        $product_details_id = $request->s_id;
        if(empty($product_details_id)){
            $product_details_id = [];
        }

        $diff_values = array_diff($old_keys, $product_details_id);
        if (!empty($diff_values)){
            foreach ($diff_values as $delete_key){
                SupplierProductDiscountRange::where('id', $delete_key)->delete();
            }
        }

        $product_details = $request->min_qty;
        foreach ($product_details as $key => $value){
            $request->min_qty[$key];$request->max_qty[$key];$request->discount[$key];$request->discount_price[$key];
            if (isset($request->custom_add[$key]) && $request->custom_add[$key] == 1){
                $add_product = new SupplierProductDiscountRange;
                $add_product->supplier_product_id = $supplierProduct->id;
                $add_product->product_id = $request->supplierProduct;
                $add_product->supplier_id = $supplier;
                $add_product->min_qty = $request->min_qty[$key];
                $add_product->max_qty = $request->max_qty[$key];
                $add_product->unit_id = $request->supplierProductUnit;
                $add_product->discount = $request->discount[$key];
                $add_product->discounted_price = $request->discount_price[$key];
                $add_product->save();
            } else {
               //echo "-- ". $request->s_id[$key] ." --";
                $add_product = SupplierProductDiscountRange::find($request->s_id[$key]);
                $add_product->id = $request->s_id[$key];
                $add_product->supplier_product_id = $supplierProduct->id;
                $add_product->product_id = $request->supplierProduct;
                $add_product->supplier_id = $supplier;
                $add_product->min_qty = $request->min_qty[$key];
                $add_product->max_qty = $request->max_qty[$key];
                $add_product->unit_id = $request->supplierProductUnit;
                $add_product->discount = $request->discount[$key];
                $add_product->discounted_price = $request->discount_price[$key];
                $add_product->save();
            }

        }

        //end supplier range

        if ($request->file('productImages')) {
            Storage::delete('/public/' . $request->oldproductImages);
            $productImagesFileName = Str::random(10) . '_' . time() . 'productImages_' . $request->file('productImages')->getClientOriginalName();
            $productImagesFilePath = $request->file('productImages')->storeAs('uploads/supplier', $productImagesFileName, 'public');

            $supplierProductImage = SupplierProductImage::where('supplier_product_id', $request->editSupplierProductId)->get();

            if (count($supplierProductImage)) {
                $supplierProductImage = $supplierProductImage[0];
                $supplierProductImage->image = $productImagesFilePath;
                $supplierProductImage->save();
            } else {
                $supplierProductImage = new SupplierProductImage;
                $supplierProductImage->supplier_product_id = $supplierProduct->id;
                $supplierProductImage->image = $productImagesFilePath;
                $supplierProductImage->save();
            }
        }
        $oldbrandData = SupplierProductBrand::where('supplier_product_id', $supplierProduct->id)->get();
        foreach ($oldbrandData as $oldbrand) {
            $oldbrand->delete();
        }
        if ($request->supplierProductBrand) {
            $supplierProductBrand = new SupplierProductBrand();
            $brands = explode(",", $request->supplierProductBrand);

            foreach ($brands as $brandName) {
                $brandName = trim($brandName);
                if ($brandName) {
                    $brand = Brand::where('is_deleted', 0)->where('name', $brandName)->get();
                    if (count($brand)) {
                        $brandId = $brand[0]->id;
                    } else {
                        $brandTable = new Brand();
                        $brandTable->name = $brandName;
                        $brandTable->description = $brandName;
                        $brandTable->status = 1;
                        $brandTable->save();
                        $brandId = $brandTable->id;
                    }
                    $supplierProductBrand = new SupplierProductBrand();
                    $supplierProductBrand->supplier_product_id = $supplierProduct->id;
                    $supplierProductBrand->brand_id = $brandId;
                    $supplierProductBrand->save();
                }
            }
        }

        $oldSupplierData = SupplierProductGrade::where('supplier_product_id', $supplierProduct->id)->get();
        foreach ($oldSupplierData as $oldSupplier) {
            $oldSupplier->delete();
        }
        if ($request->supplierProductGrade) {
            $supplierProductGrade = new SupplierProductGrade();
            $grades = explode(",", $request->supplierProductGrade);

            foreach ($grades as $gradeName) {
                $gradeName = trim($gradeName);
                if ($gradeName) {
                    $grade = Grade::where('is_deleted', 0)->where('name', $gradeName)->where('subcategory_id', $request->supplierSubCategory)->get();
                    if (count($grade)) {
                        $gradeId = $grade[0]->id;
                    } else {
                        $gradeTable = new Grade();
                        $gradeTable->subcategory_id = $request->supplierSubCategory;
                        $gradeTable->name = $gradeName;
                        $gradeTable->description = $gradeName;
                        $gradeTable->status = 1;
                        $gradeTable->save();
                        $gradeId = $gradeTable->id;
                    }

                    $supplierProductGrade = new SupplierProductGrade();
                    $supplierProductGrade->supplier_product_id = $supplierProduct->id;
                    $supplierProductGrade->grade_id = $gradeId;
                    $supplierProductGrade->save();
                }
            }
        }

        return response()->json(array('success' => true, 'supplierProduct' => $supplierProduct));
    }

    function getSupplierProductAjax($supplierProductId)
    {
        $supplierProduct = DB::table('supplier_products')
            ->join('products', 'supplier_products.product_id', '=', 'products.id')
            ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->where('supplier_products.id', $supplierProductId)
            ->where('supplier_products.is_deleted', 0)
            // ->get(['supplier_products.*','products.name as product_name', 'categories.name as category_name', 'sub_categories.name as sub_category_name', 'supplier_products.id as supplier_products_id']);
            ->get(['supplier_products.*', 'products.name as product_name', 'supplier_products.description as product_description', 'sub_categories.id as sub_category_id', 'categories.id as category_id']);

        $supplierProductBrands =  DB::table('supplier_product_brands')
            ->where('supplier_product_brands.supplier_product_id', $supplierProductId)
            ->join('brands', 'supplier_product_brands.brand_id', '=', 'brands.id')
            ->get('brands.name')->toArray();
        $supplierProductBrands = implode(',', array_unique(array_column($supplierProductBrands, 'name')));

        $supplierProductGrades =  DB::table('supplier_product_grades')
            ->where('supplier_product_grades.supplier_product_id', $supplierProductId)
            ->join('grades', 'supplier_product_grades.grade_id', '=', 'grades.id')
            ->get('grades.name')->toArray();

        $supplierProductGrades = implode(',', array_unique(array_column($supplierProductGrades, 'name')));

        $supplierProductImages =  DB::table('supplier_product_images')
            ->where('supplier_product_images.supplier_product_id', $supplierProductId)
            ->get();

        $supplierProductDiscountRanges =  DB::table('supplier_product_discount_ranges')
            ->where('supplier_product_id', $supplierProductId)
            ->get();
        $spdrCount = DB::table('supplier_product_discount_ranges')->where('supplier_product_id', $supplierProductId)->count();
        $units = Unit::where('status',1)->where('is_deleted',0)->orderBy('id', 'DESC')->get(['id', 'name']);
        $returnHTML = view('admin/supplier/supplierProductEditDiscountRange', ['supplierProductDiscountRanges' => $supplierProductDiscountRanges,'units' => $units])->render();
        return response()->json(array('success' => true, 'supplierProduct' => $supplierProduct, 'supplierProductBrands' => $supplierProductBrands, 'supplierProductGrades' => $supplierProductGrades, 'supplierProductImages' => $supplierProductImages, 'html' => $returnHTML, 'spdrCount' => $spdrCount));
    }

    function getSupplierProductListAjax($subCategoryId,$supplierId){
        $products = DB::table('supplier_products')
            ->join('products', 'supplier_products.product_id', '=', 'products.id')
            ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->where('products.subcategory_id', $subCategoryId)
            ->where('supplier_products.supplier_id', $supplierId)
            ->where('supplier_products.is_deleted', 0)
            ->orderBy('products.name', 'desc')
            ->get(['products.name','products.id']);
        //dd($products);
            //->get('products.name as product_name', 'supplier_products.description as product_description', 'sub_categories.id as sub_category_id', 'categories.id as category_id']);
        return response()->json(array('success' => true, 'products' => $products));
    }

    function productDeleteAjax(Request $request)
    {
        //Check if quotation has been send for product id (rfq_id = 5)
        $quoteExist = 0;
        if(isset($request->id)) {
            if(Auth::user()->role_id == Role::SUPPLIER){
                $supplierID = getSupplierByLoginId(Auth::user()->id);
                $productID = SupplierProduct::where('id',$request->id)->pluck('product_id')->first();
                $quoteItems = QuoteItem::with(['quote'=>function($q){
                    $q->whereIN('status_id', ['1','2','5','6']);
                }])->where('supplier_id',$supplierID)->get();
                $quoteItemProducts  = $quoteItems->pluck('product_id')->toArray();
                $supplierProducts = array_unique($quoteItemProducts);
                if (in_array($productID, $supplierProducts)) {
                    $quoteExist = 1;
                }
            }else{
                $quoteExist = Quote::join('quote_items','quotes.id','=','quote_items.quote_id')
                    ->join('products','quote_items.product_id','=','products.id')
                    ->join('supplier_products','products.id','=','supplier_products.product_id')
                    ->join('rfqs','quotes.rfq_id','=','rfqs.id')
                    ->where('rfqs.status_id',5)
                    ->where('supplier_products.id',$request->id)
                    ->count();
            }
        }
        if($quoteExist != 0) {
            return response()->json(array('success' => false));
        } else {
            $supplierProduct = SupplierProduct::find($request->id);
            $supplierProduct->is_deleted = 1;
            $supplierProduct->deleted_by = Auth::id();

            $supplierProduct->save();
            $supplierProductImage = SupplierProductImage::where('supplier_product_id', $request->id)->get();
            if (count($supplierProductImage)) {
                $supplierProductImage = $supplierProductImage[0];
                $supplierProductImage->is_deleted = 1;
                $supplierProductImage->save();
            }
            $supplierProductBrand = SupplierProductBrand::where('supplier_product_id', $request->id)->get();
            if (count($supplierProductBrand)) {
                $supplierProductBrand = $supplierProductBrand[0];
                $supplierProductBrand->is_deleted = 1;
                $supplierProductBrand->save();
            }
            $supplierProductGrade = SupplierProductGrade::where('supplier_product_id', $request->id)->get();
            if (count($supplierProductGrade)) {
                $supplierProductGrade = $supplierProductGrade[0];
                $supplierProductGrade->is_deleted = 1;
                $supplierProductGrade->save();
            }
            return response()->json(array('success' => true));
        }
    }

    function getSupplierBank($id)
    {
        $data = SuppliersBank::getSupplierBank($id);

        if ($data) {
            return response()->json(array('success' => true, 'data' => $data));
        }
        return response()->json(array('success' => false));
    }

    function bankDelete(Request $request)
    {
        if (SuppliersBank::find($request->id)->delete()){
            return response()->json(array('success' => true));
        }else{
            return response()->json(array('success' => false));
        }
    }

    function saveSupplierBank(Request $request)
    {
        $inputs = $request->all();
        $id = (int)$inputs['id'];
        unset($inputs['id']);
        unset($inputs['_token']);
        $result = SuppliersBank::createOrUpdateSuppliersBank($inputs,$id);
        //preDump($result);
        if (is_int($result)){
            $res['is_edit'] = 1;
            $res['supplier_bank_details'] = SuppliersBank::getSupplierBank($result);
        }else{
            $res['is_edit'] = 0;
            $res['supplier_bank_details'] = SuppliersBank::getSupplierBank($result['id']);
        }
        return response()->json(array('success' => true,'data'=>$res));
    }

    function supplierBankStatusUpdate(Request $request)
    {
        $inputs = $request->all();
        $result = SuppliersBank::find($inputs['id']);
        $result->is_primary = $inputs['is_primary'];

        if ($result->save()){
            if ($inputs['is_primary']){
                SuppliersBank::where(['supplier_id' => $result->supplier_id])->where('id','!=',$inputs['id'])->update(['is_primary'=>0]);
            }
            return response()->json(array('success' => true));
        }
        return response()->json(array('success' => false));
    }

    function getBrandGradeProduct($subCategoryId)
    {
        // $brands = DB::table('brands')
        //     ->where('subcategory_id', $subCategoryId)
        //     ->where('is_deleted', 0)
        //     ->orderBy('name', 'desc')
        //     ->get();

        // $grades = DB::table('grades')
        //     ->where('subcategory_id', $subCategoryId)
        //     ->where('is_deleted', 0)
        //     ->orderBy('name', 'desc')
        //     ->get();

        $products = DB::table('products')
            //->select('id', 'name', 'subcategory_id', 'description')
            ->where('subcategory_id', $subCategoryId)
            ->where('is_deleted', 0)
            ->where('status', 1)
            //->groupBy('name')
            ->orderBy('name', 'desc')
            ->get();
        return response()->json(array('success' => true, 'products' => $products));

        // return response()->json(array('success' => true, 'brands' => $brands, 'grades' => $grades, 'products' => $products));
    }

    function changeStatus(Request $request)
    {
        $supplierBankDetails = DB::table('suppliers_banks')
        ->where('supplier_id', $request->id)
        ->get();

        $supplier_quote = DB::table('quotes')
        ->where('supplier_id', $request->id)
        ->get();

        if($supplierBankDetails->count() == 0){
            return response()->json(array('success' => false, 'message' => __('admin.supplier_has_no_primary_account')));
        }
        if($supplier_quote->count() > 0 && $request->status == 0){
            return response()->json(array('success' => false,  'message' => __('admin.supplier_has_quotes')));
        }else{
            $supplier = Supplier::find($request->id);
            $supplier->status = $request->status;
            $supplier->save();
            $user_id = UserSupplier::where('supplier_id', $request->id)->first();
            if (isset($user_id) && !empty($user_id)){
                $user = User::find($user_id->user_id);
                $user->is_active = $request->status;
                $user->save();
            }
            return response()->json(array('success' => true));
        }
    }

    function getAllCategory(Request $request)
    {
        $supplier = Supplier::where('id',$request->id)->where('is_deleted', 0)->first();
        $pieces = explode(",", $supplier->interested_in);

        $supplierProductCategory = DB::table('supplier_products')
            ->join('products', 'supplier_products.product_id', '=', 'products.id')
            ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->where('supplier_products.supplier_id', $request->id)
            ->get(['categories.name', 'supplier_products.supplier_id']);

        return response()->json(array('success' => true, 'supplierProductCategory' => $supplierProductCategory,'pieces'=>$pieces));
    }

    function supplierProductCheck(Request $request)
    {
        if ($request->editSupplierProductId) {
            $supplierProductCount = DB::table('supplier_products')
                ->where('supplier_id', $request->supplier_id)
                ->where('product_id', $request->supplierProduct)
                ->where('id', '!=', $request->editSupplierProductId)
                ->where('is_deleted',0)
                ->count();
        } else {
            $supplierProductCount = DB::table('supplier_products')
                ->where('supplier_id', $request->supplier_id)
                ->where('product_id', $request->supplierProduct)
                ->where('is_deleted',0)
                ->count();
        }
        return response()->json(array('success' => true, 'supplierProductCount' => $supplierProductCount));
    }

    public function isSupplierXenAccountExist($id){
        $result = getXenPlatformIdBySupplierId($id);
        if (empty($result)){
            return response()->json(['success' => false, 'message' => __('admin.xen_platform_ac_not_found_for_this_supplier_pls_create_it')]);
        }
        return response()->json(array('success' => true));
    }

    function downloadSupplierImageAdmin(Request $request){
        if($request->fieldName == "policies_image"){
            $image = CompanyDetails::where('id', $request->id)->pluck($request->fieldName)->first();
        }else{
            $image = Supplier::where('id', $request->id)->pluck($request->fieldName)->first();
        }

        if (!empty($image)){
            ob_end_clean();
            $headers = array('Content-Type: image/*, application/pdf');
            return Storage::download('/public/' . $image, '', $headers);
            //return response()->download(public_path('storage/'.$image));
        }
        return response()->json(array('success' => false));
    }

    public function isEmailExist(Request $request){
        $where = 'email="'.$request->email.'"';
        if (isset($request->id) && !empty($request->id)){
            $where .= ' AND supplier_id!='.$request->id;
        }
        $result = XenSubAccount::whereRaw($where)->first();
        if (empty($result)){
            return response()->json(true);
        }
        return response()->json(false);
    }

    function getXenBalance($supplierId)
    {
        $xenPlatformId = getXenPlatformIdBySupplierId($supplierId);
        $xenAccountBalance = 0;
        if (!empty($xenPlatformId) || (empty($supplierId) && auth()->user()->role_id == 1)) {
            $xendit = new XenditController();
            $xenAccountBalance = $xendit->getBalance($xenPlatformId);
            $xenAccountBalance = number_format($xenAccountBalance['balance'], 2);
        }
        return response()->json(array('success' => true, 'data' => $xenAccountBalance));
    }
    function getLoanBalance($supplierId)
    {
    $supplier = UserSupplier::join('users','users.id','=','user_suppliers.user_id')
        ->where('users.id',auth()->user()->id)
        ->where('users.role_id',3)
        ->first(['user_suppliers.supplier_id']);

      $virtualBalance=0;
      if (\Auth::user()->hasRole('admin') || \Auth::user()->hasRole('supplier')) {

      $orderStatuse =[9,4,8];
      $koinworkBalance = Quote::selectRaw('sum(quotes.supplier_final_amount) AS supplier_final_amount')
                        ->Join('orders', 'orders.quote_id', '=', 'quotes.id')
                        ->where('orders.is_credit', 2)
                        ->where('orders.order_status',  '!=' ,10);
                        if(!empty($supplier) ){
                            $koinworkBalance=  $koinworkBalance->where('orders.supplier_id',$supplier->supplier_id);
                        }
                        $koinworkBalance=  $koinworkBalance->whereIn('orders.order_status',  $orderStatuse)
                                                        ->get();
        $virtualBalance= $koinworkBalance->pluck('supplier_final_amount');
        }

       return response()->json(array('success' => true, 'data' => $virtualBalance ));
    }


    function getXenAccount($supplierId)
    {
        $xenPlatformId = getXenPlatformIdBySupplierId($supplierId);
        $data = [];
        if (!empty($xenPlatformId)) {
            /*$xendit = new XenditController();
            $xenAccountBalance = $xendit->getBalance($xenPlatformId);*/
            $data = XenSubAccount::where('xen_platform_id',$xenPlatformId)->first(['id','xen_platform_id','business_name','email','public_profile']);
            //$data['balance'] = number_format($xenAccountBalance['balance'], 2);
        }
        if (empty($data)){
            return response()->json(array('success' => false, 'data' => $data));
        }
        return response()->json(array('success' => true, 'data' => $data));
    }

    public function getSupplierDetails($supplierId){
        $supplier = Supplier::where('id',$supplierId)->first();

        if (empty($supplier)){
            return response()->json(array('success' => false, 'data' => $supplier));
        }
        return response()->json(array('success' => true, 'data' => $supplier));
    }

    public function createSupplierXenAccount(Request $request){
        $supplier = Supplier::where(['id'=>$request->id,'status'=>1])->first();
        if (empty($supplier)){
            return response()->json(array('success' => false, 'message' => 'Something went wrong!'));
        }elseif (isset($supplier->xen_platform_id) && !empty($supplier->xen_platform_id)){
            return response()->json(array('success' => false, 'message' => __('admin.xen_account_already_exist')));
        }
        $xendit = new XenditController;
        $xenAccount = $xendit->createXenAccount(['business_name'=>$supplier->name,'email'=>$request->email]);
        $supplier->xen_platform_id = $xenAccount['id'];
        $supplier->save();
        $xenAccount['supplier_id'] = $supplier->id;
        XenSubAccount::createOrUpdateXenAccount($xenAccount);
        $xenAccount['xen_platform_id'] = $xenAccount['id'];
        $xenAccount['business_name'] = $supplier->name;
        return response()->json(array('success' => true, 'data' => $xenAccount));
    }

    public function updateSupplierXenAccount(Request $request){
        $supplier = Supplier::where(['id'=>$request->id,'status'=>1])->first();
        if (empty($supplier)){
            return response()->json(array('success' => false, 'message' => __('admin.something_went_wrong')));
        }
        $updateData['xen_platform_id'] = $supplier->xen_platform_id;
        if (isset($request->email)&&!empty($request->email)){
            $updateData['email'] = $request->email;
        }else{
            $xenData = $supplier->xenAccount()->first(['email','business_name']);
            if (!empty($supplier->xen_platform_id) && isset($xenData['business_name']) && $supplier->name!=$xenData['business_name']) {
                $updateData['email'] = $xenData['email'];
                $updateData['business_name'] = $supplier->name;
            }else{
                return response()->json(array('success' => false, 'message' => __('admin.xen_account_not_found')));
            }
        }
        $xendit = new XenditController;
        $xenAccount = $xendit->updateXenAccount($updateData);
        $xenAccount['supplier_id'] = $supplier->id;
        XenSubAccount::createOrUpdateXenAccount($xenAccount);
        return response()->json(array('success' => true,'xenAccount'=>$xenAccount));//, 'data' => $xenAccount

    }

    public function inviteAsBuyer($id){
        $already_exits = UserSupplier::where('supplier_id', $id)->get()->count();
        $supplier_details = Supplier::find($id);
        $check_supplier_email = User::withTrashed()->where('email', $supplier_details->contact_person_email)->first();
        if($already_exits != 0){
            return response()->json(array('success' => false, 'message' => __('admin.supplier_already_exist'), 'heading' => 'Warning', 'icon' => 'warning', 'loaderBg' => '#57c7d4', 'buttons' => ['Cancel', 'OK'] ));
        } elseif (!empty($check_supplier_email)){
            return response()->json(array('success' => false, 'message' => __('admin.email_address_already_registered_as_buyer'), 'heading' => 'Warning', 'icon' => 'warning', 'loaderBg' => '#57c7d4', 'buttons' => ['Cancel', 'OK'] ));
        } elseif (isset($supplier_details) && $supplier_details->status == 0 && empty($supplier_details->xen_platform_id)){
            return response()->json(array('success' => false, 'message' => __('admin.activate_supplier_create_xen_account') , 'heading' => 'Warning', 'icon' => 'warning', 'loaderBg' => '#57c7d4', 'buttons' => ['Cancel', 'OK'] ));

        } elseif (isset($supplier_details) && $supplier_details->status == 0){
            return response()->json(array('success' => false, 'message' => __('admin.please_activate_this_supplier'), 'heading' => 'Warning', 'icon' => 'warning', 'loaderBg' => '#57c7d4', 'buttons' => ['Cancel', 'OK'] ));
        }
        elseif (empty($supplier_details->xen_platform_id)){
            return response()->json(array('success' => false, 'message' => __('admin.create_xen_account_for_supplier'), 'heading' => 'Warning', 'icon' => 'warning', 'loaderBg' => '#57c7d4', 'buttons' => ['Cancel', 'OK'] ));
        }
         else {

            if (isset($supplier_details)){
                $password = Str::random(10);
                $user = new User();
                $user->firstname = $supplier_details->contact_person_name;
                $user->mobile = $supplier_details->contact_person_phone;
                $user->email = $supplier_details->contact_person_email;
                $user->phone_code = $supplier_details->cp_phone_code;
                $user->role_id = 3;
                $user->is_active = $supplier_details->status;
                $user->password = Hash::make($password);
                $user->save();
                $userId =  $user->id;
                $company = new Company();
                $company->name = $supplier_details->name;
                $company->save();
                $companyId = $company->id;
                UserCompanies::insert(['user_id' => $userId, 'company_id' => $companyId]);
                UserSupplier::insert(['user_id' => $userId, 'supplier_id' => $id]);

                // Update user id exiting id;
                if($id){
                    CompanyDetails::where('model_id',$id)
                                 ->where('model_type', Supplier::class)
                                 ->update(['user_id' => $userId]);

                    CompanyhigHlights::where('model_id',$id)
                                 ->where('model_type',Supplier::class)
                                 ->update(['user_id' => $userId]);

                    CompanyAddress::where('model_id',$id)
                                 ->where('model_type',Supplier::class)
                                 ->update(['user_id' => $userId]);

                    CompanyMembers::where('model_id',$id)
                                 ->where('model_type',Supplier::class)
                                 ->update(['user_id' => $userId]);
                }


                // Assigne user to Supplier Role.
                $agentPermissions = SpatieRole::findByName('supplier')->permissions->pluck('name');
                $user->assignRole('supplier');
                $user->givePermissionTo($agentPermissions);

                //search all rfq, quote and order then add in this group
                try {
                    dispatch(new ChatAddNewSupplierAsUser($id, $userId));
                } catch (\Exception $e) {}

                if ($userId) {
                    $userActivity = new UserActivity();
                    $userActivity->user_id = $userId;
                    $userActivity->activity = 'Account Created';
                    $userActivity->type = 'account';
                    $userActivity->record_id = $user->id;
                    $userActivity->save();
                    $useremail = [
                        'user' => $user,
                        'url' => url('/').'/activate-account?email=' . Crypt::encryptString($user->email),
                        'password' => $password
                    ];
                    try {
                        dispatch(new SendActivationMailToSupplierEmailJob($useremail, $user->email));
                    } catch (\Exception $e) {
                        //echo 'Error - ' . $e;
                    }
                }
                return response()->json(array('success' => true, 'message' => __('admin.invitation_sent_supplier_successfully'), 'heading' => 'Success', 'icon' => '/assets/icons/icon_check.png', 'loaderBg' => '#f96868', 'buttons' => 'OK' ));
            }
            return response()->json(array('success' => false, 'message' => __('admin.something_went_wrong'), 'heading' => 'Warning', 'icon' => 'warning', 'loaderBg' => '#57c7d4', 'buttons' => ['Cancel', 'OK'] ));
        }
    }

    public function isUserEmailExist(Request $request){
        $user = UserSupplier::where('supplier_id', $request->id)->first();
        if (!empty($user)) {
            $where = 'email="' . $request->email . '"';
            if (isset($request->id) && !empty($request->id)) {
                $where .= ' AND id!=' . $user->user_id;
            }
            $result = User::whereRaw($where)->first();
            if (empty($result)) {
                return response()->json(true);
            }
            return response()->json(false);
        } else {
            return response()->json(true);
        }
     }
    //{{---- Remove xen details in supplier @ekta  24/02/22 ----}}
    public function supplierDetailsView($id){
         $supplier = Supplier::find($id);
         $supplierBanks = SuppliersBank::where('supplier_id',$id)->where('is_primary', 1)->first();
         $data = [];
        //         if (isset($supplier->xen_platform_id)&&!empty($supplier->xen_platform_id)) {
        //             $xendit = new XenditController();
        //             $xenAccountBalance = $xendit->getBalance($supplier->xen_platform_id);
        //             $data = XenSubAccount::where('xen_platform_id',$supplier->xen_platform_id)->first(['id','xen_platform_id','business_name','email','public_profile']);
        //             $data['balance'] = number_format($xenAccountBalance['balance'], 2);
        //         }
        $rfqview = view('admin/supplier/supplierViewModal', ['supplier' => $supplier, 'xen_platform' => $data, 'banks' => $supplierBanks])->render();

         /**begin: system log**/
        $supplier->bootSystemView(new Supplier(), 'Supplier', SystemActivity::RECORDVIEW, $supplier->id);
         /**end: system log**/
         return response()->json(array('success' => true, 'rfqview'=>$rfqview));
     }

    public function supplierTransactionList(){
        /**begin: system log**/
        SupplierTransactionCharge::bootSystemView(new SupplierTransactionCharge());
        /**end:  system log**/
        return view('admin/supplier_transaction/index', ['supplerCharges' => SupplierTransactionCharge::all()->sortDesc()]);
    }

    //{{----Invite Buyer -- Ekta 1/03/22-----}}
    public function inviteBuyerList(Request $request)
    {
        $condition = [];
        if($request->ajax()){
            if($request->user_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'invite_buyer.user_id', 'value' => $request->user_ids]);
            }

            if($request->usertype_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'invite_buyer.user_type', 'value' => $request->usertype_ids]);
            }

            if($request->status_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'invite_buyer.status', 'value' => $request->status_ids]);
            }

            if($request->start_date && $request->end_date){
                $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d 00:00:00');
                $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d 23:59:59');
                array_push($condition, ['condition' => 'wherebetween', 'column_name' => 'invite_buyer.date', 'value' => [$start_date,  $end_date] ]);
            }
        }

        if (auth()->user()->role_id == Role::SUPPLIER){
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id');
            $invitebuyer = InviteBuyer::join('suppliers', 'invite_buyer.supplier_id', '=', 'suppliers.id')
                ->where('supplier_id',$supplier_id)
                ->where('role_id',2)
                ->where('invite_buyer.is_deleted',0)
                ->orderBy('invite_buyer.id', 'DESC')
                ->get(['invite_buyer.*','suppliers.name']);
            $inviteBuyerDataHtml = view('admin/invite_buyer/inviteBuyerTableData', ['invitebuyer' => $invitebuyer])->render();
            return view('admin/invite_buyer/inviteBuyerList', ['inviteBuyerDataHtml'=> $inviteBuyerDataHtml,'invitebuyer' => $invitebuyer]);
        }else {
            $invitebuyer = InviteBuyer::join('roles', 'invite_buyer.user_type', '=', 'roles.id')
                ->where('invite_buyer.role_id',2)
                ->where('invite_buyer.is_deleted',0);

                //Agent permission
                if (Auth::user()->hasRole('agent')) {

                    $invitebuyer->where('added_by', Auth::user()->id);

                }
//                ->orderBy('invite_buyer.id', 'DESC')
//                ->get(['invite_buyer.*','roles.name as user_type_name']);
                if(sizeof($condition) > 0){
                    foreach($condition as $key => $value){
                        if(strtoupper($value['condition']) == "WHEREIN"){
                            $invitebuyer = $invitebuyer->whereIn($value['column_name'], $value['value']);
                        }
                        if(strtoupper($value['condition']) == "WHEREBETWEEN"){
                            $invitebuyer = $invitebuyer->whereBetween($value['column_name'], $value['value']);
                        }
                    }
                }
                $invitebuyer = $invitebuyer->orderBy('invite_buyer.id', 'DESC')->get(['invite_buyer.*','roles.name as user_type_name']);
                foreach($invitebuyer as $key => $value){
                    if ($value->user_type == Role::SUPPLIER){
                        //$supplier = UserSupplier::join('suppliers', 'user_suppliers.supplier_id', 'suppliers.id')->first('name');
                        $supplier = Supplier::where('id',$value->supplier_id)->first('name');
                        $value['user_name'] = !empty($supplier) ? $supplier->name : '';
                    } elseif ($value->user_type == Role::BUYER || $value->user_type == Role::AGENT){
                        $user = User::where('id',$value->user_id)->first(['firstname','lastname']);
                        $value['user_name'] = !empty($user) ? $user->firstname.' '.$user->lastname : '';
                    } else {
                        $value['user_name'] = 'Blitznet Team';
                    }
                }

            //$invitebuyer = $invitebuyer->orderBy('invite_buyer.id', 'DESC')->get(['invite_buyer.*','roles.name as user_type_name']);
            //dd($invitebuyer->toArray());
            //for filter use
            $status = InviteBuyer::where('role_id', 2)
                ->where('is_deleted',0);

                //Agent permissions
                if (Auth::user()->hasRole('agent')) {
                    $status->where('added_by', Auth::user()->id);
                }

            $status = $status->select('id', 'status')
                ->groupBy('status')
                ->get()->toArray();

            //dd($status);
            $user_types = InviteBuyer::where('role_id', 2)
                ->where('is_deleted',0);

                //Agent permissions
                if (Auth::user()->hasRole('agent')) {
                    $user_types->where('added_by', Auth::user()->id);
                }

                $user_types = $user_types->select('id', 'user_type')
                ->groupBy('user_type')
                ->get()->toArray();

            $users = InviteBuyer::where('role_id', 2)
                ->where('is_deleted',0);

                //Agent permissions
                if (Auth::user()->hasRole('agent')) {
                    $users->where('added_by', Auth::user()->id);
                }

            $users = $users->select('user_id','user_type')
                ->groupBy('user_id')
                ->get();
            foreach($users as $key => $value){
                if ($value->user_type == 3){
                    $supplier = UserSupplier::join('suppliers', 'user_suppliers.supplier_id', 'suppliers.id')->first('name');
                    $value['user_name'] = !empty($supplier) ? $supplier->name : '';
                } elseif ($value->user_type == 2 || $value->user_type == 5) {
                    $user = User::where('id', $value->user_id)->first(['firstname', 'lastname']);
                    $value['user_name'] = !empty($user) ? $user->firstname . ' ' . $user->lastname : '';
                }
                else {
                    $value['user_name'] = 'Blitznet Team';
                }
            }
            $users = $users->toArray();
            // end filters

            $inviteBuyerDataHtml = view('admin/invite_buyer/inviteBuyerTableData', ['invitebuyer' => $invitebuyer])->render();
            if($request->ajax()){
                return $inviteBuyerDataHtml;
            }

             /**begin: system log**/
             InviteBuyer::bootSystemView(new InviteBuyer());
            /**end:  system log**/
            return view('admin/invite_buyer/inviteBuyerList', ['inviteBuyerDataHtml'=> $inviteBuyerDataHtml,'invitebuyer' => $invitebuyer , 'status' => $status , 'user_types' => $user_types , 'users' => $users]);
        }
    }

    public function inviteBuyerAdd()
    {
        $suppliers = Supplier::all()->where('is_deleted',0);

        //Agent category permission
        if (Auth::user()->hasRole('agent')) {

            $suppliers = Supplier::all()->where('added_by', Auth::user()->id)->where('is_deleted',0);

        }

        return view('admin/invite_buyer/inviteBuyerAdd', ['suppliers' => $suppliers]);
    }

    public function inviteBuyerCreate(Request $request)
    {
        //dd($request->all());
        if (auth()->user()->role_id == 3) {
            //dd('supplier');
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $user_id = auth()->user()->id;
            $user_typa = 3; //supplier
            $supplier = DB::table('suppliers')
                ->where('id', $supplier_id)
                ->first(['contact_person_name', 'name']);
        } else {
            //dd('admin');
            if(isset($request->supplier_id)){
                //two possibilities 1-user 2-supplier
                if(!empty($request->supplier_id && $request->user_type == 3)){
                    $supplier_id = $request->supplier_id;
                    $user_id = UserSupplier::where('supplier_id', $supplier_id)->pluck('user_id')->first();
                    $user_typa = $request->user_type; //supplier
                    //for mail
                    $supplier = DB::table('suppliers')
                        ->where('id', $supplier_id)
                        ->first(['contact_person_name', 'name']);
                }else{
                    //ask to nimisha mam
                    $supplier_id = null;
                    $user_id = $request->supplier_id;
                    $user_typa = $request->user_type; //buyer
                    //for mail
                    $supplier = DB::table('users')
                        ->where('id', $request->supplier_id)
                        ->selectRaw('CONCAT(firstname, " ", lastname) as contact_person_name, firstname as name ')
                        //->first(['firstname', 'lastname']);
                    ->first();
                }
            }else{
                $supplier_id = null;
                $user_id = auth()->user()->id;
                $user_typa = Auth::user()->hasRole('agent') ? Auth::user()->role_id : 1; //buyer

                //for mail
                if (Auth::user()->hasRole('agent')) {
                    $supplier = User::where('id', Auth::user()->id)
                        ->selectRaw('CONCAT(firstname, " ", lastname) as contact_person_name, firstname as name ')
                        ->first();
                } else {
                    $supplier = new static;
                    $supplier->contact_person_name = 'Blitznetteam' ;
                    $supplier->name ='Blitznet team';
                }
            }
        }

        $token = Str::random(64);
        $inviteBuyer = new InviteBuyer();
        $inviteBuyer->supplier_id = $supplier_id;
        $inviteBuyer->user_id = $user_id;
        $inviteBuyer->role_id = 2;
        if(!empty($request->supplier_id && $request->user_type == 2)) {
            $inviteBuyer->company_id = User::find($request->supplier_id)->default_company??null;
        }
        $inviteBuyer->user_type = $user_typa;
        $inviteBuyer->user_email = $request->user_email;
        $inviteBuyer->token = $token;
        $inviteBuyer->added_by =  Auth::id();
        $inviteBuyer->save();
        $supplier->buyer_supplier_mail = 2;
        $useremail = [
            'user' => $supplier,
            'url' => route('signup-user', ['email' => Crypt::encryptString($inviteBuyer->user_email),'token' => $token ])
        ];

        try {
            dispatch(new InviteBuyerActivationJob($useremail, $inviteBuyer->user_email));
        } catch (\Exception $e) {
            //echo 'Error - ' . $e;
            dd($e);
        }
        return response()->json(array('success' => true));
        //return redirect('/admin/invite_buyer/inviteBuyerList');
    }

    public function checkUserEmailExist(Request $request){
        $result = User::where('email', $request->email)->count();
        $buyer = InviteBuyer::where('user_email', $request->email)->count();
        $supplier = Supplier::where('email', $request->email)->orWhere('contact_person_email',$request->email)->count();

        if ($result > 0){
            return response()->json(false);
        }elseif ($buyer > 0){
            return response()->json(false);
        }
        if (!empty($supplier)){
            return response()->json(false);
        }else{
            return response()->json(true);
        }

        return response()->json(true);
    }

    public function inviteBuyerEdit($id){
        $id = Crypt::decrypt($id);
        $inviteBuyer = InviteBuyer::find($id);
        if ($inviteBuyer) {
            if($inviteBuyer->user_type == 3){
                $suppliers = DB::table('suppliers')
                    ->where('is_deleted', 0)
                    ->select('id', 'name')
                    ->get();
            }elseif($inviteBuyer->user_type == 2){
                $suppliers = DB::table('users')
                    ->where('role_id', 2)
                    ->where('is_delete', 0)
                    ->selectRaw('CONCAT(firstname, " ", lastname) as name, id')
                    ->get();
            }else{
                $suppliers = DB::table('suppliers')
                    ->where('is_deleted', 0)
                    ->select('id', 'name')
                    ->get();
            }
            //dd($inviteBuyer);
            /**begin: system log**/
            $inviteBuyer->bootSystemView(new InviteBuyer(), 'InviteBuyer', SystemActivity::EDITVIEW, $inviteBuyer->id);
            /**end: system log**/
            return view('/admin/invite_buyer/inviteBuyerEdit', ['invitebuyer' => $inviteBuyer, 'suppliers' => $suppliers]);
        } else {
            return redirect('/admin/invite_buyer/inviteBuyerList');
        }

    }

    public function checkUserEmailEditExist(Request $request)
    {
        $result = User::withTrashed()->where('email', $request->email)->count();
        $buyer = InviteBuyer::where('user_email', $request->email)->where('id','<>', $request->id)->count();
        $supplier = Supplier::where('email', $request->email)->orWhere('contact_person_email',$request->email)->count();
        if (!empty($result)){
            return response()->json(false);
        }
        if (!empty($buyer)){
            return response()->json(false);
        }
        if (!empty($supplier)){
            return response()->json(false);
        }
        return response()->json(true);
    }

    public function inviteBuyerUpdate(Request $request)
    {
        //dd($request->all());
        if (auth()->user()->role_id == 3) {
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $user_id = auth()->user()->id;
            $user_typa = 3; //supplier

            //no change data
            $checkRecords = InviteBuyer::where([['user_email', '=' ,$request->user_email], ['id',$request->id], ['supplier_id',$supplier_id]])->count();
            if($checkRecords > 0){
                return response()->json(array('success' => true));
            }else{
                $supplier = DB::table('suppliers')
                    ->where('id', $request->supplier_id)
                    ->first(['contact_person_name', 'name']);
            }
        } else {
            if($request->supplier_id > 0){
                //two possibilities 1-user 2-supplier
                if($request->supplier_id > 0 && $request->user_type == 3){
                    $supplier_id = $request->supplier_id;
                    $user_id = UserSupplier::where('supplier_id', $supplier_id)->pluck('user_id')->first();
                    $user_typa = $request->user_type; //supplier
                    //for mail
                    $supplier = DB::table('suppliers')
                        ->where('id', $supplier_id)
                        ->first(['contact_person_name', 'name']);
                }else{
                    //ask to nimisha mam
                    $supplier_id = null;
                    $user_id = $request->supplier_id;
                    $user_typa = $request->user_type; //buyer
                    //for mail
                    $supplier = DB::table('users')
                        ->where('id', $request->supplier_id)
                        ->selectRaw('CONCAT(firstname, " ", lastname) as contact_person_name, firstname as name ')
                        //->first(['firstname', 'lastname']);
                        ->first();
                }
            }else{
                $supplier_id = null;
                $user_id = auth()->user()->id;
                $user_typa = Auth::user()->hasRole('agent') ? Auth::user()->role_id : 1; //buyer

                //for mail
                if (Auth::user()->hasRole('agent')) {
                    $supplier = User::where('id', Auth::user()->id)
                        ->selectRaw('CONCAT(firstname, " ", lastname) as contact_person_name, firstname as name ')
                        ->first();
                } else {
                    $supplier = new static;
                    $supplier->contact_person_name = 'Blitznetteam' ;
                    $supplier->name ='Blitznet team';
                }

            }
            // check no change data
            $checkRecords = InviteBuyer::where([['user_email', '=' ,$request->user_email], ['id',$request->id], ['user_id',$user_id]])->count();
            if($checkRecords > 0){
                return response()->json(array('success' => true));
            }
        }
        //dd($supplier_id,$user_id,$user_typa);
        //dd($request->user_email,$request->id,$user_id);
        $token = Str::random(64);
        $inviteBuyer = InviteBuyer::find($request->id);
        $inviteBuyer->supplier_id = $supplier_id;
        $inviteBuyer->user_id = $user_id;
        if(!empty($request->supplier_id && $request->user_type == 2)) {
            $inviteBuyer->company_id = User::find($request->supplier_id)->default_company??null;
        }
        $inviteBuyer->role_id = 2;
        $inviteBuyer->user_type = $user_typa;
        $inviteBuyer->user_email = $request->user_email;
        $inviteBuyer->token = $token;
        $inviteBuyer->added_by =  Auth::id();
        $inviteBuyer->save();
        $supplier->buyer_supplier_mail = 2;
        $useremail = [
            'user' => $supplier,
            'url' => route('signup-user', ['email' => Crypt::encryptString($inviteBuyer->user_email),'token' => $token ])
        ];

        try {
            dispatch(new InviteBuyerActivationJob($useremail, $inviteBuyer->user_email));
        } catch (\Exception $e) {
            //echo 'Error - ' . $e;
            dd($e);
        }
        /**begin: system log**/
        $inviteBuyer->bootSystemActivities();
        /**end: system log**/
        return response()->json(array('success' => true));
    }

    public function inviteBuyerResend(Request $request)
    {
        $token = Str::random(64);
        $inviteBuyer = InviteBuyer::find($request->id);
        $getResend = $inviteBuyer->resend_count;
        $resendCount = $getResend + 1;

        $inviteBuyer->token = $token;
        $inviteBuyer->resend_count = $resendCount;
        $inviteBuyer->status = '0';

        $inviteBuyerData = $inviteBuyer->save();

        //check invitation sent from admin / supplier / user
        if(isset($inviteBuyer->user_type) && $inviteBuyer->user_type == 1){
            $supplier = new static;
            $supplier->contact_person_name = 'Blitznet';
            $supplier->name ='Blitznet Team';
        }elseif(isset($inviteBuyer->user_type) && $inviteBuyer->user_type == 5){
            $supplier = new static;
            $supplier->contact_person_name = Auth::user()->firstname;
            $supplier->name = Auth::user()->firstname.' '.Auth::user()->lastname;
        }elseif (isset($inviteBuyer->user_type) && $inviteBuyer->user_type == 2){
            $supplier = DB::table('users')
                ->join('user_companies','user_companies.user_id','=','users.id')
                ->join('companies','user_companies.company_id','=','companies.id')
                ->where('users.id',$inviteBuyer->user_id)
                ->selectRaw('CONCAT(users.firstname, " ", users.lastname) as contact_person_name, companies.name as name')
                ->first();
        }else{
            $supplier_id = UserSupplier::where('user_id', $inviteBuyer->user_id)->pluck('supplier_id')->first();
            $supplier = DB::table('suppliers')
                ->where('id', $supplier_id)
                ->first(['contact_person_name', 'name']);
        }
        $supplier->buyer_supplier_mail = 2;
        //check user mail or supplier mail
        if($inviteBuyer->role_id == 2){
            $supplier->buyer_supplier_mail = 2;
            $useremail = [
                'user' => $supplier,
                'url' => route('signup-user', ['email' => Crypt::encryptString($inviteBuyer->user_email),'token' => $token ])
            ];
        }else{
            $useremail = [
                'user' => $supplier,
                'url' => route('signup-supplier-invited', ['email' => Crypt::encryptString($inviteBuyer->user_email),'token' => $token ])
            ];
        }

        try {
            dispatch(new InviteBuyerActivationJob($useremail, $inviteBuyer->user_email));
        } catch (\Exception $e) {
            //echo 'Error - ' . $e;
            return response()->json(['success' => false]);
        }
        /**begin: system log**/
        $inviteBuyer->bootSystemActivities();
        /**end: system log**/
        return response()->json(array('success' => true));
        //return redirect('/admin/invite_buyer/inviteBuyerList');
    }
    //Add Supplier Product
    public function addSupplierProduct() {
        $supplier = UserSupplier::join('users','users.id','=','user_suppliers.user_id')
        ->where('users.id',auth()->user()->id)->first(['user_suppliers.supplier_id']);

        $category = Category::all()->where('is_deleted', 0)->where('status', 1);
        $subCategory = SubCategory::all()->where('is_deleted', 0)->where('status', 1);
        $brandData = Brand::select('name')->where('is_deleted', 0)->get()->toArray();
        $gradeData = Grade::select('name')->where('is_deleted', 0)->get()->toArray();
        $unit = Unit::all()->where('is_deleted', 0);
        $products = Product::all()->where('is_deleted', 0);
        $check_as_buyer = UserSupplier::where('supplier_id',$supplier->supplier_id)->get()->count();

        $supplierProduct = DB::table('supplier_products')
            ->join('products', 'supplier_products.product_id', '=', 'products.id')
            ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->where('supplier_products.supplier_id', $supplier->supplier_id)
            ->where('supplier_products.is_deleted', 0)
            ->get(['products.name as product_name', 'products.description as product_description', 'categories.name as category_name', 'sub_categories.name as sub_category_name', 'supplier_products.id as supplier_products_id']);
        $brand = implode(',', array_unique(array_column($brandData, 'name')));
        $grade = implode(',', array_unique(array_column($gradeData, 'name')));

        return view('admin/supplier/add_supplier_product', ['supplier' => $supplier,'category' => $category, 'subCategory' => $subCategory, 'brand' => $brand, 'grade' => $grade, 'unit' => $unit, 'products' => $products, 'supplierProduct' => $supplierProduct, 'asBuyer'=> $check_as_buyer]);
    }

    //Edit Supplier Product (Ronak Makwana - 19/04/2022)
    public function editSupplierProduct($id) {
        $supp_prod_id = Crypt::decrypt($id);
        //dd($supp_prod_id);
        $product = DB::table('supplier_products')
            ->join('products', 'supplier_products.product_id', '=', 'products.id')
            ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->leftjoin('rfq_products','products.name','=','rfq_products.product')
            ->where('supplier_products.id', $supp_prod_id)
            ->where('supplier_products.is_deleted', 0)
            ->first(['supplier_products.*', 'products.name as name', 'supplier_products.description as description', 'sub_categories.id as subcategory_id', 'categories.id as category_id','rfq_products.rfq_id']);

        $prodBrands = SupplierProductBrand::join('brands','supplier_product_brands.brand_id','=','brands.id')
        ->where('supplier_product_brands.supplier_product_id',$supp_prod_id)->get(['brands.id','brands.name'])->toArray();

        $prodGrades = SupplierProductGrade::join('grades','supplier_product_grades.grade_id','=','grades.id')
        ->where('supplier_product_grades.supplier_product_id',$supp_prod_id)->get(['grades.id','grades.name'])->toArray();

        if ($product) {
            $supplier_product = DB::table('supplier_products')
                ->join('products', 'supplier_products.product_id', '=', 'products.id')
                ->where('supplier_products.supplier_id',$product->supplier_id)
				->get(['supplier_products.product_id','products.name as prodName']);
            // $sproduct = array();
            // foreach ($supplier_product as $suplierProduct)
            //     $sproduct[] = $suplierProduct->product_id;
            $subCategories = SubCategory::all()->where('category_id',$product->category_id);
            $categories = Category::all()->where('is_deleted',0);
            $brandData = Brand::select('name')->where('is_deleted', 0)->get()->toArray();
            $brands = implode(',', array_unique(array_column($brandData, 'name')));
            $gradeData = Grade::select('name')->where('is_deleted', 0)->get()->toArray();
            $grades = implode(',', array_unique(array_column($gradeData, 'name')));
            $productBrands = DB::table('product_brands')
                ->where('product_id', $id)
                ->get('brand_id');
            $productBrandArray = array();
            foreach ($productBrands as $value)
                $productBrandArray[] = $value->brand_id;
            $unit = Unit::all()->where('is_deleted', 0);
        }

        //Supplier Discount Range @ekta 21-04
        $supplierProductDiscountRanges =  DB::table('supplier_product_discount_ranges')
            ->where('supplier_product_id', $supp_prod_id)
            ->get();
        $spdrCount = DB::table('supplier_product_discount_ranges')->where('supplier_product_id', $supp_prod_id)->count();

        return view('admin/supplier/edit_supplier_product', ['product'=> $product, 'prodBrands' => $prodBrands, 'prodGrades' => $prodGrades, 'supplier_product' => $supplier_product, 'categories' => $categories, 'subCategories' => $subCategories, 'brands' => $brands, 'grades' => $grades, 'unit' => $unit, 'productBrands' => $productBrandArray, 'prodId' => $supp_prod_id, 'supplierProductDiscountRanges' => $supplierProductDiscountRanges,  'spdrCount' => $spdrCount]);
    }

    //Update supplier product data
    public function updateSupplierProduct(Request $request) {
        $supplierProduct = SupplierProduct::find($request->supplier_prod_id);
        $supplierProduct->product_id = $request->product_id;
        $supplierProduct->description = strip_tags($request->description);
        $supplierProduct->price = $request->supplierProductPrice;
        $supplierProduct->min_quantity = $request->supplierProductMinQuantity;
        $supplierProduct->quantity_unit_id = $request->supplierProductUnit;
        $supplierProduct->discount = $request->discount;
        $supplierProduct->discounted_price = $request->discounted_price;
        $supplierProduct->product_ref = $request->productRef;
        $supplierProduct->updated_by = Auth::id();
        $supplierProduct->save();

        if ($request->file('productImages')) {
            Storage::delete('/public/' . $request->oldproductImages);
            $productImagesFileName = Str::random(10) . '_' . time() . 'productImages_' . $request->file('productImages')->getClientOriginalName();
            $productImagesFilePath = $request->file('productImages')->storeAs('uploads/supplier', $productImagesFileName, 'public');

            $supplierProductImage = SupplierProductImage::where('supplier_product_id', $request->editSupplierProductId)->get();

            if (count($supplierProductImage)) {
                $supplierProductImage = $supplierProductImage[0];
                $supplierProductImage->image = $productImagesFilePath;
                $supplierProductImage->save();
            } else {
                $supplierProductImage = new SupplierProductImage;
                $supplierProductImage->supplier_product_id = $supplierProduct->id;
                $supplierProductImage->image = $productImagesFilePath;
                $supplierProductImage->save();
            }
        }
        $oldbrandData = SupplierProductBrand::where('supplier_product_id', $supplierProduct->id)->get();
        foreach ($oldbrandData as $oldbrand) {
            $oldbrand->delete();
        }
        if ($request->supplierProductBrand) {
            $supplierProductBrand = new SupplierProductBrand();
            $brands = explode(",", $request->supplierProductBrand);

            foreach ($brands as $brandName) {
                $brandName = trim($brandName);
                if ($brandName) {
                    $brand = Brand::where('is_deleted', 0)->where('name', $brandName)->get();
                    if (count($brand)) {
                        $brandId = $brand[0]->id;
                    } else {
                        $brandTable = new Brand();
                        $brandTable->name = $brandName;
                        $brandTable->description = $brandName;
                        $brandTable->status = 1;
                        $brandTable->save();
                        $brandId = $brandTable->id;
                    }
                    $supplierProductBrand = new SupplierProductBrand();
                    $supplierProductBrand->supplier_product_id = $supplierProduct->id;
                    $supplierProductBrand->brand_id = $brandId;
                    $supplierProductBrand->save();
                }
            }
        }

        $oldSupplierData = SupplierProductGrade::where('supplier_product_id', $supplierProduct->id)->get();
        foreach ($oldSupplierData as $oldSupplier) {
            $oldSupplier->delete();
        }
        if ($request->supplierProductGrade) {
            $supplierProductGrade = new SupplierProductGrade();
            $grades = explode(",", $request->supplierProductGrade);

            foreach ($grades as $gradeName) {
                $gradeName = trim($gradeName);
                if ($gradeName) {
                    $grade = Grade::where('is_deleted', 0)->where('name', $gradeName)->where('subcategory_id', $request->supplierSubCategory)->get();
                    if (count($grade)) {
                        $gradeId = $grade[0]->id;
                    } else {
                        $gradeTable = new Grade();
                        $gradeTable->subcategory_id = $request->supplierSubCategory;
                        $gradeTable->name = $gradeName;
                        $gradeTable->description = $gradeName;
                        $gradeTable->status = 1;
                        $gradeTable->save();
                        $gradeId = $gradeTable->id;
                    }

                    $supplierProductGrade = new SupplierProductGrade();
                    $supplierProductGrade->supplier_product_id = $supplierProduct->id;
                    $supplierProductGrade->grade_id = $gradeId;
                    $supplierProductGrade->save();
                }
            }
        }
        return response()->json(array('success' => true));
    }

    //Supplier Address Listing (Ronak)
    public function supplierAddressList() {
        $supplier = UserSupplier::where('user_id',auth()->user()->id)->first(['supplier_id']);
        $addresses = SupplierAddress::where('supplier_id', $supplier->supplier_id)
        ->where('is_deleted', 0)->orderBy('id','DESC')->get();
        return view('admin/supplier/address_listing', ['addresses' => $addresses]);
    }

    //Add Supplier Address (Ronak)
    public function addSupplierAddress() {
        $countries = CountryOne::get(['id', 'name']);
        return view('admin/supplier/add_supplier_address')->with(compact(['countries']));
    }

    //Store Supplier Address (Ronak)
    public function createSupplierAddress(Request $request) {
        $supplier = UserSupplier::where('user_id',auth()->user()->id)->first(['supplier_id']);
        $supplierAddress = new SupplierAddress();
        $supplierAddress->supplier_id = $supplier->supplier_id;
        $supplierAddress->address_name = $request->address_name;
        $supplierAddress->address_line_1 = $request->address_line_1;
        $supplierAddress->address_line_2 = $request->address_line_2 ? $request->address_line_2 : '';
        $supplierAddress->pincode = $request->pincode;
        $supplierAddress->city = $request->cityId == UserAddresse::OtherCity ? $request->city : '';
        $supplierAddress->state = $request->stateId == UserAddresse::OtherCity ? $request->state : '';
        $supplierAddress->city_id = $request->cityId;
        $supplierAddress->state_id = $request->stateId;
        $supplierAddress->country_id = $request->countryId;
        $supplierAddress->sub_district = $request->sub_district;
        $supplierAddress->district = $request->district;
        $supplierAddress->default_address = $request->default_address == true ? 1 : 0;
        $supplierAddress->save();
        if($request->default_address == 1) {
            SupplierAddress::where(['supplier_id' => $supplier->supplier_id])->where('id','!=',$supplierAddress->id)->update(['default_address' => 0]);
        }
        return response()->json(array('success' => true));
        return redirect('/admin/supplier-address-list');
    }

    //Get Supplier Address by id (Ronak)
    public function getSupplierAddress($addressId) {
        $addressData = SupplierAddress::find($addressId);
        $addressView = view('admin/supplier/supplier_address_modal',['addressData'=>$addressData])->render();
        return response()->json(array('success' => true, 'addressView'=>$addressView));
    }

    //Get Supplier Address Details
    public function editSupplierAddress($id) {
        $addressId = Crypt::decrypt($id);
        $address = SupplierAddress::find($addressId);
        $all_addresses = SupplierAddress::where('supplier_id', auth()->user()->id)
        ->where('is_deleted', 0)->orderBy('id','DESC')->get();
        $countries = CountryOne::get(['id', 'name']);
        $states = State::where('country_id', $address->country_id)->get(['id', 'name']);
        $cityes = City::where(['country_id' => $address->country_id, 'state_id' => $address->state_id])->get(['id', 'name']);
        return view('/admin/supplier/edit_supplier_address', ['address' => $address, 'all_addresses' => $all_addresses, 'countries' => $countries, 'states' => $states, 'cities' => $cityes]);
    }

    //Update Supplier Address (Ronak)
    public function updateSupplierAddress(Request $request) {
        $supplierAddress = SupplierAddress::find($request->id);
        $supplier = UserSupplier::where('user_id',auth()->user()->id)->first(['supplier_id']);
        $supplierAddress->supplier_id = $supplier->supplier_id;
        $supplierAddress->address_name = $request->address_name;
        $supplierAddress->address_line_1 = $request->address_line_1;
        $supplierAddress->address_line_2 = $request->address_line_2 ? $request->address_line_2 : '';
        $supplierAddress->pincode = $request->pincode;
        $supplierAddress->city = $request->cityId == UserAddresse::OtherCity ? $request->city : '';
        $supplierAddress->state = $request->stateId == UserAddresse::OtherCity ? $request->state : '';
        $supplierAddress->city_id = $request->cityId;
        $supplierAddress->state_id = $request->stateId;
        $supplierAddress->country_id = $request->countryId;
        $supplierAddress->sub_district = $request->sub_district;
        $supplierAddress->district = $request->district;
        $supplierAddress->default_address = $request->default_address == true ? 1 : 0;
        $supplierAddress->save();
        if($request->default_address == 1) {
            SupplierAddress::where(['supplier_id' => $supplier->supplier_id])->where('id','!=',$supplierAddress->id)->update(['default_address' => 0]);
        }
        return response()->json(array('success' => true));
        return redirect('/admin/supplier-address-list');
    }

    //Make supplier address primary (only one address can be primary at a time)
    public function supplierAddressStatusUpdate(Request $request) {
        $inputs = $request->all();
        $result = SupplierAddress::find($inputs['id']);
        $result->default_address = $inputs['is_primary'];
        if ($result->save()){
            if ($inputs['is_primary']){
                SupplierAddress::where(['supplier_id' => $result->supplier_id])->where('id','!=',$inputs['id'])->update(['default_address'=>0]);
            }
            return response()->json(array('success' => true));
        }
        return response()->json(array('success' => false));
    }

    //Delete supplier address by id
    public function deleteSupplierAddress(Request $request) {
        if ($request->id){
            $SupplierAddress = SupplierAddress::find($request->id);
            $SupplierAddress->is_deleted = 1;
            $SupplierAddress->save();
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('success' => false));
        }
    }

    //Get supplier address by supplier id
    public function getSupplierAddressById($supplier_id) {
        $addresses = SupplierAddress::where('supplier_id', $supplier_id)
        ->where('is_deleted', 0)->orderBy('id','DESC')->get();

        $tc_document = Supplier::where('id', $supplier_id)->where('is_deleted', 0)->select('commercialCondition')->first();
        $supplier_tc = '';
        if (!empty($tc_document->commercialCondition)){
            $termsconditions_file_name = $imagepath = $tc_documentPath='';
            $termsconditionsFileTitle = Str::substr($tc_document->commercialCondition, stripos($tc_document->commercialCondition, "termsconditions_file_") + 21);
            $extension_termsconditions_file = getFileExtension($termsconditionsFileTitle);
            $termsconditions_file_filename = getFileName($termsconditionsFileTitle);
            if(strlen($termsconditions_file_filename) > 10){
                $termsconditions_file_name = substr($termsconditions_file_filename,0,10).'...'.$extension_termsconditions_file;
            } else {
                $termsconditions_file_name = $termsconditions_file_filename.$extension_termsconditions_file;
            }
            $tc_documentPath = $tc_document->commercialCondition ? Storage::url($tc_document->commercialCondition) : "javascript:void(0);";
            $imagepath = URL::asset("front-assets/images/icons/icon_download.png");
            $supplier_tc .='<input type="hidden" class="form-control" id="oldtermsconditions_file" name="oldtermsconditions_file" value="'. $tc_document->commercialCondition .'">';
            $supplier_tc .='<span class="ms-2">';
            $supplier_tc .='<a href="'.$tc_documentPath.'" target="_blank" id="termsconditionsFileDownload" download title="'.$termsconditionsFileTitle.'" style="text-decoration: none;"> '.$termsconditions_file_name.'</a>';
            $supplier_tc .='</span>';
            $supplier_tc .='<span class="ms-2">';
            $supplier_tc .='<a class="termsconditions_file" href="'.$tc_documentPath.'" title="'. __("profile.download_file").'" download style="text-decoration: none;"><img src="'.$imagepath.'" width="14px"></a>';
            $supplier_tc .='</span>';

        }

        return response()->json(['addresses' => $addresses, 'tc_document' => $supplier_tc]);
    }

    //get supplier wise category @ekta 07-04
    public function gatSupplierWiseCategoryList($supplierId){
        $supplierProductCategory = DB::table('supplier_products')
            ->join('products', 'supplier_products.product_id', '=', 'products.id')
            ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->where('supplier_products.supplier_id', $supplierId)
            ->where('supplier_products.is_deleted', 0)
            ->groupBy('categories.id')
            //->orderBy('categories.id', 'desc')
            ->get(['categories.name', 'categories.id']);
        $groupMargine = Supplier::where('id', $supplierId)->pluck('group_margin');
        return response()->json(array('success' => true, 'supplierProductCategory' => $supplierProductCategory,'groupMargine'=>$groupMargine));
    }

    //@ekta-03/05/22 get user type wise data  eg buyer list - user table , supplier list - supplier master
    public function getSupplierBuyerList($radioValue){
        //$radioValue==2 -> user otherwise  $radioValue==3 supplier
        if($radioValue==2){
            $userData = DB::table('users');
            //Agent permission
            if (Auth::user()->hasRole('agent')) {
                $userData->where('added_by', Auth::user()->id);
            }
            $userData = $userData->where('role_id', $radioValue)
                ->where('is_delete', 0)
                ->selectRaw('CONCAT(firstname, " ", lastname) as name, id')
                //->select('id', 'firstname','lastname')
                ->get();
        }else{
            $userData = DB::table('suppliers');
            //Agent permission
            if (Auth::user()->hasRole('agent')) {
                $userData->where('added_by', Auth::user()->id);
            }
            $userData = $userData->where('is_deleted', 0)
                ->select('id', 'name')
                ->get();
        }
        //dd($userData);
        return response()->json(array('success' => true, 'userData' => $userData, 'radioValue'=>$radioValue));
    }

    //{{----Invite supplier -- Ekta 03/05/22-----}}
    public function inviteSupplierList(){
        if (auth()->user()->role_id == 2){
            $invitebuyer = InviteBuyer::join('users', 'invite_buyer.user_id', '=', 'users.id')
                ->where('invite_buyer.user_id',auth()->user()->id)
                ->where('invite_buyer.role_id', 2);
                //Agent permission
                if (Auth::user()->hasRole('agent')) {
                    $invitebuyer->where('invite_buyer.added_by', Auth::user()->id);
                }
            $invitebuyer = $invitebuyer->orderBy('invite_buyer.id', 'DESC')
                ->get(['invite_buyer.*','users.firstname','users.lastname']);
        }else {
            //DB::enableQueryLog();
            $invitebuyer = InviteBuyer::leftjoin('users', 'invite_buyer.user_id', '=', 'users.id')
                ->where('invite_buyer.role_id', 3);
                //Agent permission
                if (Auth::user()->hasRole('agent')) {
                    $invitebuyer->where('invite_buyer.added_by', Auth::user()->id);
                }
            $invitebuyer = $invitebuyer->orderBy('invite_buyer.id', 'DESC')
                ->get(['invite_buyer.*','users.firstname','users.lastname']);
        }
        return view('admin/invite_supplier/invite_supplier_list', ['invitebuyer' => $invitebuyer]);
    }

    public function inviteSupplierAdd()
    {
        $users = DB::table('users')
            ->where('role_id', 2);

            //Agent permission
            if (Auth::user()->hasRole('agent')) {
                $users->where('added_by', Auth::user()->id);
            }

        $users = $users->where('is_delete', 0)
            ->selectRaw('CONCAT(firstname, " ", lastname) as name, id')
            ->get();
        return view('admin/invite_supplier/invite_supplier_add', ['users' => $users]);
    }

    public function inviteSupplierCreate(Request $request)
    {
        //dd($request->all());
        $supplier_id = null;
        if (auth()->user()->role_id == 2) {
            $user_type = 2;
            $user_id = auth()->user()->id;
            //for mail
            $user = DB::table('users')
                ->join('user_companies','user_companies.user_id','=','users.id')
                ->join('companies','user_companies.company_id','=','companies.id')
                ->where('users.id',$user_id)
                ->selectRaw('CONCAT(users.firstname, " ", users.lastname) as contact_person_name, companies.name as name')
                ->first();
        } else {
            if($request->user_id != 0){
                $user_id = $request->user_id;
                $user_type = 2;
                //for mail
                /*
                $user = User::join('user_companies','user_companies.user_id','=','users.id')
                    ->join('companies','user_companies.company_id','=','companies.id')
                    ->where('users.id',$request->user_id)
                    ->selectRaw('CONCAT(users.firstname, " ", users.lastname) as contact_person_name, companies.name as name')
                    ->first();
                */
                //dd($user->name);
                $user = DB::table('users')
                    ->join('user_companies','user_companies.user_id','=','users.id')
                    ->join('companies','user_companies.company_id','=','companies.id')
                    ->where('users.id',$request->user_id)
                    ->selectRaw('CONCAT(users.firstname, " ", users.lastname) as contact_person_name, companies.name as name')
                    ->first();
            }else{
                $user_id = Auth::id();
                $user_type = 1;
                //for mail
                $user = new static;
                $user->contact_person_name = 'Blitznetteam' ;
                $user->name ='Blitznet team';
            }
        }
        //dd($supplier_id,$user_id);
        $token = Str::random(64);
        $inviteBuyer = new InviteBuyer();
        $inviteBuyer->supplier_id = $supplier_id;
        $inviteBuyer->user_id = $user_id;
        $inviteBuyer->company_id = User::find($user_id)->default_company??null;
        $inviteBuyer->role_id = 3 ;
        $inviteBuyer->user_type = $user_type ;
        $inviteBuyer->user_email = $request->user_email;
        $inviteBuyer->token = $token;
        $inviteBuyer->added_by =  Auth::id();
        $inviteBuyer->save();
        $user->buyer_supplier_mail = 3;
        $useremail = [
            'user' => $user,
            'url' => route('signup-supplier-invited', ['email' => Crypt::encryptString($inviteBuyer->user_email),'token' => $token ])
        ];
        try {
            dispatch(new InviteBuyerActivationJob($useremail, $inviteBuyer->user_email));
        } catch (\Exception $e) {
            //echo 'Error - ' . $e;
            dd($e);
        }
        return response()->json(array('success' => true));
    }

    public function inviteSupplierEdit($id){
        $id = Crypt::decrypt($id);
        $inviteBuyer = InviteBuyer::find($id);
        if ($inviteBuyer) {
            //$suppliers = Supplier::all()->where('is_deleted',0);
            $users = DB::table('users')
                ->where('role_id', 2)
                ->where('is_delete', 0);
                //Agent permission
                if (Auth::user()->hasRole('agent')) {
                    $users->where('added_by', Auth::user()->id);
                }
            $users = $users->selectRaw('CONCAT(firstname, " ", lastname) as name, id')
                ->get();

            /**begin: system log**/
            $inviteBuyer->bootSystemView(new InviteBuyer(), 'Invite Supplier', SystemActivity::EDITVIEW, $inviteBuyer->id);
            /**end: system log**/
            return view('/admin/invite_supplier/invite_supplier_edit', ['invitebuyer' => $inviteBuyer, 'users' => $users]);
        } else {
            return redirect('/admin/invite_supplier/invite_supplier_list');
        }

    }

    public function inviteSupplierUpdate(Request $request)
    {
        $supplier_id = null;
        if (auth()->user()->role_id == 1) {
            //no change data
            $checkRecords = InviteBuyer::where([['user_email', '=' ,$request->user_email], ['id',$request->id], ['user_id',$request->user_id]])->count();
            if($checkRecords > 0){
                return response()->json(array('success' => true));
            }else{
                if($request->user_id != 0){
                    $user_id = $request->user_id;
                    $user_type = 2;
                    //for mail
                    $user = DB::table('users')
                        ->join('user_companies','user_companies.user_id','=','users.id')
                        ->join('companies','user_companies.company_id','=','companies.id')
                        ->where('users.id',$request->user_id)
                        ->selectRaw('CONCAT(users.firstname, " ", users.lastname) as contact_person_name, companies.name as name')
                        ->first();
                }else{
                    $user_id = Auth::id();
                    $user_type = 1;
                    //for mail
                    $user = new static;
                    $user->contact_person_name = 'Blitznetteam' ;
                    $user->name ='Blitznet team';
                }

            }
        }
        //dd($supplier_id,$user_id);
        $token = Str::random(64);
        $inviteBuyer = InviteBuyer::find($request->id);
        $inviteBuyer->supplier_id = $supplier_id;
        $inviteBuyer->user_id = $user_id;
        $inviteBuyer->company_id = User::find($user_id)->default_company??null;
        $inviteBuyer->role_id = 3 ;
        $inviteBuyer->user_type = $user_type ;
        $inviteBuyer->user_email = $request->user_email;
        $inviteBuyer->token = $token;
        $inviteBuyer->added_by =  Auth::id();
        $inviteBuyer->save();
        $user->buyer_supplier_mail = 3;
        $useremail = [
            'user' => $user,
            'url' => route('signup-supplier-invited', ['email' => Crypt::encryptString($inviteBuyer->user_email),'token' => $token ])
        ];
        //dd($useremail);
        try {
            dispatch(new InviteBuyerActivationJob($useremail, $inviteBuyer->user_email));
        } catch (\Exception $e) {
            //echo 'Error - ' . $e;
            dd($e);
        }
        return response()->json(array('success' => true));
    }

    /**
     * Supplier excel export
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    function supplierExportExxcel(Request $request)
    {
        ob_end_clean();
        ob_start();

            $condition = [];
            if($request->category_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'categories.id', 'value' => $request->category_ids]);
            }

            if($request->subcategory_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'sub_categories.id', 'value' => $request->subcategory_ids]);
            }

            if($request->product_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'products.id', 'value' => $request->product_ids]);
            }
            if($request->status_ids){
                array_push($condition, ['condition' => 'wherein', 'column_name' => 'suppliers.status', 'value' => $request->status_ids]);
            }

            if($request->start_date && $request->end_date){
                $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d 00:00:00');
                $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d 23:59:59');
                array_push($condition, ['condition' => 'wherebetween', 'column_name' => 'suppliers.created_at', 'value' => [$start_date,  $end_date] ]);
            }

                $select_columns = [];
                $select_columns = ['suppliers.name as company_name','suppliers.email as company_email',DB::raw('CONCAT(suppliers.c_phone_code," ",suppliers.mobile) as mobile'),DB::raw('CONCAT(suppliers.contact_person_name," ",suppliers.contact_person_last_name) as fullname'),'suppliers.contact_person_email',DB::raw('CONCAT(suppliers.cp_phone_code," ",suppliers.contact_person_phone) as contact_mobile'),'available_banks.name as bank_name','available_banks.code','suppliers_banks.bank_account_name as bank_account_name','suppliers_banks.bank_account_number',DB::raw('IF(suppliers.status = "1","Yes", "No") as is_active')];

                $suppliers = Supplier::leftJoin('supplier_products', function ($join) {
                    $join->on('supplier_products.supplier_id', '=', 'suppliers.id');
                })->leftJoin('products', function ($join) {
                    $join->on('supplier_products.product_id', '=', 'products.id');
                })->leftJoin('sub_categories', function ($join) {
                    $join->on('products.subcategory_id', '=', 'sub_categories.id');
                })->leftjoin('suppliers_banks', function ($join) {
                    $join->on('suppliers_banks.supplier_id', '=', 'suppliers.id')
                    ->where('suppliers_banks.is_primary',1);
                })->leftjoin('available_banks', function ($join) {
                    $join->on('suppliers_banks.bank_id', '=', 'available_banks.id');
                })->leftJoin('categories', 'sub_categories.category_id', '=', 'categories.id')
                ->where('suppliers.is_deleted' , 0 );
                if(sizeof($condition) > 0){
                    foreach($condition as $key => $value){
                        if(strtoupper($value['condition']) == "WHEREIN"){
                            $suppliers = $suppliers->whereIn($value['column_name'], $value['value']);
                        }
                        if(strtoupper($value['condition']) == "WHEREBETWEEN"){
                            $suppliers = $suppliers->whereBetween($value['column_name'], $value['value']);
                        }
                    }
                }
        $suppliers = $suppliers->orderBy('suppliers.id','desc')->groupBy('suppliers.id')->get($select_columns);
        return Excel::download(new SupplierExport($suppliers), 'suppliers.xlsx');

        ob_flush();
    }
    /* Added on 25-11-2022 for supplier professional profile start */

    public function updateSupplierBasicDetail(SupplierProfessionalProfileRequest $request){
        $where = ['model_type'=>Supplier::class,'model_id'=>Auth::user()->id];
        $policiesFilePath = '';
        if ($request->file('policiesImage')) {
            $policiesFileName = Str::random(10) . '_' . time() . 'policies_' . $request->file('policiesImage')->getClientOriginalName();
            $policiesFilePath = $request->file('policiesImage')->storeAs('uploads/supplier', $policiesFileName, 'public');
        }
        $update = array(
            'model_type' => Supplier::class,
            'model_id' => Auth::user()->id,
            'company_id' => Auth::user()->default_company,
            'business_description' => $request->business_description,
            'mission' => $request->mission,
            'vision' => $request->vision,
            'history_growth' => $request->history_growth,
            'industry_information' => $request->industry_information,
            'policies' => $request->policies,
            'policies_image' => $policiesFilePath,
            'public_relations' => $request->public_relations,
            'advertising' => $request->advertising,
        );
        $result = CompanyDetails::updateOrCreate($where,$update);
        if($result){
            return response()->json(array('result' => true, 'message' => 'Company Basic Details Update Successfully.'));
        }else{
            return response()->json(array('result' => true, 'message' => 'Failed to update Company Basic Details.'));
        }
    }
    public function updateSupplierCompanyHighlights(SupplierProfessionalProfileRequest $request){
        $id = Crypt::decrypt($request->id);
        //delete company highlight start
        if($request->type == "deleteHighlight"){
            CompanyHighlights::find($id)->forceDelete();
            return response()->json(array('result' => true, 'message' => __('admin.company_achievements_deletd')));
        }
        //delete company highlight end

        $where = array();
        $highlightFilePath = NULL;
        if ($request->file('highlightImage')) {
            $highlightFileName = Str::random(10) . '_' . time() . 'highlight_' . $request->file('highlightImage')->getClientOriginalName();
            $highlightFilePath = $request->file('highlightImage')->storeAs('uploads/supplier', $highlightFileName, 'public');
        }else{
            if($id > 0){
                $highlightFilePath = $request->oldhighlightImage;
            }
        }
        //$where = ['model_type'=>Supplier::class,'model_id'=>Auth::user()->id];
        $dataArr = array(
            'user_id' => Auth::user()->id,
            'model_type' => Supplier::class,
            'model_id' => getSupplierByLoginId(Auth::user()->id),
            'company_id' => Auth::user()->default_company,
            'category' => $request->category,
            'name' => $request->name,
            'number' => $request->number,
            'image' => $highlightFilePath
        );
        if($id > 0){
            $result = CompanyHighlights::where('id',$id)->update($dataArr);
        }else{
            $result = CompanyHighlights::insert($dataArr);
        }
        if($result){
            return response()->json(array('result' => true, 'message' => 'Company Highlights Added Successfully.'));
        }else{
            return response()->json(array('result' => true, 'message' => 'Failed to add Company Highlights.'));
        }
    }
    public function getCompanyHighlights(Request $request){
        $id = $request->id;
        if(!empty($id)){
            $data = CompanyHighlights::find($id);
            $data['highlight_image'] = '';

            if(!empty($data->image)){
                $highlight_image_name = substr(Str::substr($data->image, stripos($data->image, 'highlight_') + 10), 0, -4);
                $data['highlight_image'] = $highlight_image_name;
            }
            return json_decode($data);
        }
        if ($request->ajax()) {
            $data = CompanyHighlights::select('id','category','name','number','image')->where('is_deleted',0)->orderBy('id','DESC')->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" class="show-icon ps-2 editHighlight" data-id="'.$row->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#Highlights"><i class="fa fa-edit"></i></a>';
                    $btn .= '<a href="javascript:void(0)" id="delete" class="show-icon ps-2 deleteHighlight" data-id="'.$row->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function deleteOrDownloadSupplierImages(Request $request){
        $imageType = $request->imageType;
        if($imageType == "highLights") {
            $id = $request->id;
            $data = CompanyHighlights::find($id);
            $fileName = $data->image;
        }else if($imageType == "coreTeam"){
            $id = $request->id;
            $data = CompanyMembers::find($id);
            $fileName = $data->image;
        }else if($imageType == "testimonial"){
            $id = $request->id;
            $data = CompanyMembers::find($id);
            $fileName = $data->image;
        }else if($imageType == "partner"){
            $id = $request->id;
            $data = CompanyMembers::find($id);
            $fileName = $data->image;
        }else if($imageType == "portfolio"){
            $id = $request->id;
            $data = CompanyMembers::find($id);
            $fileName = $data->image;
        }

        if($data) {
            if($request->type == "download"){
                ob_end_clean();
                $headers = array('Content-Type: image/*, application/pdf');
                return Storage::download('/public/' . $fileName, '', $headers);
            }else{
                $data->image = NULL;
                $data->save();
                Storage::delete('/public/' . $fileName);
                return true;
            }
        }


    }
    public function getCompanyMembers(Request $request){
        //dd($request->all());
        $id = $request->id;
        if($request->company_user_type == 1){
            $editClass = 'editCoreTeam';
            $deleteClass = 'deleteCoreTeam';
        }else if($request->company_user_type == 2){
            $editClass = 'editTestimonial';
            $deleteClass = 'deleteTestimonial';
        }else if($request->company_user_type == 3){
            $editClass = 'editPartner';
            $deleteClass = 'deletePartner';
        }else if($request->company_user_type == 4){
            $editClass = 'editPortfolio';
            $deleteClass = 'deletePortfolio';
        }
        if(!empty($id)){
            $data = CompanyMembers::find($id);
            $data['member_image'] = '';

            if(!empty($data->image)){
                if($data->company_user_type_id == 1){
                    $member_image_name = substr(Str::substr($data->image, stripos($data->image, 'coreteam_') + 9), 0, -4);
                }elseif ($data->company_user_type_id == 2){
                    $member_image_name = substr(Str::substr($data->image, stripos($data->image, 'testimonial_') + 12), 0, -4);
                }elseif ($data->company_user_type_id == 3){
                    $member_image_name = substr(Str::substr($data->image, stripos($data->image, 'partner_') + 8), 0, -4);
                }elseif ($data->company_user_type_id == 4){
                    $member_image_name = substr(Str::substr($data->image, stripos($data->image, 'portfolio_') + 10), 0, -4);
                }

                $data['member_image'] = $member_image_name;
            }
            return json_decode($data);
        }
        if ($request->ajax()) {
            $data = CompanyMembers::select('id','salutation','firstname','lastname','email','phone','designation','position','sector','registration_NIB','portfolio_type','company_name','image','description')->where('is_deleted',0)->where('company_user_type_id',$request->company_user_type)->where('model_id',$request->supplier_id)->orderBy('id','DESC')->get();
             return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row) use ($editClass,$deleteClass){
                    $btn = '<a href="javascript:void(0)" class="show-icon ps-2 '.$editClass.'" data-id="'.Crypt::encrypt($row->id).'" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal"><i class="fa fa-edit"></i></a>';
                    $btn .= '<a href="javascript:void(0)" id="delete" class="show-icon ps-2 '.$deleteClass.'" data-id="'.Crypt::encrypt($row->id).'" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function updateSupplierCompanyMembers(SupplierProfessionalProfileRequest $request){
        $id = Crypt::decrypt($request->id);
        //delete company members start
        if($request->type == "deleteCompanyMembers"){
            $msg = '';
            if($request->company_user_type == 1){
                $msg = __('admin.core_team_deleted_success');
            }else if($request->company_user_type == 2){
                $msg = __('admin.testimonial_deleted_success');
            }else if($request->company_user_type == 3){
                $msg = __('admin.partner_deleted_success');
            }else if($request->company_user_type == 4){
                $msg = __('admin.portfolio_deleted_success');
            }
            CompanyMembers::find($id)->forceDelete();
            return response()->json(array('success' => true, 'message' => $msg));
        }
        //delete company members end

        $where = array();
        $memberFilePath = NULL;
        if ($request->file('coreTeamImage')) {
            $memberFileName = Str::random(10) . '_' . time() . 'coreteam_' . $request->file('coreTeamImage')->getClientOriginalName();
            $memberFilePath = $request->file('coreTeamImage')->storeAs('uploads/supplier', $memberFileName, 'public');
        }else if ($request->file('testimonialImage')) {
            $memberFileName = Str::random(10) . '_' . time() . 'testimonial_' . $request->file('testimonialImage')->getClientOriginalName();
            $memberFilePath = $request->file('testimonialImage')->storeAs('uploads/supplier', $memberFileName, 'public');
        }else if ($request->file('partnerImage')) {
            $memberFileName = Str::random(10) . '_' . time() . 'partner_' . $request->file('partnerImage')->getClientOriginalName();
            $memberFilePath = $request->file('partnerImage')->storeAs('uploads/supplier', $memberFileName, 'public');
        }else if ($request->file('portfolioImage')) {
            $memberFileName = Str::random(10) . '_' . time() . 'portfolio_' . $request->file('portfolioImage')->getClientOriginalName();
            $memberFilePath = $request->file('portfolioImage')->storeAs('uploads/supplier', $memberFileName, 'public');
        }else{
            if($id > 0){
                $memberFilePath = $request->oldmemberImage;
            }
        }
        $dataArr = array(
            'user_id' => Auth::user()->id,
            'model_type' => Supplier::class,
            'model_id' => getSupplierByLoginId(Auth::user()->id),
            'company_id' => Auth::user()->default_company,
            'company_user_type_id' => $request->company_user_type_id,
            'salutation' => $request->salutation,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'country_phone_code' => $request->country_phone_code,
            'phone' => $request->phone,
            'designation' => $request->designation,
            'position' => $request->position,
            'sector' => $request->sector,
            'registration_NIB' => $request->registration_NIB,
            'portfolio_type' => $request->portfolio_type,
            'company_name' => $request->company_name,
            'quote' => $request->quote,
            'image' => $memberFilePath,
            'description' => strip_tags($request->description),
        );
        if($id > 0){
            $result = CompanyMembers::where('id',$id)->update($dataArr);
            $message = $request->typeName." Updated Successfully.";
        }else{
            $result = CompanyMembers::insert($dataArr);
            $message = $request->typeName." Added Successfully.";
        }
        if($result){
            return response()->json(array('result' => true, 'message' => $message));
        }else{
            return response()->json(array('result' => true, 'message' => 'Failed to add Company '.$request->typeName.'.'));
        }
    }
    /** Admin supplier edit */
    public function supplierEdit($id) {
        try {
            $id = Crypt::decrypt($id);
            $supplier = Supplier::with('user')->where('id',$id)->first();

            $banks = AvailableBank::where('can_disburse', 1)->get()->toArray();
            $supplierBanks = SuppliersBank::where('supplier_id',$supplier->id)->get();
            if(!empty($supplier->user->id)){
                $companyDetails = CompanyDetails::where('user_id',$supplier->user->id)->first();
                $supplierAddress = CompanyAddress::where('user_id',$supplier->user->id)->first();
            }else{
                $companyDetails = CompanyDetails::where('model_id',$id)->first();
                $supplierAddress = CompanyAddress::where('model_id',$id)->first();
            }
            $category = Category::where('is_deleted', 0)->where('status', 1)->get();

            $subCategory = SubCategory::all()->where('is_deleted', 0);
            $brandData = Brand::where('is_deleted', 0)->get()->toArray();
            $gradeData = Grade::where('is_deleted', 0)->get()->toArray();
            $unit = Unit::where('is_deleted', 0)->get();
            $products = Product::where('is_deleted', 0)->get();
            $check_as_buyer = UserSupplier::where('supplier_id',$id)->get()->count();

            $supplierProduct = DB::table('supplier_products')
                ->join('products', 'supplier_products.product_id', '=', 'products.id')
                ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
                ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
                ->where('supplier_products.supplier_id', $id)
                ->where('supplier_products.is_deleted', 0)
                ->get(['products.name as product_name', 'products.description as product_description', 'categories.name as category_name', 'sub_categories.name as sub_category_name', 'supplier_products.id as supplier_products_id']);
            $brand = implode(',', array_unique(array_column($brandData, 'name')));
            $grade = implode(',', array_unique(array_column($gradeData, 'name')));

            /** Supplier Dealing With category*/
            $supplierId = $supplier->id;
            $dealing_category = Supplier::dealWithSubCategoriesTag($supplierId);
            /** Supplier Dealing With category */

            /**begin: system log**/
            $supplier->bootSystemView(new Supplier(), 'Supplier', SystemActivity::EDITVIEW, $supplier->id);
            /**end: system log**/
            //pkp value store login start
                $pkp_file_name = $pkp_file_filename = $pkpFileTitle="";
                if($supplier->company_type==1 && $supplier->pkp_file){
                    $pkpFileTitle = Str::substr($supplier->pkp_file, stripos($supplier->pkp_file, "pkp_file_") + 10);
                    $extension_pkp_file = getFileExtension($pkpFileTitle);
                    $pkp_file_filename = getFileName($pkpFileTitle);
                    if(strlen($pkp_file_filename) > 10){
                        $pkp_file_name = substr($pkp_file_filename,0,10).'...'.$extension_pkp_file;
                    } else {
                        $pkp_file_name = $pkp_file_filename.$extension_pkp_file;
                    }
                }
                $ViewData['banks']=$banks;
                $ViewData['supplierBanks']=$supplierBanks;
                $ViewData['supplier']=$supplier;
                $supplierAddress = CompanyAddress::where('model_id',$id)->get()->toArray();
                $slug_prefix = Settings::where('key','slug_prefix')->first()->value;

            return view('admin/supplier/profile/supplierProfessionalProfile', ['banks'=>$banks,'supplierBanks'=>$supplierBanks,'supplier' => $supplier, 'category' => $category, 'subCategory' => json_encode($dealing_category), 'brand' => $brand, 'grade' => $grade, 'unit' => $unit, 'products' => $products, 'supplierProduct' => $supplierProduct, 'asBuyer'=> $check_as_buyer, 'companyDetails'=>$companyDetails,'pkp_file_name'=>$pkp_file_name,'pkp_file_filename'=>$pkp_file_filename,'pkpFileTitle'=>$pkpFileTitle,'supplierAddress'=>$supplierAddress,'slug_prefix'=>$slug_prefix,'categories'=>json_encode($dealing_category)]);

        } catch(\Exception $exception) {
            Log::critical('Code - 400 | ErrorCode:B033 - Admin Supplier Edit');
            abort('404');
        }
    }
    /**
     * Supplier Create
     *
     */
    function supplierCreate() {
        $category = Category::all()->where('is_deleted', 0);
        $subCategory = SubCategory::all()->where('is_deleted', 0);
        $banks = getAllRecordsByCondition('available_banks',['can_disburse'=>1],'id,name,code,logo');
        $brandData = Brand::select('name')->where('is_deleted', 0)->get()->toArray();
        $gradeData = Grade::select('name')->where('is_deleted', 0)->get()->toArray();
        $unit = Unit::all()->where('is_deleted', 0);
        $products = Product::all()->where('is_deleted', 0);
        $brand = implode(',', array_unique(array_column($brandData, 'name')));
        $grade = implode(',', array_unique(array_column($gradeData, 'name')));
        $slug_prefix = Settings::where('key','slug_prefix')->first()->value;
        /**begin: system log**/
        Supplier::bootSystemView(new Supplier(), 'Supplier', SystemActivity::ADDVIEW);
        /**end: system log**/
        return view('admin/supplier/profile/addSupplierProfessionalProfile',['category' => $category, 'subCategory' => $subCategory, 'banks' => $banks, 'brand' => $brand, 'grade' => $grade, 'unit' => $unit, 'products' => $products, 'supplier' => null,'slug_prefix'=>$slug_prefix]);
    }

    /**
     * Supplier Gallery Image upload
     * @param Request $request
     */
    function supplierGalleryImagesUpdate(Request $request) {
        if($request->hasfile('group_image'))
        {
            $groupImageFilePath = '';
            foreach($request->file('group_image') as $key => $file)
            {
                $groupFileName = Str::random(10) . '_' . time() . '_image_' . $request->file('group_image')[$key]->getClientOriginalName();
                $groupImageFilePath = $request->file('group_image')[$key]->storeAs('uploads/supplier_images', $groupFileName, 'public');

                $insert[$key]['supplier_id'] = $request->id;
                $insert[$key]['image'] = $groupImageFilePath;
                $insert[$key]['added_by'] = Auth::id();
            }
        }
        //dd($insert);
        SupplierGallery::insert($insert);
        return response()->json(array('success' => true,'supplierId' => $request->id));
    }
    public function getSupplierImages($id){
        $groupImages = SupplierGallery::where('supplier_id', $id)
            ->orderBy('id', 'asc')
            ->get();
        $storeImageCount = $groupImages->count();
        return response()->json(array('success' => true, 'groupImages' => $groupImages,'storeImageCount'=>$storeImageCount));

    }
    public function galleryImageDelete(Request $request){
        $id = $request->id;
        $image = SupplierGallery::select('image','supplier_id')->where('id', $id)->first();
        if(!empty($image->image)){
             Storage::delete('/public/' . $image->image);
            SupplierGallery::where('id', $id)->forceDelete();
        }

        return response()->json(array('success' => true));
    }

    /**
     * Supplier Add
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storeSupplier(AdminCompanyDetailsRequest $request)
    {
        try {

            $supplier = new Supplier;
            $supplier = $this->uploadSupplierFiles($request,$supplier);
            $supplier->name = $request->name;
            $supplier->email = $request->email;
            $supplier->c_phone_code = $request->c_phone_code?'+'.$request->c_phone_code:'';
            $supplier->mobile = $request->mobile;
            $supplier->website = $request->website;
            $supplier->nib = $request->nib;
            $supplier->npwp = $request->npwp;
            $supplier->group_margin = $request->group_margin;
            $supplier->salutation = $request->salutation;
            $supplier->contact_person_name = $request->contactPersonName;
            $supplier->contact_person_last_name = $request->contactPersonLastName;
            $supplier->contact_person_email = trim($request->contactPersonEmail);
            $supplier->cp_phone_code = $request->cp_phone_code?'+'.$request->cp_phone_code:'';
            $supplier->contact_person_phone = $request->contactPersonMobile;
            $supplier->alternate_email = $request->alternate_email?trim($request->alternate_email):'';
            $supplier->licence = $request->licence;
            $supplier->company_alternative_phone_code = $request->company_alternative_phone_code?'+'.$request->company_alternative_phone_code:'';
            $supplier->company_alternative_phone = $request->company_alternative_phone;
            $supplier->facebook = $request->facebook;
            $supplier->twitter = $request->twitter;
            $supplier->linkedin = $request->linkedin;
            $supplier->youtube = $request->youtube;
            $supplier->instagram = $request->instagram;
            $supplier->profile_username = Str::replace(' ','-',$request->profile_username);
            $supplier->established_date = !empty($request->established_date) ? \Carbon\Carbon::createFromFormat('d-m-Y', $request->established_date)->format('Y-m-d') : '';
            $supplier->company_type = $request->companyType;
            $supplier->added_by =  Auth::id();
            $supplier->save();


            /**begin: Update supplier company location address and remove previous added addresss*/
            CompanyAddress::where('model_id',$supplier->id)->delete();
            if (!empty($request->address)) {
                foreach ($request->address as $key => $address) {
                    if ($request->address[$key]) {
                        $supplierCompany = new CompanyAddress;
                        $supplierCompany->model_type = Supplier::class;
                        $supplierCompany->model_id = $supplier->id;
                        $supplierCompany->address = $request->address[$key];
                        $supplierCompany->company_id = null;
                        $supplierCompany->is_deleted = 0;
                        $supplierCompany->save();

                        /**begin: system log**/
                        $supplierCompany->bootSystemActivities();
                        /**end: system log**/
                    }
                }
            }
            /**end: Update supplier company location address and remove previous added addresss*/

            /**begin: system log**/
            $supplier->bootSystemActivities();
            /**end: system log**/
            $request->session()->flash('status', 'Supllier Added Successfully');

            return response()->json(array('success' => true, 'supplierId' => $supplier->id));

       } catch (\Exception $exception) {

            return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')],500);
        }

        return response()->json(['success' => false, 'message' => __('admin.something_went_wrong')],400);
    }

    /**
     * Upload supplier files
     *
     * @param $request
     * @param $supplier
     * @return mixed
     */
    public function uploadSupplierFiles($request, $supplier)
    {
        if ($request->file('logo')) {
            $supplier->logo = $supplier->uploadOne($request->file('logo'),'uploads/supplier','public','logo_');
        }

        if ($request->file('catalog')) {
            $supplier->catalog = $supplier->uploadOne($request->file('catalog'),'uploads/supplier','public','catalog_');

        }

        if ($request->file('pricing')) {
            $supplier->pricing = $supplier->uploadOne($request->file('pricing'),'uploads/supplier','public','pricing_');

        }

        if ($request->file('product')) {
            $supplier->product = $supplier->uploadOne($request->file('product'),'uploads/supplier','public','product_');

        }

        if ($request->file('commercialCondition')) {
            $supplier->commercialCondition = $supplier->uploadOne($request->file('commercialCondition'),'uploads/supplier','public','termsconditions_file_');

        }

        if ($request->file('nib_file')) {
            $supplier->nib_file = $supplier->uploadOne($request->file('nib_file'),'uploads/supplier','public','nib_file_');
        }

        if ($request->file('npwp_file')) {
            $supplier->npwp_file = $supplier->uploadOne($request->file('npwp_file'),'uploads/supplier','public','npwp_file_');

        }

        if ($request->companyType == 1  && $request->file('pkp_file')) {
            $supplier->pkp_file = $supplier->uploadOne($request->file('pkp_file'),'uploads/supplier','public','pkp_file_');

        }

        return $supplier;

    }

    //Supplier can download document from own account
    public function downloadSupplierDoc($supplierDoc) {
        $filepath = public_path('storage/supplier_tutorial_docs/'.$supplierDoc);
        return Response::download($filepath);
    }

    /**
     * Supplier product tagging (Ronak M - 16/10/2023)
    **/
    public function supplierProductTagging(Request $request) {

        //Check product is already exist in supplier_products table
        $supplierProdExists = SupplierProduct::where(['supplier_id' => $request->supplier_id, 'product_id' => $request->rfqProdData['product_id']])->first();

        if(empty($supplierProdExists)) {

            //check this sub-category already added or new added //send mail if new added
            $supplierDetails = $categoryArray =  [];
            $categoryId = $request->rfqProdData['sub_category_id'];

            $categoryArray[0] = $categoryId;
            $supplierDetails = Supplier::where(['is_deleted'=> 0, 'id'=>$request->supplier_id])->select('id','contact_person_name','contact_person_last_name','contact_person_email')->first();
            $oldCategoryArray = SupplierDealWithCategory::where(['supplier_id'=>$request->supplier_id,'deleted_at'=>null])->pluck('sub_category_id')->toArray();
            // check this supplier deal in this categories
            $supplierNewaddCategoryList = SupplierProduct::checkSupplierWsieCategoryNewadded($request->supplier_id,$categoryArray,$oldCategoryArray);
            if(!empty($supplierNewaddCategoryList[0]['subcat_name']) && !empty($supplierDetails)){
                dispatch(new SupplierNotifyCategoryWiseRfqlistJob($supplierNewaddCategoryList, $supplierDetails));
            }
            $checkCategoryExist = SupplierDealWithCategory::where(['supplier_id'=>$request->supplier_id, 'sub_category_id'=>$categoryId, 'deleted_at'=>null])->count();
            if($checkCategoryExist == 0){
                $data = array('category_id'=>$request->rfqProdData['category_id'],'sub_category_id'=>$request->rfqProdData['sub_category_id'],'supplier_id'=>$request->supplier_id,'created_at'=>now(),'updated_at'=>now());
                SupplierDealWithCategory::createSupplierDealWithCategory($data);
            }
            //End mail

            //Add product in supplier products
            $supplierProduct = new SupplierProduct;
            $supplierProduct->product_id = $request->rfqProdData['product_id'];
            $supplierProduct->description = $request->rfqProdData['product_description'];
            $supplierProduct->supplier_id = $request->supplier_id;
            $supplierProduct->price = 1;
            $supplierProduct->min_quantity = 1;
            $supplierProduct->max_quantity = $request->rfqProdData['quantity'];
            $supplierProduct->quantity_unit_id = $request->rfqProdData['unit_id'];
            $supplierProduct->product_ref = null;
            $supplierProduct->added_by =  Auth::id();
            $supplierProduct->save();

            /**
             * Add supplier product discount range
             * Static value will be inserted for min_qty, max_qty, discount and discounted_price
            **/
            $discount_options[] = array(
                'supplier_product_id' => $supplierProduct->id,
                'product_id' => $request->rfqProdData['product_id'],
                'supplier_id' => $request->supplier_id,
                'min_qty' => 1,
                'max_qty' => 2,
                'unit_id' => $request->rfqProdData['unit_id'],
                'discount' => 1,
                'discounted_price' => 1,
            );
            $supplier_discount_option = SupplierProductDiscountRange::insert($discount_options);

            return response()->json(array('success' => true, 'class' => 'success', 'message' => __('admin.supplier_product_added_successfully')));

        } else {
            return response()->json(array('success' => true, 'class' => 'error', 'message' => __('admin.product_exist')));
        }

    }

}
