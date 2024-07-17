<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Payment\XenditController;
use App\Jobs\GroupLeaveBuyerJob;
use App\Jobs\GroupLeaveJob;
use App\Jobs\SetExpireOnInvoice;
use App\Models\Category;
use App\Models\GroupActivity;
use App\Models\GroupImages;
use App\Models\GroupMember;
use App\Models\MongoDB\GroupChatMember;
use App\Models\Order;
use App\Models\GroupPlaceOrderLog;
use App\Models\Groups;
use App\Models\GroupSupplierDiscountOption;
use App\Models\Quote;
use App\Models\Rfq;
use App\Models\RfqProduct;
use App\Models\Settings;
use App\Models\SubCategory;
use App\Models\UserCompanies;
use App\Models\Product;
use App\Models\Unit;
use App\Models\GroupStatus;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use PHPUnit\TextUI\XmlConfiguration\Group;
use Illuminate\Contracts\Encryption\DecryptException;

class GroupTradingController extends Controller
{
    //Group Trading Main page
    public function index() {
        //Group Categories
        $group_categories = Groups::select('category_id')->groupBy('category_id')->orderBy('category_id','ASC')->get()->pluck('category_id')->toArray();
        $all_categories = Category::select('id')->where('is_deleted',0)->groupBy('name')->get()->pluck('id')->toArray();
        $catResult = array_intersect($all_categories,$group_categories);
        $categories = Category::select('id','name as category_name')->whereIn('id',$catResult)->where('is_deleted',0)->groupBy('name')->get();

        //Group Subcategories
        $group_subCategories = Groups::select('subCategory_id')->groupBy('subCategory_id')->orderBy('subCategory_id','ASC')->get()->pluck('subCategory_id')->toArray();
        $all_subCategories = SubCategory::select('id')->where('is_deleted',0)->groupBy('name')->get()->pluck('id')->toArray();
        $subCatResult = array_intersect($all_subCategories,$group_subCategories);
        $subCategories = SubCategory::select('id','name as subCategoryName')->whereIn('id',$subCatResult)->where('is_deleted',0)->groupBy('name')->get();

        //Group Product
        $group_products = Groups::select('product_id')->groupBy('product_id')->orderBy('product_id','ASC')->get()->pluck('product_id')->toArray();
        $all_products = Product::select('id')->where('is_deleted',0)->groupBy('name')->get()->pluck('id')->toArray();
        $productResult = array_intersect($all_products,$group_products);
        $products = Product::select('id','name as productName')->whereIn('id',$productResult)->where('is_deleted',0)->groupBy('name')->get();

        $discounts = GroupSupplierDiscountOption::select('id','discount')->groupBy('discount')->orderBy('discount','ASC')->get();
        $remainingDays = Groups::select('id','end_date')->whereDate('end_date', '>', Carbon::now())->groupBy('end_date')->orderBy('end_date','ASC')->get();
        $groups = Groups::with('groupMembersMultiple')
            ->leftjoin('group_images','groups.id','=','group_images.group_id')
            ->leftjoin('units','groups.unit_id','=','units.id')
            ->leftjoin('products','groups.product_id','=','products.id')
            ->leftjoin('group_supplier_discount_options','groups.id','=','group_supplier_discount_options.group_id')
            //->skip($request->paginate * $group_count)->take($group_count)
            ->whereDate('groups.end_date', '>=', Carbon::now())
            ->whereNotIn('groups.group_status', [3, 4])
            ->groupBy('groups.id')
            ->orderBy('groups.id', 'desc')
            ->orderBy('group_images.id', 'desc')
            ->get(['groups.id','groups.category_id as grpCatId','groups.subCategory_id as grpSubCatId','groups.product_id as grpProdId', 'groups.unit_id as grpUnitId', 'groups.name as groupName', 'groups.target_quantity', 'groups.achieved_quantity', 'groups.reached_quantity', 'groups.end_date', 'group_images.image as groupImg', 'units.name as unit', 'products.name as productName',DB::raw('(MAX(group_supplier_discount_options.discount)) AS max_discount'),'groups.group_status']);
        // dd($groups->toArray());
        return view('group_trading/group_trading_dashboard', ['calendlyMeetingLink' => env('CALENDLY_MEETING_LINK')], ['categories' => $categories, 'catResult' => $catResult, 'subCategories' => $subCategories, 'products' => $products, 'discounts' => $discounts, 'remainingDays' => $remainingDays,'groups' => $groups]);
    }

