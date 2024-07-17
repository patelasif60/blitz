<?php

namespace App\Http\Controllers;

use App\Events\BuyerNotificationEvent;
use App\Models\Category;
use App\Models\PreferredSupplier;
use App\Models\PreferredSuppliersRfq;
use App\Models\RfqProduct;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PreferredSuppliersController extends Controller
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
    }

    /** Add Preferred Suppliers Data
    *   Ronak M - 04/07/2022
    */
    public function addPreferredSuppliers(Request $request) {
        $preferredSuplier = new PreferredSupplier();
        $preferredSuplier->user_id = Auth()->user()->id;
        $preferredSuplier->company_id =  Auth::user()->default_company ?? null;;
        $preferredSuplier->supplier_id = $request->supplierId;
        $preferredSuplier->is_active = 1;
        $preferredSuplier->save();
        return response()->json(array('success' => true, 'response' => "Preferred supplier added successfully"));
    }

    /** Preferred Suppliers Data
    *   Ronak M - 21/06/2022
    */
    public function getAllPreferredSuppliers(Request $request) {
        if(isset($request->categoryId) && !empty($request->categoryId)) {
            $catName = Category::where('id',$request->categoryId)->first(['name as categoryName']);
        }

        $preferredSuppliers = PreferredSupplier::leftJoin('user_suppliers','preferred_suppliers.supplier_id','=','user_suppliers.supplier_id')
        ->leftJoin('user_companies','user_suppliers.user_id','=','user_companies.user_id')
        ->leftJoin('companies','user_companies.company_id','=','companies.id')
        ->leftJoin('suppliers','preferred_suppliers.supplier_id','=','suppliers.id')
        ->leftJoin('supplier_products','suppliers.id','=','supplier_products.supplier_id')
        ->leftJoin('products','supplier_products.product_id','=','products.id')
        ->leftJoin('sub_categories','products.subcategory_id','=','sub_categories.id');
        /**********begin:Preferred Suppliers set permissions based on custom role******/
        $isOwner = User::checkCompanyOwner();
        if($isOwner == true || Auth::user()->hasPermissionTo('list-all buyer preferred supplier')){
            $preferredSuppliers = $preferredSuppliers->where('preferred_suppliers.company_id', Auth::user()->default_company);
        }else {
            $preferredSuppliers = $preferredSuppliers->where('preferred_suppliers.user_id', Auth::user()->id)->where('preferred_suppliers.company_id', Auth::user()->default_company);;
        }
        /**********end:Preferred Suppliers set permissions based on custom role******/
        if(isset($request->categoryId) && !empty($request->categoryId)) {
            // $preferredSuppliers = $preferredSuppliers->where('interested_in', 'LIKE', '%'.$catName->categoryName.'%');
            $preferredSuppliers = $preferredSuppliers->where('sub_categories.category_id',intval($request->categoryId));
        }
        $preferredSuppliers = $preferredSuppliers->where('preferred_suppliers.deleted_at', null)
            ->where('preferred_suppliers.is_active', 1)
        ->groupBy(['suppliers.id','suppliers.name'])
        ->orderBy('preferred_suppliers.id','DESC')
        ->get(['suppliers.name as companyName','suppliers.contact_person_email','suppliers.interested_in','preferred_suppliers.is_active','suppliers.id as preferredSuppId']);

        $preferredSuppliersView = view('preferredSuppliers/preferred_suppliers_modal',['preferredSuppliers' => $preferredSuppliers])->render();

        return response()->json(array('success' => true, 'preferredSupplierView' => $preferredSuppliersView));
    }

    /** Update status(active / inactive) for preferred suppliers
    *  Ronak M - 22/06/2022
    */
    public function updatePreferredSupplierStatus(Request $request) {
        if ($request->ajax()) {
            $userId = Auth::user()->id;
            if (!empty($userId)) {
                $isOwner = User::checkCompanyOwner();
                if($isOwner == true || Auth::user()->hasPermissionTo('list-all buyer preferred supplier')){
                    $data = PreferredSupplier::where('company_id',Auth::user()->default_company)->where('supplier_id', $request->preferredSuppId)->update([
                        'is_active' => $request->status == 1 ? 0 : 1,
                    ]);
                }else {
                    $data = PreferredSupplier::where('user_id', $userId)->where('company_id',Auth::user()->default_company)->where('supplier_id', $request->preferredSuppId)->update([
                        'is_active' => $request->status == 1 ? 0 : 1,
                    ]);
                }

                return response()->json(['response' => 'success', 'success' => 'true']);
            }
        }
    }

    /** Delete Preferred Suppliers
    * (Ronak M - 21/06/2022)
    */
    function deletePreferredSupplier($id) {
        $isOwner = User::checkCompanyOwner();
        if($isOwner == true || Auth::user()->hasPermissionTo('list-all buyer preferred supplier')){
            $userData = PreferredSupplier::where('company_id',Auth::user()->default_company)->where('supplier_id', $id)->first();
        }else {
            $userData = PreferredSupplier::where('user_id', Auth::user()->id)->where('company_id',Auth::user()->default_company)->where('supplier_id', $id)->first();
        }
        if(!empty($userData)){
            $userData->delete();
        }
        buyerNotificationInsert(Auth::user()->id, 'Preferred Supplier Deleted', 'buyer_preferred_supplier_delete', 'other', 0, ['updated_by' => Auth::user()->full_name, 'icons' => 'fa-gear']);
        broadcast(new BuyerNotificationEvent());
        return response()->json(array('success' => 'true'));
    }

    //Get all preferred suppliers list by rfq id (Ronak M - 28/06/2022)
    public function getPreferredSuppliersData(Request $request) {
        //dd($request->all());

        $rfqProducts = RfqProduct::where('rfq_id',$request->rfqId)->pluck('product_id')->toArray();
        $preferredIds = PreferredSuppliersRfq::where('rfq_id',$request->rfqId)->get(['supplier_id']);
        //$rfqProductSuppliers = SupplierProduct::whereIn('product_id',$rfqProducts)->whereIn('supplier_id',$preferredIds)->get(['supplier_id','product_id'])->toArray();

        $preferredSupplierData = [];
        if(isset($preferredIds)) {
            foreach($preferredIds as $i => $preferredSuplier) {
                $supplier = $preferredSuplier->supplier()->first(['id','name']);
                $preferredSupplierData[$i] = $supplier;
                $supplierProducts = $supplier->products()->whereIn('product_id',$rfqProducts)->where('supplier_products.is_deleted','=',0)->get(['name']);
                $preferredSupplierData[$i]['products'] = $supplierProducts;
            }
        } else {
            return response()->json(array('success' => false));
        }

        $preferredSupplierView = view('preferredSuppliers/preferred_suppliers_modal',['preferredSuppliers' => $preferredSupplierData])->render();
        return response()->json(array('success' => true, 'preferredSupplierView' => $preferredSupplierView));
    }

    //Get preferred suppliers list by category (Ronak M - 28/06/2022)
    public function getPreferredSuppliersByCategory($categoryId) {
        $catName = Category::where('id',$categoryId)->first(['name as categoryName']);

        $suppliersData = PreferredSupplier::leftJoin('suppliers','preferred_suppliers.supplier_id','=','suppliers.id')
        ->leftJoin('supplier_products','suppliers.id','=','supplier_products.supplier_id')
        ->leftJoin('products','supplier_products.product_id','=','products.id')
        ->leftJoin('sub_categories','products.subcategory_id','=','sub_categories.id')
        //->where('interested_in', 'LIKE', '%'.$catName->categoryName.'%')
        ->where('sub_categories.category_id',intval($categoryId));
        /**********begin:Preferred Suppliers set permissions based on custom role******/
        $isOwner = User::checkCompanyOwner();
        if($isOwner == true || Auth::user()->hasPermissionTo('list-all buyer preferred supplier')){
            $suppliersData = $suppliersData->where('preferred_suppliers.company_id', Auth::user()->default_company);
        }else {
            $suppliersData = $suppliersData->where('preferred_suppliers.user_id', Auth::user()->id)->where('preferred_suppliers.company_id', Auth::user()->default_company);
        }
        /**********end:Preferred Suppliers set permissions based on custom role******/
        $suppliersData = $suppliersData->groupBy(['suppliers.id','contact_person_email'])
        ->get(['suppliers.id as supplierId','suppliers.name as supplierName','contact_person_email']);
        return response()->json(array('success' => true, 'suppliersData' => $suppliersData));
    }

    //Get preferred suppliers list by category (Ronak M - 01/07/2022)
    public function getPreferredSuppliersByRfqId($rfqId, $categoryId) {
        $allSuppliersIds = PreferredSupplier::where('user_id',Auth::user()->id)->get()->pluck(['supplier_id']);

        //get preferred suppliers data if already exist in "preferred_suppliers_rfqs" table
        $existingPreferredSuppliers = PreferredSuppliersRfq::where('user_id',Auth::user()->id)->where('rfq_id',$rfqId)->pluck('supplier_id');

        $preferredSuppliers = PreferredSupplier::leftJoin('user_suppliers','preferred_suppliers.supplier_id','=','user_suppliers.supplier_id')
        ->leftJoin('user_companies','user_suppliers.user_id','=','user_companies.user_id')
        ->leftJoin('companies','user_companies.company_id','=','companies.id')
        ->leftJoin('suppliers','preferred_suppliers.supplier_id','=','suppliers.id')
        ->leftJoin('supplier_products','suppliers.id','=','supplier_products.supplier_id')
        ->leftJoin('products','supplier_products.product_id','=','products.id')
        ->leftJoin('sub_categories','products.subcategory_id','=','sub_categories.id');
        /**********begin:Preferred Suppliers set permissions based on custom role******/
        $isOwner = User::checkCompanyOwner();
        if($isOwner == true || Auth::user()->hasPermissionTo('list-all buyer preferred supplier')){
            $preferredSuppliers = $preferredSuppliers->where('preferred_suppliers.company_id', Auth::user()->default_company);
        }else {
            $preferredSuppliers = $preferredSuppliers->where('preferred_suppliers.user_id', Auth::user()->id)->where('preferred_suppliers.company_id', Auth::user()->default_company);;
        }
        /**********end:Preferred Suppliers set permissions based on custom role******/
        if(isset($categoryId) && !empty($categoryId)) {
            $preferredSuppliers = $preferredSuppliers->where('sub_categories.category_id',intval($categoryId));
        }
        $preferredSuppliers = $preferredSuppliers->where('preferred_suppliers.deleted_at', null)
        ->where('preferred_suppliers.is_active', 1)
        ->groupBy(['suppliers.id','suppliers.name'])
        ->orderBy('preferred_suppliers.id','DESC')
        //->where('preferred_suppliers.rfq_id', $rfqId)
        ->get(['suppliers.name as companyName','suppliers.contact_person_email','suppliers.interested_in','suppliers.id as preferredSuppId']);
        return response()->json(array('success' => true, 'suppliersData' => $preferredSuppliers, 'selectedSuppliersIds' => $existingPreferredSuppliers->toArray(), 'allSuppliersIds' => $allSuppliersIds->toArray()));
    }

}
