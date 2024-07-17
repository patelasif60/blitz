<?php

namespace App\Http\Controllers;

use App\Models\SupplierProductImage;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\Brand;
use App\Models\Grade;
use App\Models\ProductBrand;
use App\Models\RfqProduct;
use App\Models\SupplierProductBrand;
use App\Models\SupplierProductGrade;
use App\Models\SystemActivity;
use App\Models\Unit;
use App\Models\UserSupplier;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use URL;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create products|edit products|delete products|publish products|unpublish products', ['only' => ['list']]);
        $this->middleware('permission:create products', ['only' => ['productAdd', 'create']]);
        $this->middleware('permission:edit products', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete products', ['only' => ['delete']]);
    }

    public function list(Request $request){
        $authRoleId = auth()->user()->role_id;
        if($authRoleId==3){
            return redirect('/admin/productList');
        }

        if($request->ajax() && $request->get('draw')) {
            $start = $request->get("start");
            $length = $request->get("length");
            $search = (!empty($request->get('search')) ? $request->get('search')['value'] : '');
            $column_order    = $request->get('column_order');
            $order_type     = $request->get('order_type');
            DB::enableQueryLog();
          
                
            $query = Product::with(['subcategory','subcategory.category'])->where('products.is_deleted', 0);

            //Agent category permission
            if (Auth::user()->hasRole('agent')) {
                $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();
                $query = Product::with(['subcategory','subcategory.category'])->where('products.is_deleted', 0);
                $query->whereHas('subcategory.category', function ($query) use ($assignedCategory) {
                     $query->whereIn('category_id', $assignedCategory);
                });
            }

            // Ordering
            if($column_order == "sub_category_name"){
                $query = $query->orderBy(subcategory::select('name')
                    ->whereColumn('sub_categories.id', 'products.subcategory_id')->limit(1),$order_type );
            }else if($column_order == "category_name"){
                $query = $query->orderBy(subcategory::join('categories', 'categories.id', '=', 'sub_categories.category_id')->select('categories.name')->whereColumn('sub_categories.id','products.subcategory_id')->limit(1),$order_type);
            }else{
                $query->orderBy($column_order, $order_type);
            }

            // // Searching
            if ($search != "") {
                $query=$query->where(function($q) use($search){
                    $q->where('name','like','%'.$search.'%')
                        ->orWhere(function($query) use($search){
                            $query->orWhereHas('subcategory.category',function ($q) use($search){
                                $q->where('name','like','%'.$search.'%');
                            })
                            ->orWhereHas('subcategory',function ($q) use($search){
                                $q->where('name','like','%'.$search.'%');
                            });
                        });
                });
            }
            
            $setTotalRecords = $query->count();
            $setFilteredRecords = $query->count();
            $products = ($length == -1) ? $query->get() : $query->skip($start)->take($length)->get();

            foreach ($products as $product) {
                $product->canBeDeleted = SupplierProduct::where(['product_id' => $product->id, 'is_deleted' => 0])->count() > 0 ? 0 : 1;
                $product->category_name = !empty($product->subcategory->category) ? $product->subcategory->category->name : '-';
                $product->sub_category_name = !empty($product->subcategory) ? $product->subcategory->name : '-';
                $product->addedBy = !empty($product->trackAddData) ? $product->trackAddData->full_name : '-';
                $product->updatedBy = !empty($product->trackUpdateData) ? $product->trackUpdateData->full_name : '-';
            }
            
            $data = $products;
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                        $btn = '<a href="'.route("product-edit", ["id" => Crypt::encrypt($row->id)]).'"
                        class="show-icon"  data-toggle="tooltip" ata-plaement="top" title="'.__('admin.edit').'">
                        <i class="fa fa-edit"></i>
                        </a>';
                        $btn .= '<a href="javascript:void(0)"
                        data-delete-status="'.$row->canBeDeleted.'"
                        id="deleteProduct_'.$row->id.'"
                        class="ps-2 deleteProduct show-icon" data-toggle="tooltip" ata-placement="top" title="'.__('admin.delete').'">
                        <i class="fa fa-trash"></i>
                        </a>';
                    return $btn;
                })
                ->setOffset($start)
                ->setTotalRecords($setTotalRecords)
                ->setFilteredRecords($setFilteredRecords)
                ->rawColumns(['action'])
                ->make(true);
        }
        /**begin: system log**/
        Product::bootSystemView(new Product());
        /**end:  system log**/
        return view('admin.products',compact('authRoleId'));
    }
    
    function productAdd()
    {
        $categories = Category::all()->where('is_deleted',0);
        $suppliers = Supplier::all()->where('is_deleted',0);
        //Agent category permissions
        if (Auth::user()->hasRole('agent')) {

            $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();

            $categories = Category::all()->whereIn('id', $assignedCategory)->where('is_deleted',0);

            $assignedSubCategory = SubCategory::whereIn('category_id', $assignedCategory)->pluck('id')->toArray();

            $suppliers = DB::table('suppliers')
                ->leftJoin('supplier_products', 'suppliers.id', '=', 'supplier_products.supplier_id')
                ->leftJoin('products', 'supplier_products.product_id', '=', 'products.id')
                ->whereIn('products.subcategory_id', $assignedSubCategory)
                ->distinct()
                ->selectRaw('suppliers.*')
                ->where('suppliers.is_deleted',0)
                ->get();

        }
        $brands = Brand::all()->where('is_deleted', 0);
        /**begin: system log**/
        Product::bootSystemView(new Product(), 'Product', SystemActivity::ADDVIEW);
        /**end: system log**/
        return view('admin/productAdd', ['categories' => $categories, 'suppliers' => $suppliers, 'brands' => $brands]);
    }
    function create(Request $request)
    {
        if(isset($request->name)) {
            $duplicateData = checkDuplication('products',trim($request->name),$request->subCategory);
        }
        if($duplicateData == true) {
            $product = new Product;
            $product->subcategory_id = $request->subCategory;
            $product->name = $request->name;
            $product->description = $request->description;
            // $product->price = $request->price;
            // $product->min_quantity = $request->min_quantity;
            $product->status = $request->status;
            $product->added_by =  Auth::id();

            $productData = $product->save();
            if ($productData) {
                if(isset($request['brands'])){
                    if(count($request['brands']) > 0){
                        foreach ($request['brands'] as $brand) {
                            $productBrand = new ProductBrand;
                            $productBrand->product_id = $product->id;
                            $productBrand->brand_id = $brand;
                            $productBrand->save();
                        }
                    }
                }
                if(isset($request['suppliers'])){
                    if(count($request->suppliers)>0){
                        foreach($request->suppliers as $supplier){
                            if(empty($supplier)){
                                continue;
                            }
                            $supplierProduct = new SupplierProduct;
                            $supplierProduct->product_id = $product->id;
                            $supplierProduct->supplier_id = $supplier;
                            $supplierProduct->description = $request->description;
                            $supplierProduct->price = 0;
                            $supplierProduct->min_quantity = 0;
                            $supplierProduct->quantity_unit_id = NULL;
                            $supplierProduct->status = $request->status;
                            $supplierProduct->discount = 0;
                            $supplierProduct->discounted_price = 0;
                            $supplierProduct->product_ref = NULL;
                            $supplierProductData = $supplierProduct->save();
                        }
                    }
                }
            }

            /**begin: system log**/
            Product::bootSystemActivities();
            /**end: system log**/
            return response()->json(array('success' => true));
        } else {
            return response()->json(array('success' => false));
        }
        return redirect('/admin/products');
    }
    function edit($id)
    {
        $id = Crypt::decrypt($id);
        $product_id = Product::find($id);
        if(auth()->user()->role_id == 3) {
            $product = DB::table('supplier_products')
            ->join('products', 'supplier_products.product_id', '=', 'products.id')
            ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->where('supplier_products.id', $id)
            ->where('supplier_products.is_deleted', 0)
            ->get(['supplier_products.*', 'products.name as name', 'supplier_products.description as description', 'sub_categories.id as subcategory_id', 'categories.id as category_id']);
        } else {
		    $product = DB::table('products')
			->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
			->join('categories', 'sub_categories.category_id', '=', 'categories.id');
		    //Agent category permissions
            if (Auth::user()->hasRole('agent')) {

                $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();

                $product->whereIn('categories.id', $assignedCategory);

            }
            $product = $product->where('products.id', $id)
			->get(['products.*', 'categories.id as category_id', 'sub_categories.id as sub_category_id']);
			//echo "<pre>"; print_r( $product); exit;
        }
        //dd($product->toArray());
        if ($product) {
			$subCategories = SubCategory::all()->where('category_id',$product[0]->category_id);
			$categories = Category::all()->where('is_deleted',0);
            //Agent category permissions
            if (Auth::user()->hasRole('agent')) {

                $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();

                $categories = Category::all()->whereIn('id', $assignedCategory)->where('is_deleted',0);

            }
			$brands = Brand::all()->where('is_deleted', 0);
            $productBrands = DB::table('product_brands')
                ->where('product_id', $id)
                ->get('brand_id');
			$productBrandArray = array();
            foreach ($productBrands as $value)
                $productBrandArray[] = $value->brand_id;

			$suppliers = Supplier::all()->where('is_deleted',0);
			$supplier_product = DB::table('supplier_products')
				->where('product_id', $id)
				->get(['supplier_products.supplier_id']);

            $unit = Unit::all()->where('is_deleted', 0);
			$sproduct = array();
            foreach ($supplier_product as $suplierProduct)
                $sproduct[] = $suplierProduct->supplier_id;

            /**begin: system log**/
            Product::bootSystemView(new Product(), 'Product', SystemActivity::EDITVIEW, $product_id->id);
            /**end: system log**/
            return view('/admin/productEdit', ['product'=> $product, 'supplier_product' => $sproduct, 'categories' => $categories, 'subCategories' => $subCategories, 'suppliers' => $suppliers, 'brands' => $brands,'unit' => $unit, 'productBrands' => $productBrandArray]);
			//return view('/admin/productEdit', ['product'=> $product, 'categories' => $categories, 'subCategories' => $subCategories, 'brands' => $brands, 'productBrands' => $productBrandArray]);
        } else {
            return redirect('/admin/products');
        }
    }

    function update(Request $request)
    {
		 if(isset($request->name)) {
             $duplicateData = checkDuplication('products',trim($request->name),$request->subCategory);
         }
        //check if product exist
        $productExist = Product::where('name', trim($request->name))->where('subcategory_id',$request->subCategory)->whereNotIn('id', [$request->id])->count();
        $duplicateData = $productExist>0 ? false : true;

        if($duplicateData == true) {
            $product = Product::find($request->id);
            $product->subcategory_id = $request->subCategory;
            $product->name = $request->name;
            $product->description = $request->description;
            //$product->price = $request->price;
            // $product->min_quantity = $request->min_quantity;
            $product->status = $request->status;
            $product->updated_by =  Auth::id();

            $productData = $product->save();
            if ($productData) {
                $res = ProductBrand::where('product_id', $product->id)->delete();
                if(isset($request['brands'])){
                    if(count($request['brands']) > 0){
                        foreach ($request['brands'] as $brand) {
                            $productBrand = new ProductBrand;
                            $productBrand->product_id = $product->id;
                            $productBrand->brand_id = $brand;
                            $productBrand->save();
                        }
                    }
                }
                if(isset($request['suppliers'])){
                    if(count($request->suppliers)>0){
                        $oldSupplierDetail = SupplierProduct::where('product_id', $product->id)->get();
                        if(count($oldSupplierDetail) > 0){
                            foreach($oldSupplierDetail as $oldSupplier){
                                if (!in_array($oldSupplier->supplier_id, $request->suppliers))
                                {
                                    $res = SupplierProduct::where('product_id', $product->id)->where('supplier_id', $oldSupplier->supplier_id)->delete();
                                }
                            }
                        }
                        foreach($request->suppliers as $supplier){
                            if(empty($supplier)){
                                continue;
                            }
                            $supplierDetail = SupplierProduct::where('product_id', $product->id)->where('supplier_id', $supplier)->get();
                            if(count($supplierDetail) == 0){
                                $supplierProduct = new SupplierProduct;
                                $supplierProduct->product_id = $product->id;
                                $supplierProduct->supplier_id = $supplier;
                                $supplierProduct->description = $request->description;
                                $supplierProduct->price = 0;
                                $supplierProduct->min_quantity = 0;
                                $supplierProduct->quantity_unit_id = NULL;
                                $supplierProduct->status = $request->status;
                                $supplierProduct->discount = 0;
                                $supplierProduct->discounted_price = 0;
                                $supplierProduct->product_ref = NULL;
                                $supplierProductData = $supplierProduct->save();
                            }
                        }
                    }
                } else{
                    SupplierProduct::where('product_id', $product->id)->delete();
                }
            }
             /**begin: system log**/
            Product::bootSystemActivities();
            /**end: system log**/
            return response()->json(array('success' => true));
         } else {
             return response()->json(array('success' => false));
         }
        return redirect('/admin/products');
    }

    function delete(Request $request)
    {
        //dd($request->id);
        $product = Product::find($request->id);
        $product->is_deleted = 1;
        $product->deleted_by =  Auth::id();
        $product->save();
        $product->delete();
        /**begin: system log**/
        Product::bootSystemActivities();
        /**end: system log**/
        return $request->id;
    }

    function getProductImage($id){
        $image = SupplierProductImage::where('supplier_product_id', $id)->first();
        if (!empty($image)){
            $fileName = Str::substr($image->image, stripos($image->image, "productImages_") + 14);
            $download_function = "downloaproductdimg(".$image->supplier_product_id.",'productImages',"."'".$fileName."')";
            $icon = URL::asset('assets/icons/times-circle copy.png');
            $html = '<span class="ms-2"><a href="javascript:void(0);" id="productImagesFileDownload" onclick="'.$download_function.'"  title="'.$fileName.'" style="text-decoration: none;">'.$fileName.'</a></span><span class="removeProductFile" id="productImagesFile" data-id="'.$image->id.'" file-path="'.$image->image.'" data-name="productImages"><a href="#" title="Remove Product Images" style="text-decoration: none;"> <img src="'.$icon.'" alt="CLose button" class="ms-2"></a></span><span class="ms-2"><a class="productImagesFile" href="javascript:void(0);" title="Download Product Images" onclick="'.$download_function.'" style="text-decoration: none;"><i class="fa fa-cloud-download"></i></a></span>';
            return response()->json(array('success' => true, 'activityhtml' => $html));
        } else {
            return response()->json(array('success' => false, 'activityhtml' => ''));
        }

    }

    function downloadSupplierProductImageAdmin(Request $request){
        $image = SupplierProductImage::where('supplier_product_id', $request->id)->pluck('image')->first();
        if (!empty($image)){
            ob_end_clean();
            $headers = array('Content-Type: image/*');
            return Storage::download('/public/' . $image, '', $headers);
        }
        return response()->json(array('success' => false));
    }

    function productImageDelete(Request $request){
        $image = SupplierProductImage::find($request->id);
        $image->image = '';
        $image->delete();
        Storage::delete('/public/' . $request->filePath);
        return response()->json(array('success' => true));
    }

    public function searchProduct(Request $request)
    {
        try {

            $data = collect();


            $products = Product::with(['subcategory.category', 'subcategory'])

                ->where(function($q) use($request){
                    $q->where('name','like','%'.$request->data.'%')
                        ->orWhere(function($query) use($request){
                            $query->orWhereHas('subcategory.category',function ($q) use($request){
                                $q->where('name','like','%'.$request->data.'%');
                            })
                                ->orWhereHas('subcategory',function ($q) use($request){
                                    $q->where('name','like','%'.$request->data.'%');

                                });
                        });
                })
                ->whereHas('subcategory', function($q) use($request) {
                    $q->where(['is_deleted' => 0, 'status' => 1]);
                })
                ->whereHas('subcategory.category', function($q) use($request) {
                    $q->where(['is_deleted' => 0, 'status' => 1]);
                })
                ->where('status','=',1)
                ->where('is_verify', '=',1)
                ->get();
                 $cnt=$products->count();

            // Get the products list
            if ($products->count() > 0) {

                foreach ($products as $product) {
                    if (isset($product->subcategory->category->name) && !empty($product->subcategory->category->name)) {
                        $categoryName = $product->subcategory->category->name;
                        $categoryId = $product->subcategory->category->id;
                    } else {
                        $categoryName = 'Other';
                        $categoryId = 0;
                    }

                    if (isset($product->subcategory->name) && !empty($product->subcategory->name)) {
                        $subcategoryName = $product->subcategory->name;
                        $subcategoryId = $product->subcategory->id;
                    } else {
                        $subcategoryName = 'Other';
                        $subcategoryId = 0;
                    }

                    if (isset($product->name) && !empty($product->name)) {
                        $productName = $product->name;
                        $productId = $product->id;
                    } else {
                        $productName = $request->data;
                        $productId = 0;
                    }
                    $name = $categoryName.' - '.$subcategoryName.' - '.$productName;
                    $keyword = $request->data;
                    $searchName = preg_replace("/($keyword)/i","<b>$0</b>",$name);

                    $data->push(['productId' => $productId, 'categoryId' => $categoryId, 'subcategoryId' => $subcategoryId, 'productName' => $name,'categoryName'=>$categoryName,'subcategoryName'=>$subcategoryName,'productTextName'=>$productName,'cnt'=>$cnt]);
                }

            } else {
                if(!Auth::user()->hasRole('admin')){
                    $name = 'Other - Other - '.urldecode($request->data);
                }else{
                    $name = __('admin.no_data_available');
                }

                $data->push(['productId' => 0, 'categoryId' => 0, 'subcategoryId' => 0, 'productName' => $name,'cnt'=>0]);

            }

            return response()->json(['success' => true,'data' => $data,'cnt'=>$cnt]);


        } catch (\Exception $exception) {
            Log::critical('Code 503 | ErrorCode:B038 Product Search '.request()->getClientIp());
            return response()->json(['success' => false,'message' => __('admin.something_went_wrong')]);

        }
    }
}