    public function frontPage(){
        return view('group_trading/group_trading', ['calendlyMeetingLink' => env('CALENDLY_MEETING_LINK')]);
    }

    //Get groups data as per search
    public function groupsData(Request $request) {
        //Cet count of groups from 'settings' table
        $group_count = Settings::where('key', 'load_more_groups')->pluck('value')->first();
        if($request->ajax()) {
            $groups = '';
            $query = $request->get('query');
            if($query != '') {
                // dd('here');
                $groups = Groups::with('groupMembersMultiple')
                ->leftjoin('group_images','groups.id','=','group_images.group_id')
                ->leftjoin('categories','groups.category_id','=','categories.id')
                ->leftjoin('sub_categories','groups.subCategory_id','=','sub_categories.id')
                ->leftjoin('products','groups.product_id','=','products.id')
                ->leftjoin('units','groups.unit_id','=','units.id')
                ->leftjoin('group_tags','groups.id','=','group_tags.group_id')
                ->leftjoin('group_supplier_discount_options','groups.id','=','group_supplier_discount_options.group_id')
                ->join('group_status','groups.group_status','=','group_status.id')
                ->skip($request->paginate * $group_count)->take($group_count)
                ->where('categories.name', 'like', '%'.$query.'%')
                ->orWhere('sub_categories.name', 'like', '%'.$query.'%')
                ->orWhere('products.name', 'like', '%'.$query.'%')
                ->orWhere('groups.name', 'like', '%'.$query.'%')
                ->orWhere('group_tags.tag', 'like', '%'.$query.'%')
                ->whereDate('groups.end_date', '>=', Carbon::now())
                ->whereNotIn('groups.group_status', [3, 4])
                ->groupBy('groups.id')
                ->orderBy('groups.id', 'desc')
                ->get(['groups.id','groups.category_id as grpCatId','groups.subCategory_id as grpSubCatId','groups.product_id as grpProdId', 'groups.unit_id as grpUnitId', 'groups.name as groupName', 'groups.target_quantity', 'groups.achieved_quantity', 'groups.reached_quantity', 'groups.end_date', 'group_images.image as groupImg', 'units.name as unit', 'products.name as productName',DB::raw('(MAX(group_supplier_discount_options.discount)) AS max_discount'),'groups.group_status','group_status.name as groupNameStatus']);
            } else {
                //dd($request->paginate * $group_count);
                $groups = Groups::with('groupMembersMultiple')
                ->leftjoin('group_images','groups.id','=','group_images.group_id')
                ->leftjoin('units','groups.unit_id','=','units.id')
                ->leftjoin('products','groups.product_id','=','products.id')
                ->leftjoin('group_supplier_discount_options','groups.id','=','group_supplier_discount_options.group_id')
                ->join('group_status','groups.group_status','=','group_status.id')
                ->skip($request->paginate * $group_count)->take($group_count)
                ->whereDate('groups.end_date', '>=', Carbon::now())
                ->whereNotIn('groups.group_status', [3, 4])
                ->groupBy('groups.id')
                ->orderBy('groups.id', 'desc')
                ->orderBy('group_images.id', 'desc')
                ->get(['groups.id','groups.category_id as grpCatId','groups.subCategory_id as grpSubCatId','groups.product_id as grpProdId', 'groups.unit_id as grpUnitId', 'groups.name as groupName', 'groups.target_quantity', 'groups.achieved_quantity', 'groups.reached_quantity', 'groups.end_date', 'group_images.image as groupImg', 'units.name as unit', 'products.name as productName',DB::raw('(MAX(group_supplier_discount_options.discount)) AS max_discount'),'groups.group_status','group_status.name as groupNameStatus']);
            }
        }
        //dd($groups->toArray());
        $returnHTML = view('group_trading/groups_data_ajax', ['groups' => $groups, 'query' => $query])->render();
        return response()->json(array('success' => true, 'groups' => $groups, 'query' => $query, 'returnHTML' => $returnHTML, 'group_count' => $group_count));
    }

