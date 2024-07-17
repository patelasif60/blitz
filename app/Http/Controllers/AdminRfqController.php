<?php

namespace App\Http\Controllers;

use App\Events\BuyerNotificationEvent;
use App\Models\City;
use App\Models\Country;
use App\Models\CountryOne;
use App\Models\RfqAttachment;
use App\Models\RfqMaximumProducts;
use App\Models\Role;
use App\Models\Settings;
use App\Models\State;
use App\Models\TermsCondition;
use App\Events\rfqsEvent;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserSupplier;
use App\Models\AdminFeedbackReasons;
use Illuminate\Http\Request;
use App\Models\Rfq;
use App\Models\Unit;
use App\Models\RfqProduct;
use App\Models\Category;
use App\Models\Product;
use App\Models\UserActivity;
use App\Models\SubCategory;
use App\Models\UserRfq;
use App\Models\Supplier;
use App\Models\OtherCharge;
use App\Models\RfqStatus;
use App\Models\RfqActivity;
use App\Models\SupplierAddress;
use App\Models\SupplierProduct;
use App\Models\UserAddresse;
use App\Models\Quote;
use App\Models\Order;
use App\Models\Company;
use App\Models\OrderStatus;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Response;
use Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Export\RfqExport;
use App\Export\RfqquotesExport;
use App\Models\GroupMember;
use App\Models\Groups;
use App\Models\GroupSupplier;
use App\Models\GroupSupplierDiscountOption;
use App\Models\LogisticsService;
use App\Models\SystemActivity;
use App\Models\PreferredSuppliersRfq;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Sabberworm\CSS\Value\URL;
use ZipArchive;
use File;
use App\Models\CreditDays;
use App\Models\SupplierDealWithCategory;

class AdminRfqController extends Controller
{

