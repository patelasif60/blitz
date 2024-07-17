<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\SupplierProduct;
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

class SupplierProductController extends Controller
{
    function list(Request $request){
        $authRoleId = auth()->user()->role_id;
        if($request->ajax() && $request->get('draw')) {
            $start = $request->get("start");
            $length = $request->get("length");
            $search = (!empty($request->get('search')) ? $request->get('search')['value'] : '');
            $column_order    = $request->get('column_order');
            $order_type     = $request->get('order_type');
           
           

            $query = SupplierProduct::with(['product','product.subcategory','product.subcategory.category'])
                ->where('supplier_id',User::with('supplier')->where('id',auth()->user()->id)->first()->supplier->id)
                ->where('is_deleted',0);
             
            if($column_order == "name"){
                $query = $query->orderBy(Product::select('name')->whereColumn('supplier_products.product_id', 'products.id')->limit(1),$order_type);
            }

            if($column_order == "status"){
                $query = $query->orderBy(Product::select('status')->whereColumn('supplier_products.product_id', 'products.id')->limit(1),$order_type);
            }

            if($column_order == "sub_category_name"){
                $query = $query->orderBy(subcategory::join('products','products.subcategory_id','=','sub_categories.id')->select('sub_categories.name')
            ->whereColumn('supplier_products.product_id', 'products.id')->limit(1),$order_type);
            }

            if($column_order == "category_name"){
                $query = $query->orderBy(subcategory::join('products','products.subcategory_id','=','sub_categories.id')
                ->join('categories', 'categories.id', '=', 'sub_categories.category_id')->select('categories.name')
                ->whereColumn('supplier_products.product_id', 'products.id')->limit(1),$order_type);
            }
            

           
            
            if ($search != "") {
                $query=$query->where(function($q) use($search){
                    $q->orWhere(function($query) use($search){
                        $query->orWhereHas('product',function ($q) use($search){
                            $q->where('name','like','%'.$search.'%');
                        })
                        ->orWhereHas('product.subcategory.category',function ($q) use($search){
                            $q->where('name','like','%'.$search.'%');
                        })
                        ->orWhereHas('product.subcategory',function ($q) use($search){
                            $q->where('name','like','%'.$search.'%');
                        });
                    });
                });
            }

            
            $setTotalRecords = $query->count();
            $setFilteredRecords = $query->count();
           
            $products = ($length == -1) ? $query->get() : $query->skip($start)->take($length)->get();
           
            if(!empty($products)){
                foreach ($products as $product) {
                    $product->category_name = !empty($product->product->subcategory->category) ? $product->product->subcategory->category->name : '-';
                    $product->sub_category_name = !empty($product->product->subcategory) ? $product->product->subcategory->name : '-';
                    $product->name = !empty($product->product->name) ? $product->product->name : '-';
                    $product->supp_prod_id = !empty($product->id) ? $product->id : '-';
                    $product->status = !empty($product->product->status) ? $product->product->status : '-';
                    $product->addedBy = !empty($product->trackAddData) ? $product->trackAddData->full_name : '-';
                    $product->updatedBy = !empty($product->trackUpdateData) ? $product->trackUpdateData->full_name : '-';
                }
            }
            
            
            $data = $products;
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) use ($authRoleId) {
                    $btn = '<a href="'.route("edit-supplier-product", ["id" => Crypt::encrypt($row->supp_prod_id)]).'"
                    class="show-icon"  data-toggle="tooltip" ata-placement="top" title="Edit">
                    <i class="fa fa-edit"></i>
                    </a>';

                    $btn .= '<a href = "javascript:void(0)" data-id="'.$row->supp_prod_id.'" class="ps-2 deleteSupplierProductDetails" data-toggle="tooltip" ata-placement="top" title="delete">
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
        return view('admin.productsSpplier',compact('authRoleId'));
    }
}