    //Get group details by group id (Ronak M)
    public function groupDetails($id) {
        try {
            $groupId = Crypt::decrypt($id);

            $groupActivies = GroupActivity::
            where('group_id', $groupId)->
            orderBy('id', 'DESC')->
            get('group_activities.*','group_activities.created_at')->
            groupBy(function ($val) {
                return Carbon::parse($val->created_at)->format('Y-m-d');
            });

            $category= Category::pluck('name', 'id')->toArray();
            $subcategory = SubCategory::pluck('name', 'id')->toArray();
            $product = Product::pluck('name', 'id')->toArray();
            $unit = Unit::pluck('name', 'id')->toArray();
            $status = GroupStatus::pluck('name', 'id')->toArray();
            $suppliers = Supplier::pluck('name', 'id')->toArray();

            $group = Groups::leftJoin('group_suppliers', 'groups.id', '=', 'group_suppliers.group_id')
                ->leftJoin('suppliers', 'group_suppliers.supplier_id', '=', 'suppliers.id')
                ->leftJoin('categories', 'groups.category_id', '=', 'categories.id')
                ->leftJoin('sub_categories', 'groups.subCategory_id', '=', 'sub_categories.id')
                ->leftJoin('products', 'groups.product_id', '=', 'products.id')
                ->leftjoin('units', 'groups.unit_id', '=', 'units.id')
                ->leftJoin('group_status', 'groups.group_status', '=', 'group_status.id')
                ->leftJoin('group_members', 'groups.id', '=', 'group_members.group_id')
                ->where('groups.id',$groupId)
                ->groupBy('groups.id')
                ->first(['groups.id','groups.category_id as grpCatId','groups.subCategory_id as grpSubCatId','groups.product_id as grpProdId', 'groups.unit_id as grpUnitId', 'groups.name as groupName', 'groups.target_quantity', 'groups.reached_quantity','groups.achieved_quantity', 'groups.end_date', 'groups.location_code', 'units.name as unit', 'groups.price as original_price', 'products.name as productName','groups.group_status','categories.name as prodCat','sub_categories.name as prodSubCat','group_status.name as groupStatusName','groups.description as group_description','groups.product_description','suppliers.name as supplierName']);
            $groupImages = GroupImages::where('group_id',$groupId)->get(['image']);

            $groupOrderRange = GroupSupplierDiscountOption::where('group_id',$groupId)->get(['group_supplier_id','group_id','min_quantity','max_quantity','unit_id','discount','discount_price']);

            $userData = Groups::with('groupMembersMultiple')
                ->leftjoin('group_members','groups.id','=','group_members.group_id')
                ->leftjoin('rfqs','group_members.rfq_id','=','rfqs.id')
                ->leftjoin('rfq_products','rfqs.id','=','rfq_products.rfq_id')
                ->leftjoin('units','rfq_products.unit_id','=','units.id')
                ->leftjoin('orders','rfqs.id','=','orders.rfq_id')
                ->join('user_companies','group_members.user_id','=','user_companies.user_id')
                ->join('companies','user_companies.company_id','=','companies.id')
                ->where('groups.id',$groupId)
                ->where('group_members.is_deleted',0)
                ->groupBy('group_members.id')
                ->orderBy('group_members.id','DESC')
                ->get(['group_members.user_id','group_members.rfq_id','group_members.group_id','rfqs.address_name','rfqs.address_line_1','rfqs.address_line_2','rfqs.city','rfqs.sub_district','rfqs.district','rfqs.state','rfqs.pincode','rfq_products.quantity','units.name as unitName','companies.name as companyName','companies.logo as companyLogo', 'companies.address as comp_address','user_companies.company_id','orders.id as orderId']);

            $groupMembers = [];
            foreach($userData as $user) {
                $groupMembers[$user->user_id][$user->rfq_id] = $user;
            }
            if(!empty(Auth::user()->id)) {
                if(isset($groupMembers[Auth::user()->id])) {
                    $authMember = $groupMembers[Auth::user()->id];
                    unset($groupMembers[Auth::user()->id]);
                    array_unshift($groupMembers,$authMember);
                }
            }
            // return view('group_trading/group_activity');
            return view('group_trading/group_details',['groupActivies'=> $groupActivies, 'group' => $group, 'groupImages' => $groupImages, 'groupOrderRange' => $groupOrderRange, 'userData' => $groupMembers, 'companyCount' => count($groupMembers), 'category' => $category, 'subcategory' => $subcategory, 'product' => $product, 'unit' => $unit, 'status' => $status]);

        }
        catch(DecryptException $e) {
            abort(404);
            // dd($e->getMessage());//  Block of code to handle errors
        }
    }