    public function __construct()
    {

        $this->middleware('permission:create rfqs|edit rfqs|delete rfqs|publish rfqs|unpublish rfqs', ['only'=> ['list']]);
        $this->middleware('permission:create rfqs', ['only' => ['create']]);
        $this->middleware('permission:edit rfqs', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete rfqs', ['only' => ['delete']]);
    }

    function searchProduct(Request $request)
    {
        $product = $request->product;

        $categoryId = $request->categoryId;
        if ($categoryId) {
            $filterData = DB::table('categories')
                ->join('sub_categories', 'categories.id', '=', 'sub_categories.category_id')
                ->join('products', 'sub_categories.id', '=', 'products.subcategory_id')
                ->where('products.name', 'LIKE', '%' . $product . '%')
                ->where('categories.id', '=', $categoryId)
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
        return response()->json(array('success' => true, 'filterData' => $filterData));
    }

    function list(Request $request)
    {
        $authUser = Auth::user()->load('supplier');
        $activeFlag = Auth::user()->is_active;
        if ($authUser->hasRole('supplier')) {
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $preferredSuppliersRfq = PreferredSuppliersRfq::where('supplier_id', getSupplierByLoginId(auth()->user()->id))->get(['rfq_id'])->pluck('rfq_id');
            $supplierProducts = SupplierProduct::where(['supplier_id'=>$supplier_id, 'status'=>1, 'is_deleted'=>0])->get();
            $supplierProductsSubCategories = $supplierProducts->pluck('product')->pluck('subcategory_id')->toArray();
            //Get suppier Deal with Category
            $SupplierDealingCategoty = SupplierDealWithCategory::where('supplier_id',$supplier_id)->pluck('sub_category_id')->toArray();
            $supplierRfqShowSubCategory = array_unique(array_merge(array_unique($supplierProductsSubCategories),$SupplierDealingCategoty));

            $query = Rfq::with('userRfqs', 'rfqSuppliers', 'rfqProducts', 'rfqStatus:id,backofflice_name as status_name', 'rfqUsers:user_id,rfq_id,firstname,lastname','companyDetails','rfqQuotes')
                ->select(['id', 'firstname', 'lastname', 'created_at', 'reference_number', 'is_require_credit', 'is_preferred_supplier', 'status_id','company_id','payment_type','credit_days']);
            $query->whereHas('rfqProducts', function ($q) use ($supplier_id,$supplierRfqShowSubCategory) {
                 $q->whereIn('rfq_products.sub_category_id',$supplierRfqShowSubCategory);
            })->where('is_deleted',0);

            $whereNotification = ['supplier_id' => $supplier_id, 'admin_id' => 0, 'user_activity' => 'Generate RFQ', 'notification_type' => 'rfq', 'side_count_show' => 0];
            Notification::where($whereNotification)->update(['side_count_show' => 1]);
        } else {
            $query = Rfq::with('userRfqs', 'rfqProducts', 'rfqStatus:id,backofflice_name as status_name', 'rfqUsers:user_id,rfq_id,firstname,lastname','companyDetails', 'rfqQuotes')
                ->select(['id', 'firstname', 'lastname', 'created_at', 'reference_number', 'is_require_credit', 'is_preferred_supplier', 'status_id','company_id','payment_type','credit_days'])
                ->where('group_id', null);

            //Agent category permission
            if ($authUser->hasRole('agent')) {
                $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();
                $query->whereHas('rfqProduct', function ($query) use ($assignedCategory) {
                    $query->whereIn('category_id', $assignedCategory);
                });
            }
            $whereNotification = ['admin_id' => $authUser->id, 'supplier_id' => 0, 'user_activity' => 'Generate RFQ', 'notification_type' => 'rfq', 'side_count_show' => 0];
            Notification::where($whereNotification)->update(['side_count_show' => 1]);
        }

        if ($request->ajax() && $request->get('draw')) {
             $draw = $request->get('draw');
             $start = $request->get("start");
             $length = $request->get("length");
             $sort = !empty($request->get('order')) ? $request->get('order')[0]['dir'] : 'asc';
             $search = !empty($request->get('search')) ? $request->get('search')['value'] : '';

             $columnIndex_arr = $request->get('order');
             $columnName_arr = $request->get('columns');
             $columnIndex = !empty($columnIndex_arr) ? $columnIndex_arr[0]['column'] : '';
             $column = !empty($columnIndex_arr) ? $columnName_arr[$columnIndex]['data'] : '';

             // Server side search
             if ($search != "") {
                 $query->where(function ($q) use ($search) {
                     $q->where('reference_number', 'LIKE', "%$search%")
                         ->orWhere('firstname', 'LIKE', "%$search%")
                         ->orWhere('lastname', 'LIKE', "%$search%")
                         ->orWhereHas('rfqProducts', function ($query) use ($search) {
                             $query->where('category', 'LIKE', "%$search%");
                         })
                         ->orWhereHas('rfqProducts', function ($query) use ($search) {
                             $query->where('sub_category', 'LIKE', "%$search%");
                         })
                         ->orWhereHas('rfqProducts', function ($query) use ($search) {
                             $query->where('product', 'LIKE', "%$search%");
                         })
                         ->orWhere('created_at', 'LIKE', "%$search%")
                         ->orWhereHas('rfqStatus', function ($query) use ($search) {
                             $query->where('backofflice_name', 'LIKE', "%$search%");
                         });
                     $q->orWhereHas('companyDetails', function($query) use($search){
                        $query->where('name', 'LIKE',"%$search%");
                    });
                     $searchLowerCase = strtolower($search);
                     if (in_array($searchLowerCase, ["advanced", "credit", "bayar", "kredit"])) {
                         if ($searchLowerCase == "advanced" || $searchLowerCase == "bayar") {
                             $q->orWhere('is_require_credit', 0);
                         } elseif ($searchLowerCase == "credit" || $searchLowerCase == "kredit") {
                             $q->orWhere('is_require_credit', 1);
                         }
                     }
                 });
             }

            $totalRecords = $query->count();

            //Filters
             $query = $this->filter($request->filterData, $query);

             // Sorting
             $query = $this->sorting($column, $sort, $query);

             // Total Display records
             $totalDisplayRecords = $query->count();

            if($length > 1)
            {
                $payments = $query->skip($start)->take($length)->get();
            }
            else{
                $payments = $query->get();
            }
             $rfqData = $payments->map(function ($rfq) use ($request, $authUser) {
                 $rfqItemCount = $rfq->rfqProducts->count();
                 $rfq->product = $rfqItemCount . ' ' . __('admin.products');
                 if ($rfqItemCount == 1) {
                     $rfq->product = $rfq->rfqProducts->first()->product_name_desc; //
                 }
                 $rfqCategoryName = $rfq->rfqProducts->first()->category ?? '-';
                 if (!empty($preferredSuppliersRfq)) {
                     if ($preferredSuppliersRfq->count() > 0) {
                         if ($preferredSuppliersRfq->contains($rfq->id) === false && $rfq->is_preferred_supplier == 1) {
                             // continue;
                         }
                     } elseif ($rfq->is_preferred_supplier == 1) {
                         // continue;
                     }
                 }


                 $rfqId = $rfq->id;

                 if ($authUser->hasRole('supplier')) {
                     $supplier_id = $authUser->supplier->id;
                     $quoteCount=$rfq->rfqQuotes->where('supplier_id', $supplier_id)->count();
                     if($rfq->rfqStatus->id=='2' && $quoteCount == 0){
                         $rfq->rfqStatus->status_name = 'RFQ Received';
                     }else if($quoteCount > 0 &&  $rfq->rfqStatus->id == '1'){
                         $rfq->rfqStatus->status_name = 'RFQ Response Sent';
                     }
                 }
                 else if($authUser->hasRole('admin')){
                     $quoteCount=$rfq->rfqQuotes->count();
                     if($rfq->rfqStatus->id=='2' && $quoteCount == 0){
                         $rfq->rfqStatus->status_name = 'RFQ Received';
                     }else if($quoteCount > 0 &&  $rfq->rfqStatus->id == '1'){
                         $rfq->rfqStatus->status_name = 'RFQ Response Sent';
                     }
                 }
                 $rfqNumber = '<a href="javascript:void(0);" style="text-decoration: none; color: #000" class="viewRfqDetail hover_underline" data-bs-toggle="modal" data-bs-target="#viewRfqModal" data-id="'.$rfq->id.'">'.$rfq->reference_number.'</a>';

                 if ($rfq->is_require_credit && $rfq->payment_type == 1) {
                     $isCredit = '<span class="badge badge-pill badge-danger d-block">' . __('admin.credit') . '-'.$rfq->credit_days.' </span>';
                 }
                 else if ($rfq->is_require_credit && $rfq->payment_type == 3) {

                     $isCredit = '<span class="badge badge-pill badge-danger d-block">' . __('admin.lc') . '</span>';
                 }
                 else if ($rfq->is_require_credit && $rfq->payment_type == 4) {
                     $isCredit = '<span class="badge badge-pill badge-danger d-block">' . __('admin.skbdn') . '</span>';

                 }
                 else {
                     $isCredit = '<span class="badge badge-pill badge-success d-block">' . __('admin.advanced') . '</span>';
                 }
                 $rfqLink = '';
                 if ($rfq->status_id == 1 && $rfq->status_id != 3 && !$authUser->hasRole('supplier')) {
                     if ($authUser->hasPermissionTo('edit rfqs')) {
                         $rfqLink = '<a href="' . route('rfq-edit', ['id' => Crypt::encrypt($rfqId)]) . '" class="pe-2 show-icon d-inline-block"  data-toggle="tooltip" ata-placement="top" title="' . __('admin.edit') . '"><i class="fa fa-edit" aria-hidden="true"></i></a>';
                     }
                 }
                 if (!in_array($rfq->status_id, [4]) &&  $rfq->rfqStatus->status_name!='RFQ Response Sent') {
                     $rfqLink .= "<a class=\"pe-2 cursor-pointer d-inline-block\" onclick=\"chat.adminShowRfqChat('$rfqId','Rfq','$rfq->reference_number')\"  style=\"color: cornflowerblue;\" data-id=\"\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Chat\"><i class=\"fa fa-comments\"></i></a>";
                 }
                 if ($authUser->hasPermissionTo('publish rfqs')) {
                     $rfqLink .= '<a class="cursor-pointer viewRfqDetail d-inline-block" data-id="' . $rfqId . '" data-toggle="tooltip" data-placement="top" title="' . __('admin.view') . '"><i class="fa fa-eye"></i></a>';
                 }

                 if (Auth::user()->role_id == ADMIN) {
                     $rfqLink .= '<a class="cursor-pointer viewFeedback" style="color: cornflowerblue;" data-id="' . $rfqId . '"  data-toggle="tooltip" data-placement="top" title="' . __('admin.feedback') . '" data-bs-original-title="' . __('admin.feedback') . '" aria-label=""><i class="fa fa-commenting ms-2 text-info"></i></a>';
                 }
                 $action = $rfqLink;
                 return [
                     'id' => $rfq->id,// rfq_id
                     'reference_number' => $rfqNumber,// reference_number
                     'is_require_credit' => $isCredit,// payment term
                     'name' => $rfq->firstname . ' ' . $rfq->lastname, //buyer_name
                      'buyer_company_name' => ($rfq->companyDetails) ? $rfq->companyDetails->name : '', // buyer company name
                     'category' => $rfqCategoryName ?? '-', // category
                     'product_name' => $rfq->product ?? '-', // product_name
                     'created_at' => date('d-m-Y H:i:s', strtotime($rfq->created_at)), // created date
                     'status_id' => __('admin.' . trim($rfq->rfqStatus->status_name)), // status
                     'actions' => $action
                 ];
             });
             return response()->json([

                 "draw" => intval($draw),
                 "iTotalRecords" => $totalRecords,
                 "iTotalDisplayRecords" => $totalDisplayRecords,
                 "aaData" => $rfqData
             ]);
        }

        $rfqs = $query->get();
        $rfq_numbers = $rfqs->unique('id');
        $status = $rfqs->unique('rfqStatus.id');
        $companies = $rfqs->unique('companyDetails.id');

        $cats = [];
        $custmrs = [];
        $newQuery = clone $rfqs;
        foreach ($newQuery as $category) {
            foreach ($category->rfqProducts as $val) {
                array_push($cats, $val->category);
            }
        }
        $categories = array_unique($cats);
        foreach ($newQuery as $cust) {
            foreach ($cust->rfqUsers as $value) {
                array_push($custmrs, ['user_id' => $value->user_id, 'customer_name' => $value->firstname . ' ' . $value->lastname]);
            }
        }

        $temp = array_unique(array_column($custmrs, 'customer_name'));
        $unique_arr = array_intersect_key($custmrs, $temp);
        $customers = $unique_arr;
        $feedbackReasions = AdminFeedbackReasons::where('reasons_type', 1)->get();

        /**begin: system log**/
        RFq::bootSystemView(new RFq());
        /**end: system log**/
        return View::make('admin.rfq.list')->with(compact(['rfq_numbers', 'customers', 'categories', 'status', 'feedbackReasions','companies','activeFlag']));
    }

    /**
     * Admin/Supplier RFQ Datatable Listing
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listJson(Request $request)
    {
        $authUser = Auth::user()->load('supplier');
        if ($authUser->hasRole('supplier')) {
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $preferredSuppliersRfq = PreferredSuppliersRfq::where('supplier_id', getSupplierByLoginId(auth()->user()->id))->get(['rfq_id'])->pluck('rfq_id');
            $supplierProducts = SupplierProduct::where(['supplier_id'=>$supplier_id, 'status'=>1, 'is_deleted'=>0])->get();
            $supplierProductsSubCategories = $supplierProducts->pluck('product')->pluck('subcategory_id')->toArray();
            //Get suppier Deal with Category
            $SupplierDealingCategoty = SupplierDealWithCategory::where('supplier_id',$supplier_id)->pluck('sub_category_id')->toArray();
            $supplierRfqShowSubCategory = array_unique(array_merge(array_unique($supplierProductsSubCategories),$SupplierDealingCategoty));

            $query = Rfq::with('userRfqs', 'rfqSuppliers', 'rfqProducts', 'rfqStatus:id,backofflice_name as status_name', 'rfqUsers:user_id,rfq_id,firstname,lastname','companyDetails','rfqQuotes')
                ->select(['id', 'firstname', 'lastname', 'created_at', 'reference_number', 'is_require_credit', 'is_preferred_supplier', 'status_id','company_id','payment_type','credit_days']);
            $query->whereHas('rfqProducts', function ($q) use ($supplier_id,$supplierRfqShowSubCategory) {
                $q->whereIn('rfq_products.sub_category_id',$supplierRfqShowSubCategory);
            })->where('is_deleted',0);

        } else {
            $query = Rfq::with('userRfqs', 'rfqProducts', 'rfqStatus:id,backofflice_name as status_name', 'rfqUsers:user_id,rfq_id,firstname,lastname','companyDetails', 'rfqQuotes')
                ->select(['id', 'firstname', 'lastname', 'created_at', 'reference_number', 'is_require_credit', 'is_preferred_supplier', 'status_id','company_id','payment_type','credit_days'])
                ->where('group_id', null);

            //Agent category permission
            if ($authUser->hasRole('agent')) {
                $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();
                $query->whereHas('rfqProduct', function ($query) use ($assignedCategory) {
                    $query->whereIn('category_id', $assignedCategory);
                });
            }

        }

        if ($request->ajax() && $request->get('draw')) {
            $draw = $request->get('draw');
            $start = $request->get("start");
            $length = $request->get("length");
            $sort = !empty($request->get('order')) ? $request->get('order')[0]['dir'] : 'asc';
            $search = !empty($request->get('search')) ? $request->get('search')['value'] : '';

            $columnIndex_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $columnIndex = !empty($columnIndex_arr) ? $columnIndex_arr[0]['column'] : '';
            $column = !empty($columnIndex_arr) ? $columnName_arr[$columnIndex]['data'] : '';
            $totalRecords = $query->count();

            // Server side search
            if ($search != "") {
                $query->where(function ($q) use ($search) {
                    $q->where('reference_number', 'LIKE', "%$search%")
                        ->orWhere('firstname', 'LIKE', "%$search%")
                        ->orWhere('lastname', 'LIKE', "%$search%")
                        ->orWhereHas('rfqProducts', function ($query) use ($search) {
                            $query->where('category', 'LIKE', "%$search%");
                        })
                        ->orWhereHas('rfqProducts', function ($query) use ($search) {
                            $query->where('sub_category', 'LIKE', "%$search%");
                        })
                        ->orWhereHas('rfqProducts', function ($query) use ($search) {
                            $query->where('product', 'LIKE', "%$search%");
                        })
                        ->orWhere('created_at', 'LIKE', "%$search%")
                        ->orWhereHas('rfqStatus', function ($query) use ($search) {
                            $query->where('backofflice_name', 'LIKE', "%$search%");
                        });
                    $q->orWhereHas('companyDetails', function($query) use($search){
                        $query->where('name', 'LIKE',"%$search%");
                    });
                    $searchLowerCase = strtolower($search);
                    if (in_array($searchLowerCase, ["advanced", "credit", "bayar", "kredit"])) {
                        if ($searchLowerCase == "advanced" || $searchLowerCase == "bayar") {
                            $q->orWhere('is_require_credit', 0);
                        } elseif ($searchLowerCase == "credit" || $searchLowerCase == "kredit") {
                            $q->orWhere('is_require_credit', 1);
                        }
                    }
                });
            }

            //Filters
            $query = $this->filter($request->filterData, $query);

            // Sorting
            $query = $this->sorting($column, $sort, $query);


            // Total Display records
            $totalDisplayRecords = $query->count();

            if($length > 1)
            {
                $payments = $query->skip($start)->take($length)->get();
            }
            else{
                $payments = $query->get();
            }
            $rfqData = $payments->map(function ($rfq) use ($request, $authUser) {
                $rfqItemCount = $rfq->rfqProducts->count();
                $rfq->product = $rfqItemCount . ' ' . __('admin.products');
                if ($rfqItemCount == 1) {
                    $rfq->product = $rfq->rfqProducts->first()->product_name_desc; //
                }
                $rfqCategoryName = $rfq->rfqProducts->first()->category ?? '-';
                if (!empty($preferredSuppliersRfq)) {
                    if ($preferredSuppliersRfq->count() > 0) {
                        if ($preferredSuppliersRfq->contains($rfq->id) === false && $rfq->is_preferred_supplier == 1) {
                            // continue;
                        }
                    } elseif ($rfq->is_preferred_supplier == 1) {
                        // continue;
                    }
                }


                $rfqId = $rfq->id;

                if ($authUser->hasRole('supplier')) {
                    $supplier_id = $authUser->supplier->id;
                    $quoteCount=$rfq->rfqQuotes->where('supplier_id', $supplier_id)->count();
                    if($rfq->rfqStatus->id=='2' && $quoteCount == 0){
                        $rfq->rfqStatus->status_name = 'RFQ Received';
                    }else if($quoteCount > 0 &&  $rfq->rfqStatus->id == '1'){
                        $rfq->rfqStatus->status_name = 'RFQ Response Sent';
                    }
                }
                else if($authUser->hasRole('admin')){
                    $quoteCount=$rfq->rfqQuotes->count();
                    if($rfq->rfqStatus->id=='2' && $quoteCount == 0){
                        $rfq->rfqStatus->status_name = 'RFQ Received';
                    }else if($quoteCount > 0 &&  $rfq->rfqStatus->id == '1'){
                        $rfq->rfqStatus->status_name = 'RFQ Response Sent';
                    }
                }
                $rfqNumber = '<a href="javascript:void(0);" style="text-decoration: none; color: #000" class="viewRfqDetail hover_underline" data-bs-toggle="modal" data-bs-target="#viewRfqModal" data-id="'.$rfq->id.'">'.$rfq->reference_number.'</a>';

                if ($rfq->is_require_credit && $rfq->payment_type == 1) {
                    $isCredit = '<span class="badge badge-pill badge-danger d-block">' . __('admin.credit') . '-'.$rfq->credit_days.' </span>';
                }
                else if ($rfq->is_require_credit && $rfq->payment_type == 3) {

                    $isCredit = '<span class="badge badge-pill badge-danger d-block">' . __('admin.lc') . '</span>';
                }
                else if ($rfq->is_require_credit && $rfq->payment_type == 4) {
                    $isCredit = '<span class="badge badge-pill badge-danger d-block">' . __('admin.skbdn') . '</span>';

                }
                else {
                    $isCredit = '<span class="badge badge-pill badge-success d-block">' . __('admin.advanced') . '</span>';
                }
                $rfqLink = '';
                if ($rfq->status_id == 1 && $rfq->status_id != 3 && !$authUser->hasRole('supplier')) {
                    if ($authUser->hasPermissionTo('edit rfqs')) {
                        $rfqLink = '<a href="' . route('rfq-edit', ['id' => Crypt::encrypt($rfqId)]) . '" class="pe-2 show-icon d-inline-block"  data-toggle="tooltip" ata-placement="top" title="' . __('admin.edit') . '"><i class="fa fa-edit" aria-hidden="true"></i></a>';
                    }
                }
                if (!in_array($rfq->status_id, [4]) &&  $rfq->rfqStatus->status_name!='RFQ Response Sent') {
                    $rfqLink .= "<a class=\"pe-2 cursor-pointer d-inline-block\" onclick=\"chat.adminShowRfqChat('$rfqId','Rfq','$rfq->reference_number')\"  style=\"color: cornflowerblue;\" data-id=\"\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Chat\"><i class=\"fa fa-comments\"></i></a>";
                }
                if ($authUser->hasPermissionTo('publish rfqs')) {
                    $rfqLink .= '<a class="cursor-pointer viewRfqDetail d-inline-block" data-id="' . $rfqId . '" data-toggle="tooltip" data-placement="top" title="' . __('admin.view') . '"><i class="fa fa-eye"></i></a>';
                }

                if (Auth::user()->role_id == ADMIN) {
                    $rfqLink .= '<a class="cursor-pointer viewFeedback" style="color: cornflowerblue;" data-id="' . $rfqId . '"  data-toggle="tooltip" data-placement="top" title="' . __('admin.feedback') . '" data-bs-original-title="' . __('admin.feedback') . '" aria-label=""><i class="fa fa-commenting ms-2 text-info"></i></a>';
                }
                $action = $rfqLink;
                return [
                    'id' => $rfq->id,// rfq_id
                    'reference_number' => $rfqNumber,// reference_number
                    'is_require_credit' => $isCredit,// payment term
                    'name' => $rfq->firstname . ' ' . $rfq->lastname, //buyer_name
                    'buyer_company_name' => ($rfq->companyDetails) ? $rfq->companyDetails->name : '', // buyer company name
                    'category' => $rfqCategoryName ?? '-', // category
                    'product_name' => $rfq->product ?? '-', // product_name
                    'created_at' => date('d-m-Y H:i:s', strtotime($rfq->created_at)), // created date
                    'status_id' => __('admin.' . trim($rfq->rfqStatus->status_name)), // status
                    'actions' => $action
                ];
            });
            return response()->json([

                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalDisplayRecords,
                "aaData" => $rfqData
            ]);
        }

    }


    /**
     * Get filters query for List AJAX of resource
     *
     * @param $filterData
     * @param $query
     * @return mixed
     */
    public function filter($filterData, $query)
    {
        if (!empty($filterData)) {
                $filterData = $filterData;
                //Refrence Number
                if (Arr::exists($filterData, 'rfq_ids')) {
                    $query->whereIn('reference_number', $filterData['rfq_ids']);
                }

                //Products
                if (Arr::exists($filterData, 'product_names')) {
                   $query->whereHas('rfqProducts', function($query) use($filterData){
                        $query->whereIn('product',$filterData['product_names']);
                    });
                }

                //Customers
                if (Arr::exists($filterData, 'customer_ids')) {
                    $query->whereHas('userRfqs', function($query) use($filterData){
                        $query->whereIn('user_id',$filterData['customer_ids']);
                    });
                }

                //Created Date
                if (Arr::exists($filterData, 'start_date') && Arr::exists($filterData, 'end_date')) {
                    $start_date = Carbon::createFromFormat('d-m-Y', $filterData['start_date'])->format('Y-m-d 00:00:00');
                    $end_date = Carbon::createFromFormat('d-m-Y', $filterData['end_date'])->format('Y-m-d 23:59:59');
                    $query->whereBetween('created_at', [$start_date, $end_date]);
                }
                 //Buyer company name
                 if (Arr::exists($filterData, 'company_ids')) {
                     $query->whereHas('companyDetails', function($query) use($filterData){
                         $query->whereIn('id',$filterData['company_ids']);
                     });
                 }
                //Category
                if (Arr::exists($filterData, 'category_names')) {
                    $query->whereHas('rfqProducts', function($query) use($filterData){
                        $query->whereIn('category',$filterData['category_names']);
                    });
                }
                if (Arr::exists($filterData, 'payment')) {
                    $query->whereIn('is_require_credit', $filterData['payment']);
                }
                //Status
                if (Arr::exists($filterData, 'status_ids')) {
                    $query->whereHas('rfqStatus', function($query) use($filterData){
                        $query->whereIn('id',$filterData['status_ids']);
                    });
                }
        }
        return $query;

    }

    /**
     * Get sorting query for List AJAX of resource
     *
     * @param $column
     * @param $sort
     * @param $query
     * @return mixed
     */
    public function sorting($column,$sort,$query)
    {
        if (!empty($column)) {
            if ($column == 'id') {
                $query = $query->orderBy('id', 'desc');
            }else{
                if($column == 'reference_number'){
                        $query->orderBy('id', $sort);
                    }
                if($column == 'is_require_credit'){
                    $query->orderBy('is_require_credit', $sort);
                }
                if($column == 'created_at'){
                    $query->orderBy('created_at', $sort);
                }
                if($column == 'category'){
                    $query->orderBy(RfqProduct::select('category')->whereColumn('rfqs.id','rfq_products.rfq_id')->limit(1),$sort);
                }
                if($column == 'product_name'){
                    $query->orderBy(RfqProduct::join('products', 'rfq_products.product_id', '=', 'products.id')->select('products.name')->whereColumn('rfqs.id','rfq_products.rfq_id')->limit(1),$sort);
                }
                if($column == 'status_id'){
                    $query->orderBy(RfqStatus::select('rfq_status.backofflice_name')->whereColumn('rfqs.status_id','rfq_status.id')->limit(1),$sort);
                }
                if($column == 'buyer_company_name'){
                        $query->orderBy(Company::select('name')
                            ->whereColumn('companies.id', 'rfqs.company_id'),$sort);
                    }
            }
        } else {
            $query->orderBy('id', 'desc');
        }
        return $query;
    }


	function listAjax(Request $request)
    {
		/* $customers = UserRfq::select('user_rfqs.id', 'user_rfqs.user_id', 'users.firstname', 'users.lastname')
			->join('users', 'user_rfqs.user_id', '=', 'users.id')
			->groupBy('user_rfqs.user_id')
			->get();
		$categories = Category::select('id', 'name')->get()->toArray();// categories
		$rfq_numbers = Rfq::select('id', 'reference_number')->get()->toArray();
		$products = RfqProduct::select('id', 'product as name')->get()->toArray();
		$status = RfqStatus::select('id', 'name','backofflice_name')->get()->toArray();// order_status
		$payment_term = ['advance' => 0, 'credit' => 1]; */

		$condition = [];
        if($request->rfq_ids){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'rfqs.reference_number', 'value' => $request->rfq_ids]);
        }
        // if($request->product_ids){
        //     array_push($condition, ['condition' => 'wherein', 'column_name' => 'products.id', 'value' => $request->product_ids]);
        // }
		if($request->product_names){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'rfq_products.product', 'value' => $request->product_names]);
        }

        if($request->customer_ids){
            $customer_rfq_ids = UserRfq::whereIn('user_id', $request->customer_ids)->get()->pluck('rfq_id');

            // dd($customer_rfq_ids);
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'rfqs.id', 'value' => $customer_rfq_ids]); //need to change column name
        }
        if($request->start_date && $request->end_date){
			$start_date = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d 00:00:00');
			$end_date = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d 23:59:59');
			array_push($condition, ['condition' => 'wherebetween', 'column_name' => 'rfqs.created_at', 'value' => [$start_date,  $end_date] ]);
        }
        /* if($request->category_ids){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'categories.id', 'value' => $request->category_ids]);
        } */
        if($request->category_names){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'rfq_products.category', 'value' => $request->category_names]);
        }
        if($request->payment){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'rfqs.is_require_credit', 'value' => $request->payment]);
            // array_push($condition, ['condition' => 'wherein', 'column_name' => 'orders.is_credit', 'value' => $request->payment]);
        }
        if($request->status_ids){
            array_push($condition, ['condition' => 'wherein', 'column_name' => 'rfqs.status_id', 'value' => $request->status_ids]);
        }

		if (auth()->user()->role_id == 3){
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $rfq = DB::table('rfqs')
                ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
                ->join('rfq_products', 'rfq_products.id', '=', 'rfqs.id')
                ->join('sub_categories', 'rfq_products.sub_category', '=', 'sub_categories.name')
                ->join('products', function ($join) {
                    $join->on(strtolower('rfq_products.product'), '=', strtolower('products.name'))
                        ->on('sub_categories.id', '=', 'products.subcategory_id');
                })
                ->join('supplier_products', 'products.id', '=', 'supplier_products.product_id')
                ->join('suppliers', 'supplier_products.supplier_id', '=', 'suppliers.id')
                ->where('suppliers.status', 1)
                ->where('suppliers.is_deleted', 0)
                ->where('suppliers.id', $supplier_id)
                ->where('supplier_products.is_deleted', 0)
                ->orderBy('rfqs.id', 'desc');
                if(sizeof($condition) > 0){
                    foreach($condition as $key => $value){
                        if(strtoupper($value['condition']) == "WHEREIN"){
                            $rfq = $rfq->whereIn($value['column_name'], $value['value']);
                        }
                        if(strtoupper($value['condition']) == "WHEREBETWEEN"){
                            $rfq = $rfq->whereBetween($value['column_name'], $value['value']);
                        }
                    }
                }
            $rfq = $rfq->get(['rfqs.id', 'rfqs.firstname', 'rfqs.lastname', 'rfqs.created_at', 'rfqs.reference_number', 'rfqs.is_require_credit', 'rfq_products.category', 'rfq_products.sub_category', 'rfq_products.product', 'rfq_products.product_description', 'rfq_status.backofflice_name as status_name','rfq_status.id as rfq_status_id']);
        } else {
            $rfq = DB::table('rfqs')
            ->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
            ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
            ->orderBy('rfqs.id', 'desc');

            if(sizeof($condition) > 0){
                foreach($condition as $key => $value){
                    if(strtoupper($value['condition']) == "WHEREIN"){
                        $rfq = $rfq->whereIn($value['column_name'], $value['value']);
                    }

                    if(strtoupper($value['condition']) == "WHEREBETWEEN"){
                        $rfq = $rfq->whereBetween($value['column_name'], $value['value']);
                    }
                }
            }
            $rfq = $rfq->get(['rfqs.id', 'rfqs.firstname', 'rfqs.lastname', 'rfqs.created_at', 'rfqs.reference_number', 'rfqs.is_require_credit', 'rfq_products.category', 'rfq_products.sub_category', 'rfq_products.product', 'rfq_products.product_description', 'rfq_status.backofflice_name as status_name','rfq_status.id as rfq_status_id']);
        }

        $user_rfq = DB::table('user_rfqs')->get(['rfq_id']);
        $users = [];
        foreach ($user_rfq as $user)
            $users[] = $user->rfq_id;

        return view('admin/rfq/list', ['rfqs' => $rfq, 'rfq_user' => $users]);
    }


    function rfqExportExxcel()
    {
        ob_end_clean();
        ob_start();

        /**begin: system log**/
        Rfq::bootSystemView(new Rfq(), 'Rfq Export', SystemActivity::VIEW);
        /**end: system log**/
        return Excel::download(new RfqExport, 'rfq.xlsx');

        ob_flush();

    }

    function rfqquotesExportExxcel()
    {
        ob_end_clean();
        ob_start();

        /**begin: system log**/
        Rfq::bootSystemView(new Rfq(), 'Rfq Quotes Export', SystemActivity::VIEW);
        /**end: system log**/
        return Excel::download(new RfqquotesExport, 'RFQ_with_Quotes.xlsx');

        ob_flush();

    }


    public function productSearch(Request $request)
    {
        $query = $request->get('query');
        $filterResult = Product::where('name', 'LIKE', '%'. $query. '%')->get();

        return response()->json($filterResult);
    }
    function getSub($id)
    {
        $sub_category = SubCategory::where('category_id',$id)->where('is_deleted',0)->get();
        return response()->json($sub_category);
    }
    function edit($id)
    {
        $id = Crypt::decrypt($id);

        $rfq_id = Rfq::find($id);
        $rfqProduct = RfqProduct::where('rfq_id',$id)->get();
        $maxRfqProduct = RfqMaximumProducts::where('rfq_id',$id)->first();
        $rfqStatus = RfqStatus::all()->where('is_deleted', 0);
        $category = Category::where(['categories.is_deleted' => 0, 'categories.status' => 1])->orderBy('name', 'ASC')->get();

        //Agent category permissions
        if (Auth::user()->hasRole('agent')) {

            $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();

            $category = Category::all()->whereIn('id', $assignedCategory)->where('is_deleted',0)->where('status', 1);

        }

        $subCategory = SubCategory::all()->where('is_deleted', 0)->where('status', 1);
        $units = Unit::all()->where('is_deleted', 0);
        $activities = RfqActivity::where('rfq_id',$id)->where('is_deleted','0')->orderBy('id','DESC')->get();
        $rfq = Rfq::where('id',$id)->where('is_deleted','0')->first();
        $rfqGroup = Groups::where('id',$rfq->group_id)->get(['target_quantity','reached_quantity','achieved_quantity'])->first();
        $all_rfqs = RfqProduct::where('rfq_id', $id)->join('units', 'rfq_products.unit_id', '=', 'units.id')->get(['rfq_products.id as id', 'rfq_products.category', 'rfq_products.category_id', 'rfq_products.sub_category_id as product_sub_category_id', 'rfq_products.sub_category as product_sub_category', 'rfq_products.product_id', 'rfq_products.product as product_name', 'rfq_products.quantity', 'rfq_products.product_description', 'units.name as unit_name', 'units.id as unit']);
        $all_attachments = RfqAttachment::where('rfq_id', $id)->get();
        $buyer_id = UserRfq::select('user_id')->where('rfq_id',$id)->first()['user_id'];
        $userAddress = UserAddresse::where('company_id', $rfq->company_id)->where('is_deleted', 0)->orderBy('address_name')->get();
        $states = State::where('country_id',CountryOne::DEFAULTCOUNTRY)->get();
        //find maximum number of product to be added
        $max_product = Settings::where('key', 'multiple_rfq_max_added_product')->first()->value;
        if (!empty($maxRfqProduct->max_products)){
            $maximum_product = intval($max_product) <= $maxRfqProduct->max_products ? $maxRfqProduct->max_products : intval($max_product);
        } else {
            $maximum_product = intval($max_product) <= 5 ? 5 : intval($max_product);
        }
        //find maximum number of attachments to be added
        $max_attachments = Settings::where('key', 'multiple_rfq_attachments')->first()->value;

        /**begin: system log**/
        $rfq_id->bootSystemView(new Rfq(), 'Rfq', SystemActivity::EDITVIEW, $rfq_id->id);
        /**end: system log**/
        $status_name ='';
        $authUser = Auth::user();
        if ($authUser->hasRole('supplier')) {
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $quoteCount=$rfq->rfqQuotes->where('supplier_id', $supplier_id)->count();
            if($rfq->rfqStatus->id=='2' && $quoteCount == 0){
                $status_name = 'RFQ Received';
            }else if($quoteCount > 0 &&  $rfq->rfqStatus->id == '1'){
                $status_name = 'RFQ Response Sent';
            }
        }
        else if($authUser->hasRole('admin')){
            $quoteCount=$rfq->rfqQuotes->count();
            if($rfq->rfqStatus->id=='2' && $quoteCount == 0){
                $status_name = 'RFQ Received';
            }else if($quoteCount > 0 &&  $rfq->rfqStatus->id == '1'){
                $status_name = 'RFQ Response Sent';
            }
        }
        //dd($status_name);
        $creditDays = CreditDays::getAllActiveCreditDays();
        return view('admin/rfq/rfqEdit', ['creditDays'=>$creditDays,'userAddress'=>$userAddress,'rfq' => $rfq,'rfqProduct' => $rfqProduct,'category' => $category,'subCategory'=>$subCategory,'units' => $units,'rfqStatus' => $rfqStatus,'rfqactivities' => $activities, 'productRfq' => $all_rfqs, 'states' => $states,'rfqGroup'=>$rfqGroup, 'max_product' => $maximum_product,'max_attachments' =>$max_attachments,'rfq_attachments' =>$all_attachments,'status_name' =>$status_name]);



    }
    function update(Request $request)
    {
        /*$category = Category::where('is_deleted', 0)->where('id',$request->category)->first();
        $subCategory = SubCategory::where('is_deleted', 0)->where('id',$request->sub_category)->first();*/
        $getRfq = Rfq::where('id',$request->id)->where('is_deleted', 0)->first();
        $getRfqAttachments = RfqAttachment::where('rfq_id',$request->id)->get();
        $oldRfqAttachment = $getRfqAttachments->implode('attached_document', ', ');
        $getRfqProduct = RfqProduct::where('rfq_id',$request->id)->where('is_deleted', 0)->get();
        $oldRfqQuantity = $getRfqProduct[0]->quantity;
        $input['user_id'] = Auth::user()->id;
        $input['rfq_id'] = $request->id;
        $change = 0;
        $rfqAttachmentFilePath = '';
        $termsconditionsFilePath = '';
        if($request->firstname && $getRfq->firstname !== $request->firstname){
            $input['key_name'] = 'firstname';
            $input['old_value'] = $getRfq->firstname??'';
            $input['new_value'] = $request->firstname;
            $change = 1;
            RfqActivity::create($input);

        }
        if($getRfq->is_require_credit !== (int)$request->is_require_credit){
            $input['key_name'] = 'is require credit';
            $input['old_value'] = $getRfq->is_require_credit??'';
            $input['new_value'] = $request->is_require_credit ?? 0;
            RfqActivity::create($input);
            $change = 1;

        }
        if($request->lastname && $getRfq->lastname !== $request->lastname){
            $input['key_name'] = 'lastname';
            $input['old_value'] = $getRfq->lastname??'';
            $input['new_value'] = $request->lastname;
            $change = 1;

            RfqActivity::create($input);

        }
        if($request->email && $getRfq->email !== $request->email){
            $input['key_name'] = 'email';
            $input['old_value'] = $getRfq->email??'';
            $input['new_value'] = $request->email;
            RfqActivity::create($input);
            $change = 1;

        }
        if($request->mobile && $getRfq->mobile !== $request->mobile){
            $input['key_name'] = 'mobile';
            $input['old_value'] = $getRfq->mobile??'';
            $input['new_value'] = $request->mobile;
            RfqActivity::create($input);
            $change = 1;

        }
        if($request->address_name && $getRfq->address_name != $request->address_name){
            $input['key_name'] = 'address_name';
            $input['old_value'] = $getRfq->address_name??'';
            $input['new_value'] = $request->address_name;
            RfqActivity::create($input);
            $change = 1;
        }
        if($request->address_line_1 && $getRfq->address_line_1 != $request->address_line_1){
            $input['key_name'] = 'address_line_1';
            $input['old_value'] = $getRfq->address_line_1??'';
            $input['new_value'] = $request->address_line_1;
            RfqActivity::create($input);
            $change = 1;
        }
        if($request->address_line_2 && $getRfq->address_line_2 !== $request->address_line_2){
            $input['key_name'] = 'address_line_2';
            $input['old_value'] = $getRfq->address_line_2??'';
            $input['new_value'] = $request->address_line_2;
            RfqActivity::create($input);
            $change = 1;
        }
        if($request->city && $getRfq->city != $request->city){
            $input['key_name'] = 'city';
            $input['old_value'] = $getRfq->city??'';
            $input['new_value'] = $request->city;
            RfqActivity::create($input);
            $change = 1;
        }
        if($request->sub_district && $getRfq->sub_district !== $request->sub_district){
            $input['key_name'] = 'sub_district';
            $input['old_value'] = $getRfq->sub_district??'';
            $input['new_value'] = $request->sub_district;
            RfqActivity::create($input);
            $change = 1;
        }
        if($request->district && $getRfq->district !== $request->district){
            $input['key_name'] = 'district';
            $input['old_value'] = $getRfq->district??'';
            $input['new_value'] = $request->district;
            RfqActivity::create($input);
            $change = 1;
        }
        if($request->state && $getRfq->state != $request->state){
            $input['key_name'] = 'state';
            $input['old_value'] = $getRfq->state??'';
            $input['new_value'] = $request->state;
            RfqActivity::create($input);
            $change = 1;
        }
        if($request->pincode && $getRfq->pincode != $request->pincode){
            $input['key_name'] = 'pincode';
            $input['old_value'] = $getRfq->pincode??'';
            $input['new_value'] = $request->pincode;
            RfqActivity::create($input);
            $change = 1;
        }
        if($getRfq->rental_forklift !== (int)$request->rental_forklift){
            $input['key_name'] = 'rental forklift';
            $input['old_value'] = $getRfq->rental_forklift??'';
            $input['new_value'] = $request->rental_forklift ?? 0;
            RfqActivity::create($input);
            $change = 1;

        }
        if($getRfq->unloading_services !== (int)$request->unloading_services){
            $input['key_name'] = 'unloading services';
            $input['old_value'] = $getRfq->unloading_services??'';
            $input['new_value'] = $request->unloading_services ?? 0;
            RfqActivity::create($input);
            $change = 1;

        }
        if($request->attached_document){
            $input['key_name'] = 'rfq attachment';
            $input['old_value'] = $oldRfqAttachment??'';
            $input['new_value'] = count($request->attached_document).' Files';
            RfqActivity::create($input);
            $change = 1;
        }
        if($request->termsconditions_file && $getRfq->termsconditions_file !== $request->termsconditions_file){
            $input['key_name'] = 'terms & conditions document';
            $input['old_value'] = $getRfq->termsconditions_file??'';
            $input['new_value'] = $request->termsconditions_file;
            RfqActivity::create($input);
            $change = 1;

        }
        if($request->comment && $getRfqProduct[0]->comment !== $request->comment){
            $input['key_name'] = 'comment';
            $input['old_value'] = $getRfqProduct[0]->comment??'';
            $input['new_value'] = $request->comment??'';
            RfqActivity::create($input);
            $change = 1;

        }

        /**
         * Vrutika Rana 25/07/2022
         * add Multiple RFQ Attachments start
         */
        if ($request->file('attached_document')) {
            foreach ($request->attached_document as $attachment) {
                $rfqAttachmentFileName = Str::random(5) . '_' . time() . '_' . $attachment->getClientOriginalName();
                $rfqAttachmentFilePath = $attachment->storeAs('uploads/rfq_docs/'.$getRfq->reference_number , $rfqAttachmentFileName, 'public');
                $rfqAttachmentArray[] = array(
                    'rfq_id' => $request->id,
                    'attached_document' => $rfqAttachmentFilePath
                );
            }
            RfqAttachment::insert($rfqAttachmentArray);
        }
        if ($request->file('termsconditions_file')) {
            $termsconditionsFileName = Str::random(5) . '_' . time() . 'termsconditions_file_' . $request->file('termsconditions_file')->getClientOriginalName();
            $termsconditionsFilePath = $request->file('termsconditions_file')->storeAs('uploads/rfq_docs/rfq_tcdoc', $termsconditionsFileName, 'public');
            if (!empty($getRfq->termsconditions_file)) {
                Storage::delete('/public/' . $getRfq->termsconditions_file);
            }
        }else{
            $termsconditionsFilePath =$request->oldtermsconditions_file;
        }
        //update multiple rfq

        $product_details = json_decode($request->product_details);
        $old_keys = $getRfqProduct->pluck('id')->toArray();
        $exits_product_key = array_reduce($product_details, function($carry, $item) {
            if (!isset($item->custom_add)){ $carry[] = $item->id; }
            return $carry;
        });

        $diff_values = array_diff($old_keys, $exits_product_key??[]);
        if (!empty($product_details)){
            $diff_values = array_diff($old_keys, $exits_product_key??[]);
            if (!empty($diff_values)){
                foreach ($diff_values as $delete_key){
                    $product_find = RfqProduct::find($delete_key);
                    $input['key_name'] = 'Remove Product';
                    $input['old_value'] = '';
                    $input['new_value'] = $product_find->product;
                    RfqActivity::create($input);
                    $change = 1;
                    RfqProduct::where('id', $delete_key)->delete();
                }
            }
            foreach ($product_details as $product_detail){

                if (isset($product_detail->custom_add) && $product_detail->custom_add == 1){
                    $add_product = new RfqProduct;
                    $add_product->rfq_id = $input['rfq_id'];
                    $add_product->category = !empty($request->category) ? $request->category : $request->product_category;
                    $add_product->category_id = $request->category_id;
                    $add_product->sub_category = $product_detail->product_sub_category;
                    $add_product->sub_category_id = $product_detail->product_sub_category_id;
                    $add_product->product_description = $product_detail->product_description;
                    $add_product->product = $product_detail->product_name;
                    $add_product->product_id = $product_detail->product_id;
                    $add_product->quantity = $product_detail->quantity;
                    $add_product->unit_id = $product_detail->unit;
                    $add_product->comment = $request->comment;
                    $add_product->expected_date = Carbon::createFromFormat('d-m-Y', $request->expected_date)->format('Y-m-d');
                    $add_product->save();
                    $input['key_name'] = 'New Product';
                    $input['old_value'] = '';
                    $input['new_value'] = $product_detail->product_name;
                    RfqActivity::create($input);
                    $change = 1;
                } else {

                    $product_rfq = RfqProduct::find($product_detail->id);
                    if(!empty($product_rfq)) {
                        $product_rfq = RfqProduct::find($product_detail->id);
                        if($product_detail->product_name && $product_rfq->product !== $product_detail->product_name){
                            $input['key_name'] = 'product name';
                            $input['old_value'] = $product_rfq->product_name??'';
                            $input['new_value'] = $product_detail->product_name;
                            RfqActivity::create($input);
                            $change = 1;
                        }
                        if($request->expected_date && strtotime($product_rfq->expected_date) !== strtotime($request->expected_date)){
                            $input['key_name'] = 'expected date';
                            $input['old_value'] = $getRfq->expected_date??'';
                            $input['new_value'] = Carbon::createFromFormat('d-m-Y', $request->expected_date)->format('Y-m-d');
                            RfqActivity::create($input);
                            $change = 1;
                        }

                        if($product_detail->product_description && $product_rfq->product_description !== $product_detail->product_description){
                            $input['key_name'] = 'product description';
                            $input['old_value'] = $product_rfq->product_description??'';
                            $input['new_value'] = $product_detail->product_description;
                            RfqActivity::create($input);
                            $change = 1;
                        }
                        if($product_detail->unit && $product_rfq->unit_id !==(int)$product_detail->unit){
                            $input['key_name'] = 'unit';
                            $input['old_value'] = $product_rfq->unit_id ??'';
                            $input['new_value'] = $product_detail->unit;
                            RfqActivity::create($input);
                            $change = 1;
                        }
                        if($product_detail->quantity && (int)$product_rfq->quantity !== (int)$product_detail->quantity){
                            $input['key_name'] = 'quantity';
                            $input['old_value'] = $product_rfq->quantity??'';
                            $input['new_value'] = $product_detail->quantity;
                            RfqActivity::create($input);
                            $change = 1;
                        }
                        if($request->product_category== "0"){
                            $category = $request->othercategory;
                            if($request->othercategory && $request->product_category !== $request->othercategory){
                                $input['key_name'] = 'category';
                                $input['old_value'] = $request->product_category??'';
                                $input['new_value'] = $request->othercategory;
                                RfqActivity::create($input);
                                $change = 1;

                            }
                        }else{
                            $category = $request->product_category;
                            if($request->product_category && $product_rfq->category !== $request->product_category){
                                $input['key_name'] = 'category';
                                $input['old_value'] = $product_rfq->category??'';
                                $input['new_value'] = $request->product_category;
                                RfqActivity::create($input);
                                $change = 1;

                            }
                        }
                        if($request->sub_category){
                            $sub_category = $product_detail->product_sub_category;
                            if($product_detail->product_sub_category && $product_rfq->sub_category !== $product_detail->product_sub_category){
                                $input['key_name'] = 'sub category';
                                $input['old_value'] = $product_rfq->sub_category??'';
                                $input['new_value'] = $product_detail->product_sub_category;
                                RfqActivity::create($input);
                                $change = 1;

                            }
                        }
                        /*else{
                            $sub_category = 'Other';
                            if($product_rfq->sub_category !== 'Other'){
                                $input['key_name'] = 'sub category';
                                $input['old_value'] = $product_rfq->sub_category??'';
                                $input['new_value'] = 'Other';
                                RfqActivity::create($input);
                                $change = 1;

                            }
                        }*/
                        $rfqProducts = array(
                            'id' => $product_detail->id,
                            'category' => $product_detail->category,
                            'category_id' => $product_detail->category_id??0,
                            'sub_category' => $product_detail->product_sub_category ?? $product_rfq->sub_category,
                            'sub_category_id' => $product_detail->product_sub_category_id ?? 0,
                            'product_description' => $product_detail->product_description ?? $product_rfq->product_description,
                            'product' => $product_detail->product_name ?? $product_rfq->product,
                            'product_id' => $product_detail->product_id??0,
                            'quantity' => $product_detail->quantity ?? $product_rfq->quantity,
                            'unit_id' => $product_detail->unit ?? $product_rfq->unit_id,
                            'expected_date' => Carbon::createFromFormat('d-m-Y', $request->expected_date)->format('Y-m-d')
                        );
                        RfqProduct::where('id', $product_detail->id)->where('is_deleted', '0')->update($rfqProducts);
                    }
                }
            }
        }
        $updateRfq = [
            'id' => $request->id,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'address_id' => $request->useraddress_id,
            'address_name' => $request->address_name,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->cityId==UserAddresse::OtherCity ? $request->city : '',
            'sub_district' => $request->sub_district,
            'district' => $request->district,
            'state' => $request->cityId==UserAddresse::OtherCity ? $request->city : '',
            'pincode' => $request->pincode,
            'rental_forklift' => $request->rental_forklift,
            'is_require_credit' => $request->is_require_credit ?? 0,
            'unloading_services' => $request->unloading_services,
            'attached_document' => $rfqAttachmentFilePath ? $rfqAttachmentFilePath : '',
            'termsconditions_file' => $termsconditionsFilePath ? $termsconditionsFilePath : '',
            'city_id'   =>  $request->cityId,
            'state_id'  =>  $request->stateId
        ];

        $updateRfq['payment_type'] = 0;
        if($request->is_require_credit == 1)
        {
            $updateRfq['payment_type'] = 1;
            if ($request->credit_days_id == "lc" ||  $request->credit_days_id == "skbdn")
            {
                $updateRfq['payment_type'] = 3;
            }
            if ($request->credit_days_id == "skbdn")
            {
                $updateRfq['payment_type'] = 4;
            }
        }
        $updateRfq['credit_days'] = $updateRfq['payment_type'] == 1 ? $request->credit_days_id : null;

        Rfq::find($request->id)->update($updateRfq);
        $userrfq = UserRfq::where('rfq_id',$request->id)->where('is_deleted', 0)->first();

        /**begin: system log**/
        Rfq::bootSystemActivities();
        /**end: system log**/

		//Start store "other" address in user_addresses
        if ($request->useraddress_id == 0){
            $userAddress = new UserAddresse();
            $userAddress->user_id = $userrfq->user_id;
            $userAddress->address_name = $request->address_name;
            $userAddress->address_line_1 = $request->address_line_1;
            $userAddress->address_line_2 = $request->address_line_2;
            $userAddress->sub_district = $request->sub_district;
            $userAddress->district = $request->district;
            $userAddress->city = $request->cityId==UserAddresse::OtherCity ? $request->city : '';
            $userAddress->state = $request->stateId==UserAddresse::OtherCity ? $request->state : '';
            $userAddress->pincode = $request->pincode;
            $userAddress->city_id = $request->cityId;
            $userAddress->state_id = $request->stateId;
            $userAddress->save();
            $rfqAddressID = $userAddress->id;
            Rfq::where('id',$request->id)->update(['address_id'=>$rfqAddressID]);
            /**begin: system log**/
            UserAddresse::bootSystemActivities();
            /**end: system log**/
        }
		//End store "other" address in user_addresses

        if($userrfq && $change == 1){
            $commanData = [];
            if((int)Auth::user()->role_id == 1){
                $commanData = array('rfq_number' => $getRfq->reference_number, 'updated_by' => 'Blitznet Team','icons' => 'fa-user');
            }else{
                $commanData = array('rfq_number' => $getRfq->reference_number, 'updated_by' => Auth::user()->full_name, 'icons' => 'fa-user');
            }
            buyerNotificationInsert($userrfq->user_id, 'Update RFQ', 'buyer_update_rfq', 'rfq', $request->id, $commanData);
            broadcast(new BuyerNotificationEvent());
            $this->adminUpdateNotificationChange($request->id);
        }

        //group rfq qty update
        $groupId = null;
        // if(isset($request->groupId) && isset($request->product_details)) {
        if(isset($request->groupId) && $request->groupId > 0  && isset($request->product_details)) {
            $groupId = $request->groupId;
            $productDetails = json_decode($request->product_details);
            if(sizeof($productDetails) == 1){
                $productDetails = $productDetails[0];
                $group = Groups::find($request->groupId);
                $group->reached_quantity = ($group->reached_quantity - $oldRfqQuantity) + $productDetails->quantity;
                $group->save();
            }
        }

		$userAddress = UserAddresse::all()->where('user_id', $userrfq->user_id)->where('is_deleted', 0)->sortByDesc('id');
		$seletedAddress = ['address_name' => $request->address_name, 'address_line_1' => $request->address_line_1, 'address_line_2' => $request->address_line_2];

		$max_product = Settings::where('key', 'multiple_rfq_max_added_product')->first()->value;
        $rfq_max_product = RfqMaximumProducts::where('rfq_id', $request->id)->first();
        if (!empty($rfq_max_product)){
            if ($max_product>=$rfq_max_product->max_products){
                RfqMaximumProducts::where(['rfq_id'=>$request->id])->update(['max_products'=>$max_product]);
            }
        }

        return response()->json(array('success' => true, 'userAddress' => $userAddress, 'seletedAddress' => $seletedAddress, 'groupId' => $groupId));
        // return redirect()->route('rfq-list');
    }

    public function adminUpdateNotificationChange($rfq_id){
        if (Auth::user()->role_id == Role::ADMIN){
            $sendAdminNotification[] = array('user_id' => Auth::user()->id, 'admin_id' => Auth::user()->id, 'user_activity' => 'Edit RFQ', 'translation_key' => 'rfq_edit_notification', 'notification_type' => 'rfq', 'notification_type_id'=> $rfq_id, 'created_at' => Carbon::now());
            Notification::insert($sendAdminNotification);
        } else if (Auth::user()->role_id == Role::BUYER || Auth::user()->role_id == Role::SUPPLIER){
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $getAllAdmin = getAllAdmin();
            $sendAdminNotification = [];
            if (!empty($getAllAdmin)){
                foreach ($getAllAdmin as $key => $value){
                    $sendAdminNotification[] = array('user_id' => auth()->user()->id, 'admin_id' => $value, 'user_activity' => 'Edit RFQ', 'translation_key' => 'rfq_edit_notification', 'notification_type' => 'rfq', 'notification_type_id'=> $rfq_id, 'created_at' => Carbon::now());
                }
                Notification::insert($sendAdminNotification);
            }
            $sendSupplierNotification = array('user_id' => Auth::user()->id, 'supplier_id' => $supplier_id, 'user_activity' => 'Edit RFQ', 'translation_key' => 'rfq_edit_notification', 'notification_type' => 'rfq', 'notification_type_id'=> $rfq_id, 'created_at' => Carbon::now());
            Notification::insert($sendSupplierNotification);
        }
        broadcast(new rfqsEvent());
    }

    function rfqDetail($id)
    {
        $loginSupplierDetails = Supplier::find(getSupplierIdByUser(Auth::user()->id));
        $authUserRoleId = Auth::check()? Auth::user()->role_id : 1;
        $rfq = Rfq::find($id);
        $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
        $status='';

        $authUser = Auth::user();

        $productExistRFQ = '';
        if ($authUser->hasRole('supplier')) {
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $quoteCount=$rfq->rfqQuotes->where('supplier_id', $supplier_id)->count();

            /* check RfQ product added in supplier side added start */
            $total_rfq_products = RfqProduct::where('is_deleted',0)->where('rfq_id', $id)->pluck('product_id')->toArray();
            $totalRfqRelatedSupplierProducts = SupplierProduct::where('is_deleted',0)->where('supplier_id',$supplier_id)->whereIn('product_id', $total_rfq_products)->pluck('product_id')->toArray();
            $productExistRFQ = 'NOT';
            if(count($totalRfqRelatedSupplierProducts)>0){
            $productExistRFQ=(count(array_unique($total_rfq_products))==count(array_unique($totalRfqRelatedSupplierProducts)))?'ALL PRODUCT':'NOT ALL PRODUCT';
            }
            /* check RfQ product added in supplier side added end */


            if($rfq->rfqStatus()->value('id')=='2' && $quoteCount == 0){
               $status = 'RFQ Received';
            }else if($quoteCount > 0 &&  $rfq->rfqStatus()->value('id') == '1'){
                $status = 'RFQ Response Sent';
            }
        }else if($authUser->hasRole('admin')){
            $quoteCount=$rfq->rfqQuotes->count();
            if($rfq->rfqStatus()->value('id')=='2' && $quoteCount == 0){
               $status = 'RFQ Received';
            }else if($quoteCount > 0 &&  $rfq->rfqStatus()->value('id') == '1'){
                $status = 'RFQ Response Sent';
            }

        }

        //Below is the old code for getting the product availability
        /*$productExist = $rfq->rfqProduct()
                        ->join('sub_categories', 'rfq_products.sub_category_id', '=', 'sub_categories.id')
                        ->join('products', function ($join) {
                            $join->on('sub_categories.id', '=', 'products.subcategory_id');
                        })
                        ->count();*/

        //Below is the old code for getting the product availability
        /*$suppliers = $rfq->rfqProduct()
                    ->join('sub_categories', 'rfq_products.sub_category', '=', 'sub_categories.name')
                    ->join('products', 'rfq_products.product_id', '=', 'products.id')
                    ->join('supplier_products', 'products.id', '=', 'supplier_products.product_id')
                    ->join('suppliers', 'supplier_products.supplier_id', '=', 'suppliers.id')
                    ->where('suppliers.status', 1)
                    ->where('suppliers.is_deleted', 0)
                    ->where('supplier_products.is_deleted', 0)
                    ->select('suppliers.id as supplierId', 'suppliers.name as supplierName')->get();*/

        $productExist = RfqProduct::with(['product'])->where('rfq_id',$rfq->id)->count();

        $suppliers = Supplier::with(['supplierProducts.rfqProduct','supplierProducts'])->whereHas('supplierProducts.rfqProduct', function($q) use($rfq){
            $q->where('rfq_id',$rfq->id);
        })
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->select('id as supplierId', 'name as supplierName')
            ->get();

        $user_id = $rfq->rfqUser()->value('user_id');

        $orderPlaced = 0;
        $orderCount = DB::table('orders')->whereIn('quote_id', $rfq->rfqQuotes()->pluck('id')->toArray())->count();
        if ($orderCount > 0) {
            $orderPlaced = 1;
        }
        $company = '';
        if ($user_id) {
            $company = $rfq->companyDetails()->value('name');
        }
        $group = GroupMember::where('rfq_id',$id)->first(['group_id']);

        //$rfq->id = Crypt::encrypt($rfq->id);

        $all_rfqs = RfqProduct::where('rfq_id', $id)->join('units', 'rfq_products.unit_id', '=', 'units.id')->get(['rfq_products.category','rfq_products.category_id', 'rfq_products.sub_category', 'rfq_products.product', 'rfq_products.quantity', 'rfq_products.product_description', 'units.name as unit_name']);
        $all_rfqsAttachment = RfqAttachment::where('rfq_id', $id)->get();

        $rfqview = view('admin/rfq/rfqmodal',[
                                        'data' => $rfq,
                                        'user_id' => $user_id,
                                        'productExist' => ($productExist>0),
                                        'supplierDetail' => $suppliers,
                                        'loginSupplierDetails'=>$loginSupplierDetails, // login supplire compamy details
                                        'orderPlaced' => $orderPlaced,
                                        'all_products' => $all_rfqs,
                                        'rfq_attachments' => $all_rfqsAttachment,
                                        'group' => $group,
                                        'comp'=>$company,
                                        'authUserRoleId'=>$authUserRoleId,
                                        'productExistRFQ'=>$productExistRFQ, //product exit or not.
                                        'status'=> $status
                                    ])->render();

        /**begin: system log**/
        $rfq->bootSystemView(new Rfq(), 'Rfq', SystemActivity::RECORDVIEW, $rfq->id);
        /**end: system log**/
        return response()->json(array('success' => true, 'rfqview'=>$rfqview));
    }

    function cancelRfq(Request $request){
        $commanData = [];
        $rfq_id =crypt ::decrypt($request->rfqId);
        Rfq::where(['id'=>$rfq_id])->update(['status_id'=>3]);

        $rfq = DB::table('rfqs')
            ->join('user_rfqs', 'rfqs.id', '=', 'user_rfqs.rfq_id')
            ->where('rfqs.id', $rfq_id)
            ->orderBy('rfqs.id', 'desc')
            ->first(['rfqs.id', 'rfqs.reference_number','user_rfqs.user_id as user_id' ]);
        //Buyer notification
        if((int)Auth::user()->role_id == 1){
            $commanData = array('rfq_number' => $rfq->reference_number, 'updated_by' => 'blitznet team', 'icons' => 'fa-user');
        }else{
            $commanData = array('rfq_number' => $rfq->reference_number, 'updated_by' => Auth::user()->full_name, 'icons' => 'fa-user');
        }
        buyerNotificationInsert($rfq->user_id, 'RFQ Cancelled', 'buyer_rfq_cancel', 'rfq', $rfq->id, $commanData);

        //Admin notification
        $getAllAdmin = getAllAdmin();
            $sendAdminNotification = [];
            if (!empty($getAllAdmin)){
                foreach ($getAllAdmin as $key => $value){
                    $sendAdminNotification[] = array('user_id' => auth()->user()->id, 'admin_id' => $value, 'user_activity' => 'RFQ Cancelled', 'translation_key' => 'admin_rfq_cancel', 'notification_type' => 'rfq', 'notification_type_id'=> $rfq->id);
                }
                Notification::insert($sendAdminNotification);
            }
        broadcast(new BuyerNotificationEvent());
        broadcast(new rfqsEvent());
        return response()->json(array('success' => true));
    }
    function rfqReply($id)
    {
        $id = Crypt::decrypt($id);
        $platformCharges = OtherCharge::all()->where('charges_type', 0)->where('is_deleted', 0)->where('name', '<>' , 'Group Discount')->where('status',1);
        $logisticCharges = OtherCharge::all()->where('charges_type', 1)->where('is_deleted', 0)->where('status',1);

        $rfq = DB::table('rfqs')
            ->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
            ->join('user_rfqs', 'rfqs.id', '=', 'user_rfqs.rfq_id')
            ->join('users', 'user_rfqs.user_id', '=', 'users.id')
            ->join('companies', 'users.default_company', '=', 'companies.id')
            ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
            ->join('units', 'rfq_products.unit_id', '=', 'units.id')
            ->leftJoin('group_members','rfqs.id','=','group_members.rfq_id')
            ->where('rfqs.id', $id)
            ->orderBy('rfqs.id', 'desc')
            ->first(['rfqs.id', 'rfqs.firstname', 'rfqs.lastname', 'rfqs.phone_code', 'rfqs.mobile', 'rfqs.email', 'rfqs.created_at', 'rfqs.is_require_credit', 'rfqs.reference_number', 'rfq_products.id as rfq_product_id', 'rfq_products.category', 'rfq_products.sub_category', 'rfq_products.product', 'rfq_products.product_id', 'rfq_products.product_description', 'rfq_status.name as status_name',  'rfqs.address_line_1', 'rfqs.address_line_2', 'rfqs.sub_district', 'rfqs.district', 'rfqs.city', 'rfqs.state', 'rfqs.city_id', 'rfqs.state_id',  'rfqs.pincode', 'rfq_products.quantity', 'rfq_products.unit_id', 'units.name as unit_name', 'companies.name as company_name', 'rfq_products.expected_date', 'rfq_products.comment', 'rfqs.rental_forklift', 'rfqs.unloading_services', 'rfqs.group_id', 'rfqs.termsconditions_file', 'rfqs.company_id','rfqs.payment_type','rfqs.credit_days']);
        //Agent category permission
        // if (Auth::user()->hasRole('agent')) {
            /*
                $suppliers = DB::table('rfq_products')
                ->join('products', 'rfq_products.product_id', '=', 'products.id')
                ->leftJoin('supplier_products', 'products.id', '=', 'supplier_products.product_id')
                ->leftJoin('suppliers', 'supplier_products.supplier_id', '=', 'suppliers.id')
                ->leftJoin('group_members','rfq_products.rfq_id','=','group_members.rfq_id')
                ->leftJoin('group_suppliers','group_members.group_id','=','group_suppliers.group_id')
                ->where('supplier_products.is_deleted', 0)
                ->where('suppliers.status', 1)
                ->where('suppliers.is_deleted', 0)
                ->where('rfq_products.rfq_id', $id)
                ->groupBy('suppliers.id')
                ->select('suppliers.id as supplierId', 'suppliers.name as supplierName', 'group_suppliers.supplier_id as grpSuppId')->get();
            */
            if(!empty($rfq->group_id)){
                $suppliers = GroupSupplier::leftjoin('suppliers', 'group_suppliers.supplier_id', '=', 'suppliers.id')
                    ->where('group_suppliers.group_id',$rfq->group_id)
                    ->get(['suppliers.id as supplierId', 'suppliers.name as supplierName','group_suppliers.supplier_id as grpSuppId']);
            }else{
                $suppliers = DB::table('rfq_products')
                                ->join('products', 'rfq_products.product_id', '=', 'products.id')
                                ->leftJoin('supplier_products', 'products.id', '=', 'supplier_products.product_id')
                                ->leftJoin('suppliers', 'supplier_products.supplier_id', '=', 'suppliers.id')
                                ->where('supplier_products.is_deleted', 0)
                                ->where('suppliers.status', 1)
                                ->where('suppliers.is_deleted', 0);

                //Agent category permission
                /* if (Auth::user()->hasRole('agent')) {
                    $assignedCategory = User::getCustomPermission('category')->pluck('value')->toArray();
                    $assignedSubCategory = SubCategory::whereIn('category_id', $assignedCategory)->pluck('id')->toArray();
                    $suppliers->whereIn('products.subcategory_id', $assignedSubCategory);
                } */

                $suppliers = $suppliers->where('rfq_products.rfq_id', $id)
                ->groupBy('suppliers.id')
                ->select('suppliers.id as supplierId', 'suppliers.name as supplierName')->get();
            }

            $total_rfq_products = RfqProduct::where('rfq_id', $id)->join('units', 'rfq_products.unit_id', '=', 'units.id')->get()->toArray();

            $supplierAddresses = $supplierTermsDocument = $defaultAddress = '';
            //Supplier address and default address (When admin logged in and rfq belongs to any group)
            if (auth()->user()->role_id == 1 && !empty($rfq->group_id)) {
                $supplierId = GroupSupplier::where('group_id',$rfq->group_id)->pluck('supplier_id')->first();
                $supplierAddresses = SupplierAddress::where('supplier_id',$supplierId)->where('is_deleted', 0)->orderBy('id','DESC')->get();
                $defaultAddress = SupplierAddress::where('supplier_id',$supplierId)->where('default_address',1)->where('is_deleted', 0)->first();
            } else {
                $supplierAddresses = $defaultAddress = '';
            }
            //Supplier address and default address (When supplier logged in)
            if (auth()->user()->role_id == 3) {
                $supplier = UserSupplier::where('user_id',auth()->user()->id)->first(['supplier_id']);
                $supplierAddresses = SupplierAddress::where('supplier_id',$supplier->supplier_id)->where('is_deleted', 0)->orderBy('id','DESC')->get();
                $defaultAddress = SupplierAddress::where('supplier_id',$supplier->supplier_id)->where('default_address',1)->where('is_deleted', 0)->first();
                $supplierTermsDocument = Supplier::where('id',$supplier->supplier_id)->select('commercialCondition')->first();
            }

            $groupId = Rfq::where('id',$id)->pluck('group_id')->first();
            $achievedQty = Groups::where('id',$groupId)->pluck('achieved_quantity')->first();
            $minGroupQty = Groups::where('id',$groupId)->pluck('min_order_quantity')->first();
            $pricePerQty = Groups::where('id',$groupId)->pluck('price')->first();
            $groupOrderRange = GroupSupplierDiscountOption::where('group_id',$groupId)->get();
            //If achieved qty belong to any group then $groupDiscount and $groupDiscountPrice will not be empty
            $groupDiscount = $groupDiscountPrice = 0;
            if(isset($groupId)) {
                if(isset($achievedQty) && $achievedQty > 0) {
                    //dd($minGroupQty);
                    //When achieved qty is less than min order qty range
                    if ($achievedQty < $minGroupQty) {
                        $groupOrderData = RfqProduct::join('rfqs', 'rfq_products.rfq_id', '=', 'rfqs.id')
                            ->leftJoin('quotes', 'quotes.rfq_id', '=', 'rfqs.id')
                            ->leftJoin('orders', 'orders.quote_id', '=', 'quotes.id')
                            ->join('group_supplier_discount_options', 'rfqs.group_id', '=', 'group_supplier_discount_options.group_id')
                            ->join('groups', 'group_supplier_discount_options.group_id', '=', 'groups.id')
                            ->join('categories', 'categories.id', '=', 'rfq_products.category_id')
                            ->join('sub_categories', 'sub_categories.id', '=', 'rfq_products.sub_category_id')
                            ->join('units', 'rfq_products.unit_id', '=', 'units.id')
                            ->leftJoin('products', 'products.id', '=', 'rfq_products.product_id')
                            ->where('rfqs.group_id', $groupId)
                            ->where('rfqs.id', $id)
                            ->first(['groups.achieved_quantity', 'rfqs.group_id', 'group_supplier_discount_options.discount_price', 'group_supplier_discount_options.discount', 'groups.price as original_price', 'categories.name as category', 'sub_categories.name as sub_category', 'rfq_products.product', 'rfq_products.quantity', 'units.name as unitName', 'products.description as product_description', 'rfq_products.quantity as rfqQty']);
                    } else {
                    $groupOrderData = RfqProduct::join('rfqs', 'rfq_products.rfq_id', '=', 'rfqs.id')
                        ->leftJoin('quotes', 'quotes.rfq_id', '=', 'rfqs.id')
                        ->leftJoin('orders', 'orders.quote_id', '=', 'quotes.id')
                        ->join('group_supplier_discount_options', 'rfqs.group_id', '=', 'group_supplier_discount_options.group_id')
                        ->join('groups', 'group_supplier_discount_options.group_id', '=', 'groups.id')
                        ->join('categories', 'categories.id', '=', 'rfq_products.category_id')
                        ->join('sub_categories', 'sub_categories.id', '=', 'rfq_products.sub_category_id')
                        ->join('units', 'rfq_products.unit_id', '=', 'units.id')
                        ->leftJoin('products', 'products.id', '=', 'rfq_products.product_id')
                        ->where('rfqs.group_id', $groupId)
                        ->where('rfqs.id', $id)
                        //  ->where('orders.order_status', '>=', 3)
                        //  ->where('group_supplier_discount_options.min_quantity', '<=', $achievedQty)
                        //  ->where('group_supplier_discount_options.max_quantity', '>=', $achievedQty)
                        ->first(['groups.achieved_quantity', 'rfqs.group_id', 'group_supplier_discount_options.discount_price', 'group_supplier_discount_options.discount', 'groups.price as original_price', 'categories.name as category', 'sub_categories.name as sub_category', 'rfq_products.product', 'rfq_products.quantity', 'units.name as unitName', 'products.description as product_description', 'rfq_products.quantity as rfqQty']);
                        //dd($groupOrderRange);
                        $totalAmount = $pricePerQty * $groupOrderData['rfqQty'];
                        foreach($groupOrderRange as $value) {
                            if(($achievedQty >= $value['min_quantity'] && $achievedQty <= $value['max_quantity']) ){
                                $groupDiscount = $value['discount'];
                                //$groupDiscountPrice = $value['discount_price'];
                                $groupDiscountPrice = ($totalAmount * $groupDiscount/100);
                                //dump($groupDiscountPrice);
                            }
                        }
                    }
                } else {
                    $groupOrderData = RfqProduct::join('rfqs','rfq_products.rfq_id','=','rfqs.id')
                    ->leftJoin('quotes','quotes.rfq_id','=','rfqs.id')
                    ->leftJoin('orders','orders.quote_id','=','quotes.id')
                    ->join('group_supplier_discount_options','rfqs.group_id','=','group_supplier_discount_options.group_id')
                    ->join('groups','group_supplier_discount_options.group_id','=','groups.id')
                    ->join('categories','categories.id','=','rfq_products.category_id')
                    ->join('sub_categories','sub_categories.id','=','rfq_products.sub_category_id')
                    ->join('units','rfq_products.unit_id','=','units.id')
                    ->leftJoin('products','products.id','=','rfq_products.product_id')
                    ->where('rfqs.group_id',$groupId)
                    ->where('rfqs.id', $id)
                    ->first(['groups.achieved_quantity','rfqs.group_id','group_supplier_discount_options.discount_price','group_supplier_discount_options.discount','groups.price as original_price','categories.name as category','sub_categories.name as sub_category','rfq_products.product','rfq_products.quantity','units.name as unitName','products.description as product_description','rfq_products.quantity as rfqQty']);
                }
            } else {
                $groupOrderData = '';
            }
            $group_discount_charges = OtherCharge::where('name','Group Discount')->first(['id','name','type','addition_substraction','value_on','charges_value']);
            $countrys = CountryOne::get(['id', 'name']);
            if (!empty($defaultAddress)) {
                $states = State::where('country_id', $defaultAddress->country_id)->get(['id', 'name']);
            } else {
                $states = State::where('country_id', CountryOne::DEFAULTCOUNTRY)->get();
            }
            $tc_document = TermsCondition::first();
            $company_id = $rfq->company_id;

            $logistics_services = LogisticsService::all()->where('deleted_at',null);

            if ($rfq) {
                $paymentCharges = OtherCharge::with(['company' => function ($q) use($company_id) {$q->where(['is_delete' => 0, 'company_id' => $company_id])->select(['charge_id', 'company_id']);}])
                    ->with(['xenditCommisionFee'=>function($q) use($company_id){
                        $q->where(['company_id' => $company_id])->select(['charge_id', 'company_id','type','charges_value','is_delete','charges_type']);
                    }])
                    ->where(['charges_type' => 2, 'is_deleted' => 0, 'status' => 1])->get();
                $paymentCharges = collect($paymentCharges->toArray())->where('company','!=',null);
                return view('/admin/rfq/rfqReply', ['rfq' => $rfq, 'suppliers' => $suppliers, 'platformCharges' => $platformCharges, 'logisticCharges' => $logisticCharges, 'paymentCharges' => array_values($paymentCharges->toArray()), 'total_rfq_products' => $total_rfq_products,'supplierAddresses' => $supplierAddresses, 'defaultAddress' => $defaultAddress, 'groupOrderData' => $groupOrderData, 'group_discount_charges' => $group_discount_charges, 'states' => $states, 'countrys' => $countrys, 'minGroupQty' => $minGroupQty, 'groupDiscount'=>$groupDiscount , 'groupDiscountPrice'=>$groupDiscountPrice, 'supplierTermsDocument' => $supplierTermsDocument, 'tc_document' => $tc_document,'logistics_services' =>$logistics_services]);

            } else {
                return redirect('admin/rfq');
            }
        // }
    }

    function rfqactivity($id){
        $activities = RfqActivity::where('rfq_id',$id)->where('is_deleted','0')->orderBy('id','DESC')->get();
        $rfq = Rfq::where('id',$id)->where('is_deleted','0')->first();
        $user_rfqs = UserRfq::where('rfq_id',$id)->where('is_deleted','0')->first();

        $activityhtml =  view('admin/rfq/rfqactivities', ['rfqactivities' => $activities,'rfq'=>$rfq,'user_rfqs'=>$user_rfqs])->render();
        return response()->json(array('success' => true, 'activityhtml' => $activityhtml));
    }

    //Download rfq attachment by rfq id
    public function downloadRfqAttachmentFile(Request $request) {
        $rfqFile = RfqAttachment::where('rfq_id', $request->rfq_id)->get();
        if ($rfqFile->isNotEmpty()){
            $zip = new ZipArchive;

            $fileName = 'public/uploads/rfq_docs/'.$request->ref_no.'.zip';
            //dd(Storage::path($fileName));
            if ($zip->open(Storage::path($fileName), ZipArchive::CREATE) === TRUE) {

                $files = File::files(Storage::path('public/uploads/rfq_docs/'.$request->ref_no));

                foreach ($files as $key => $value) {
                    $relativeNameInZipFile = basename($value);
                    $zip->addFile($value, $relativeNameInZipFile);
                }
                $zip->close();
            }
            ob_end_clean();
            $headers = ["Content-Type"=>"application/zip"];
            return response()->download(Storage::path($fileName),$request->ref_no.'.zip', $headers)->deleteFileAfterSend(true);
        }else{
            return response()->json(array('success' => false));
        }
    }

    //Download RFQ Single Document
    function downloadRfqAttachment(Request $request){
        $rfqId = $request->rfq_id;
        if ($request->fieldName != 'attached_document'){
            $attachment = Rfq::where('id', $rfqId)->pluck($request->fieldName)->first();
        }else{
            $attachment = RfqAttachment::where('id', $rfqId)->pluck($request->fieldName)->first();
        }
        if (!empty($attachment)){
            ob_end_clean();
            $headers = array('Content-Type: image/*, application/pdf');
            return Storage::download('/public/' . $attachment, '', $headers);
            //return response()->download(public_path('storage/'.$image));
        }
        return response()->json(array('success' => false));
    }

    //Delete RFQ Attachment
    function deleteRfqAttachment(Request $request) {
        $attachment = RfqAttachment::where('id',$request->id)->first();
        $columnName = $attachment->attached_document;
        if (isset($columnName) && !empty($columnName)) {
            Storage::delete('/public/' . $columnName);
            RfqAttachment::where('id', $request->id)->delete();
        }
        $rfqAttachments = RfqAttachment::where('rfq_id',$request->rfqId)->get();
        $rfqattachId = '';
        if(count($rfqAttachments)>1){
            $rfqattach = count($rfqAttachments).' Files';
        }else{
            $rfqFileTitle = Str::substr($rfqAttachments[0]->attached_document,43);
            $extension_rfq_file = getFileExtension($rfqFileTitle);
            $rfq_file_filename = getFileName($rfqFileTitle);
            if(strlen($rfq_file_filename) > 10){
                $rfqattach = Str::substr($rfq_file_filename,0,10).'...'.$extension_rfq_file;
            } else {
                $rfqattach = $rfq_file_filename.$extension_rfq_file;
            }
            $rfqattachId = $rfqAttachments[0]->id;
        }
        $countAttachments = count($rfqAttachments);
        return response()->json(array('success' => true,'rfqAttachments'=>$rfqattach,'rfqAttachmentsId'=>$rfqattachId,'countAttachments'=>$countAttachments));
    }

    function getrfqProductReply($supplier_id, $rfq_id, $rfq_product=''){
        // dd($rfq_id.' getrfqProductReply');

        $groupId = Rfq::where('id',$rfq_id)->pluck('group_id')->first();
        $total_rfq_products = RfqProduct::where('rfq_id', $rfq_id)->join('units', 'rfq_products.unit_id', '=', 'units.id')->get(['rfq_products.*', 'units.id as unit_id', 'units.name'])->toArray();
        $product_list = DB::table('rfq_products')
            ->join('products', 'rfq_products.product_id', '=', 'products.id')
            ->leftJoin('supplier_products', 'products.id', '=', 'supplier_products.product_id')
            ->leftJoin('suppliers', 'supplier_products.supplier_id', '=', 'suppliers.id')
            ->where('supplier_products.is_deleted', 0)
            ->where('suppliers.status', 1)
            ->where('suppliers.is_deleted', 0)
            ->where('rfq_products.rfq_id', $rfq_id)
            ->where('supplier_products.supplier_id', $supplier_id)->pluck('rfq_products.product_id')->toArray();
        $quote = Quote::where('rfq_id',$rfq_id)->where('status_id','<=',2)->first();
        $view = view('admin/rfq/rfqProductDetails',[
            'total_rfq_products' => $total_rfq_products, 'edit_product' =>$rfq_product,
            'product_lists' => $product_list,'quote'=>$quote, 'groupId'=>$groupId])->render();
        return response()->json(array('success' => true,'html'=>$view));
    }
}