    //Get groups by checkbox filters and main filters
    public function groupsCategoryFilters(Request $request) {
        $group_count = Settings::where('key', 'load_more_groups')->pluck('value')->first();
        $keyword = $request->get('query');
        // dd($query);
        // DB::enableQueryLog();
        if ($request->ajax()) {
            $categories = $sub_categories = $products = $minDiscount = $maxDiscount = $remainingDays = $query = '';
            if ($request->categoriesArr != null) {
                $categories = $request->categoriesArr;
            }
            if ($request->subCategoriesArr != null) {
                $sub_categories = $request->subCategoriesArr;
            }
            if ($request->productsArr != null) {
                $products = $request->productsArr;
            }
            // if ($request->discountArr != null) {
            //     $minDiscount = min(explode(",",implode(",",preg_replace("/[^a-zA-Z 0-9]+/", ",", $request->discountArr))));
            //     $maxDiscount = max(explode(",",implode(",",preg_replace("/[^a-zA-Z 0-9]+/", ",", $request->discountArr))));
            // }
            if ($request->discountArr != null) {
                $maxDiscount = max(explode(",",implode(",",preg_replace("/[^a-zA-Z 0-9]+/", ",", array_unique($request->discountArr)))));
            }
            if ($request->daysRemainingArr != null) {
                $remainingDays = max(explode(",",implode(",",preg_replace("/[^a-zA-Z 0-9]+/", ",", array_unique($request->daysRemainingArr)))));
            }
            //dd($maxDiscount);
            // dump($request->paginate, $group_count);
            $groups = Groups::with('groupMembersMultiple')

            ->leftjoin('group_images','groups.id','=','group_images.group_id')
            ->leftjoin('categories','groups.category_id','=','categories.id')
            ->leftjoin('sub_categories','groups.subCategory_id','=','sub_categories.id')
            ->leftjoin('products','groups.product_id','=','products.id')
            ->leftjoin('units','groups.unit_id','=','units.id')
            ->leftjoin('group_tags','groups.id','=','group_tags.group_id')
            ->leftjoin('group_supplier_discount_options','groups.id','=','group_supplier_discount_options.group_id')
            /*
            ->join('group_images','groups.id','=','group_images.group_id')
            ->join('categories','groups.category_id','=','categories.id')
            ->join('sub_categories','groups.subCategory_id','=','sub_categories.id')
            ->join('products','groups.product_id','=','products.id')
            ->join('units','groups.unit_id','=','units.id')
            ->join('group_tags','groups.id','=','group_tags.group_id')
            ->join('group_supplier_discount_options','groups.id', '=', 'group_supplier_discount_options.group_id')
            */
            //->join('group_members','groups.id', '=', 'group_members.group_id')

            ->skip($request->paginate * $group_count)->take($group_count)

            ->where(function($query) use ($categories,$sub_categories,$products,$minDiscount,$maxDiscount,$remainingDays) {
                $query = $query->whereDate('groups.end_date', '>=', Carbon::now())->whereNotIn('groups.group_status', [3, 4]);
                if($categories != '') {
                    $query = $query->whereIn('categories.id', $categories);
                }
                if($sub_categories != '') {
                    $query = $query->whereIn('sub_categories.id', $sub_categories);
                }
                if($products != '') {
                    $query = $query->whereIn('products.id',$products);
                }
                if($maxDiscount != '') {
                    $query = $query->where('group_supplier_discount_options.discount','>=',$maxDiscount);
                }
                // If we use range for discount then we can use below code
                // $query->orWhere(function($query) use ($minDiscount,$maxDiscount) {
                //     $query->where('group_supplier_discount_options.discount','>=',$minDiscount)
                //     ->where('group_supplier_discount_options.discount','<=',$maxDiscount);
                // })
                if($remainingDays != '') {
                    $query = $query->where('groups.end_date','<=',date('Y-m-d',strtotime('+'.$remainingDays.' days')));
                }
            });

            if($keyword){
                $groups = $groups
                ->where('groups.name', 'like', '%'.$keyword.'%')
                ->orWhere('categories.name', 'like', '%'.$keyword.'%')
                ->orWhere('sub_categories.name', 'like', '%'.$keyword.'%')
                ->orWhere('products.name', 'like', '%'.$keyword.'%')
                ->orWhere('groups.name', 'like', '%'.$keyword.'%')
                ->orWhere('group_tags.tag', 'like', '%'.$keyword.'%');
            }


            $groups = $groups->groupBy('groups.id')
            ->orderBy('groups.id', 'desc')
            ->orderBy('group_images.id', 'desc')
            ->get(['groups.id','groups.category_id as grpCatId','groups.subCategory_id as grpSubCatId','groups.product_id as grpProdId', 'groups.unit_id as grpUnitId', 'groups.name as groupName', 'groups.target_quantity', 'groups.reached_quantity', 'groups.achieved_quantity', 'groups.end_date', 'group_images.image as groupImg', 'units.name as unit', 'products.name as productName',DB::raw('(MAX(group_supplier_discount_options.discount)) AS max_discount'), 'groups.group_status','groups.product_description']);
            // dump($groups->toArray());
            $returnHTML = view('group_trading/groups_data_ajax', ['groups' => $groups, 'query' => $query])->render();
            return response()->json(array('success' => true, 'groups' => $groups, 'returnHTML' => $returnHTML));
        }
    }

    //Leave group trading by rfq id and user id
    public function leaveGroupTrading(Request $request) {

        //Soft delete user rfq data from "group_members" table
        $groupMembersData = GroupMember::where([ ['group_id',$request->groupId],['rfq_id',$request->rfqId],['user_id',$request->userId],['is_deleted',0] ])->first();
        $groupMembersData->group_leave_reason = $request->leave_group_reason;
        $groupMembersData->removed_by = Auth::user()->id;
        $groupMembersData->group_leave_date = Carbon::now()->toDateTimeString();
        $groupMembersData->is_deleted = 1;
        $groupMembersData->save();

        if($groupMembersData->is_deleted = 1) {
            //Get rfq quantity from "rfq_products" table and update it in "groups" table
            $rfq_productData = RfqProduct::where('rfq_id',$request->rfqId)->first(['quantity']);

            $groupsData = Groups::where('id',$request->groupId)->first();
            $groupsData->reached_quantity = $groupsData->reached_quantity - $rfq_productData->quantity;
            $groupsData->save();

            //Remove group id from "rfqs" table by rfq_id
            $rfqData = Rfq::find($request->rfqId);
            $rfqData->group_id = null;
            $rfqData->save();

            $groupActivity = new GroupActivity();
            $groupActivity->user_id = Auth::user()->id;
            $groupActivity->group_id = $request->groupId;
            $groupActivity->key_name = 'left_group';
            $groupActivity->old_value = '';
            $groupActivity->new_value = 'Group Left - BRFQ '. $request->rfqId;
            $groupActivity->user_type = User::class;
            $groupActivity->save();

            //when any buyer left group join other supplier on existing chat
            $addSupplierList = GroupChatMember::saveAllSupplierOnGroupLeft($request->rfqId);
            //end

            //mail send supplier and admin and buyer
            $group_member_user_id = $request->userId;
            $group_id = $request->groupId;
            $url = route('group-details', ['id' => Crypt::encrypt($group_id)]);
            $groupData = Groups::leftjoin('group_suppliers', 'groups.id', '=', 'group_suppliers.group_id')
                ->leftjoin('suppliers', 'group_suppliers.supplier_id', '=', 'suppliers.id')
                ->leftjoin('products', 'groups.product_id', '=', 'products.id')
                ->leftjoin('group_members', 'groups.id', '=', 'group_members.group_id')
                ->leftjoin('users', 'group_members.user_id', '=', 'users.id')
                ->leftjoin('companies', 'group_members.company_id', '=', 'companies.id')
                ->where('groups.id',$group_id)
                ->where('group_members.user_id',$group_member_user_id)
                ->get(['groups.id','groups.group_number','groups.name', 'suppliers.name as supplier_name', 'suppliers.email as supplier_email', 'products.name as product_name','group_members.rfq_id','group_members.group_leave_reason','users.firstname as user_firstname','users.lastname as user_lastname','users.email as user_email','companies.name as user_companies','users.id as user_id']);
            $shareLinks = getGroupsLinks($group_id);
            $groupsMailData = [
                'group' => $groupData[0],
                'url' => $url,
                'shareLinks' =>$shareLinks,
            ];
            dispatch(new GroupLeaveJob($groupsMailData,$groupData[0]->supplier_email)); //Admin and Supplier mail send
            dispatch(new GroupLeaveBuyerJob($groupsMailData,$groupData[0]->user_email)); //Buyer mail send
            //end mail

            return response()->json(array('success' => true, 'msg' => __('dashboard.leave_group_successfully')));
        } else {
            return response()->json(array('success' => false, 'msg' => __('profile.something_went_wrong')));
        }
    }

    public function fetchGroupbyProduct(Request $request)
    {
        $groupDetails = Groups::leftJoin('group_images','groups.id','=','group_images.group_id') //left join for if image not find return null with group record
        ->leftjoin('products','groups.product_id','=','products.id')
        ->leftJoin('group_supplier_discount_options', 'groups.id', '=', 'group_supplier_discount_options.group_id')
        ->join('units','groups.unit_id','=','units.id')
        ->groupBy('groups.id')
        ->orderBy('groups.id', 'desc')
        ->where('groups.product_id', $request->product_id)->where('groups.category_id', $request->category_id)->where('groups.subCategory_id', $request->sub_category_id)
        ->with('groupMembersMultiple')
        ->whereDate('groups.end_date', '>=', Carbon::now())
        ->whereNotIn('groups.group_status', [3, 4])
        ->get(['groups.*', 'group_images.image as groupImg', 'units.name as unit', 'products.name as productName', DB::raw('(MAX(group_supplier_discount_options.discount)) AS max_discount')])->toArray();
        // dd(Carbon::now()->toDateString());
        // dd($groupDetails);

        $updatedGroupDetails = [];
        foreach ($groupDetails as $row) {
            // $diffDays = Carbon::now()->diffInDays($row['end_date'], false); // count remaining days
            $diffDays = (strtotime($row['end_date']) - strtotime(now()->format('Y-m-d'))) / (60 * 60 * 24);
            $formated_end_date = Carbon::parse($row['end_date'])->format('d M Y'); // change format of end date

            /* start get no of company */
            $company_ids = [];
            foreach ($row['group_members_multiple'] as $value) {
                array_push($company_ids, $value['company_id']);
            }
            $company_ids = array_unique($company_ids);
            /* end get no of company */
            $copyUrl = $row;
            $shareGroupPopupHtml = shareGroupLink(route('group-details',['id' => Crypt::encrypt($row['id'])])); // get share group socialite object
            $shareGroupPopupHtml = html_entity_decode($shareGroupPopupHtml); // convert object to html string

            $shareGroupLink = route('group-details',['id' => Crypt::encrypt($row['id'])]);
            $groupDetailsLink = route('group-details',['id' => Crypt::encrypt($row['id'])]);

            $row = array_merge($row, [
                'remaining_days' => $diffDays,
                'formated_end_date' => $formated_end_date,
                'no_of_company' => count($company_ids),
                'share_group_popup_html' => $shareGroupPopupHtml,
                'share_group_link' => $shareGroupLink,
                'group_details_link' => $groupDetailsLink
            ]);

            array_push($updatedGroupDetails, $row);
        }

        $groupDetails = $updatedGroupDetails;

        return response()->json($groupDetails);
    }

    /**
     * Munir M
     */
    public function groupPlaceOrder(Request $request){
        $inputs = (object)$request->except('_token');
        $inputs->user_id = auth()->user()->id;
        $inputs->cust_ref_id = $inputs->cust_ref_id??'';
        $inputs->comment = $inputs->comment??'';
        $quote = Quote::find($inputs->quoteId);
        if (empty($quote->group_id)){
            $dashController = new DashboardController();
            return $dashController->setOrder($inputs);
        }
        if ($quote->status_id==2){
            return response()->json(array('success' => false,'message'=>__('admin.something_went_wrong')));
        }
        $groupFinalAmount = Quote::calculateGroupFinalAmount($inputs->quoteId);
        $quoteItem = $quote->quoteItems()->first(['product_quantity','product_price_per_unit']);
        $group = $quote->group()->first();
        if ($group->max_order_quantity<($group->achieved_quantity+$quoteItem->product_quantity)){
            return response()->json(array('success' => false,'message'=>__('admin.not_more_then_max_group_order_quantity')));
        }
        if ($group->group_status!=Groups::OPEN){
            return response()->json(array('success' => false,'message'=>__('admin.group_is_not_open')));
        }
        $orderQty = $quoteItem->product_quantity;
        $productRealPrice = $quoteItem->product_price_per_unit;
        $groupDiscountAmount = Quote::getGroupDiscountAmount($group,$orderQty,$productRealPrice);
        $inputs->quote_data = array_merge($groupFinalAmount,(array)$groupDiscountAmount);

        //$inputs = (object)array_merge((array)$inputs,$groupFinalAmount,(array)$groupDiscountAmount);

        GroupPlaceOrderLog::createOrUpdateGroupPlaceOrderLog(['group_id' => $quote->rfq->group_id,'quote_id' => $quote->id,'request_data'=>json_encode($inputs)]);
        $groupTransaction = $quote->groupTransaction()->where('status', 'PENDING')->first(['id','user_id','invoice_id','invoice_url','amount']);
        $invoiceUrl = '';
        if (empty($groupTransaction)) {
            $result = $this->createGroupInvoice($group, $quote, $groupFinalAmount);
            if (empty($result)) {
                return response()->json(array('success' => false));
            }
            $invoiceUrl = $result->invoice_url;
        }else{
            if ($groupTransaction->amount==$groupFinalAmount['final_amount']) {
                $invoiceUrl = $groupTransaction->invoice_url;
            }else{
                dispatch(new SetExpireOnInvoice($groupTransaction));
                $result = $this->createGroupInvoice($group, $quote, $groupFinalAmount);
                if (empty($result)) {
                    return response()->json(array('success' => false));
                }
                $invoiceUrl = $result->invoice_url;
            }
        }
        return response()->json(array('success' => true, 'invoice_url' => $invoiceUrl));
    }

    public function createGroupInvoice(Groups $group,Quote $quote,array $groupFinalAmount){
        $xendit = new XenditController();
        return $xendit->createGroupInvoice($group, $quote, $groupFinalAmount);
    }

    /**
     * Munir M
     */
    function successInvoicePayment($quoteId)
    {
        $transaction = getRecordsByCondition('group_transactions',['quote_id'=>$quoteId],'id,order_id,status,customer,paid_amount,paid_at,payment_channel',0,'id DESC');

        if (empty($transaction)){
            return redirect('dashboard');
        }
        return view('dashboard/payment/payment_success', ['transaction'=>$transaction]);
    }

    /**
     * Munir M
     */
    function failInvoicePayment($quoteId)
    {
        $transaction = getRecordsByCondition('group_transactions',['quote_id'=>$quoteId]);
        if (empty($transaction)){
            return redirect('dashboard');
        }
        return view('dashboard/payment/link_expired', ['transaction'=>$transaction]);
    }

 }
