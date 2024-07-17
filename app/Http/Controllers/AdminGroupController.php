<?php

namespace App\Http\Controllers;

use App\Jobs\GroupCloseBuyerJob;
use App\Jobs\GroupCloseJob;
use App\Jobs\GroupCreateBuyerJob;
use App\Jobs\GroupCreateJob;
use App\Jobs\GroupExpireBuyerJob;
use App\Jobs\GroupExpireJob;
use App\Jobs\GroupRangeChangedBuyerJob;
use App\Jobs\GroupRangeChangedJob;
use App\Jobs\RemovedBuyerFromGroupJob;
use App\Jobs\RemovedFromGroupJob;
use App\Models\Category;
use App\Models\CompanyConsumption;
use App\Models\GroupActivity;
use App\Models\GroupImages;
use App\Models\GroupMember;
use App\Models\Groups;
use App\Models\GroupStatus;
use App\Models\GroupSupplier;
use App\Models\GroupSupplierDiscountOption;
use App\Models\GroupTag;
use App\Models\MongoDB\GroupChatMember;
use App\Models\Order;
use App\Models\OtherCharge;
use App\Models\Product;
use App\Models\Quote;
use App\Models\QuoteActivity;
use App\Models\QuoteChargeWithAmount;
use App\Models\Rfq;
use App\Models\RfqProduct;
use App\Models\SubCategory;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\SuppliersBank;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserActivity;
use App\Models\UserSupplier;
use App\Models\SupplierProductDiscountRange;
use App\Models\SystemActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Mail;

class AdminGroupController extends Controller
{
    function list(){

        if (auth()->user()->role_id == 3){
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $lists = Groups::leftJoin('group_supplier_discount_options', 'groups.id', '=', 'group_supplier_discount_options.group_id')
                ->leftjoin('group_suppliers', 'groups.id', '=', 'group_suppliers.group_id')
                ->leftjoin('suppliers', 'group_suppliers.supplier_id', '=', 'suppliers.id')
                ->leftjoin('categories', 'groups.category_id', '=', 'categories.id')
                ->leftjoin('sub_categories', 'groups.subCategory_id', '=', 'sub_categories.id')
                ->leftjoin('products', 'groups.product_id', '=', 'products.id')
                ->leftjoin('group_status', 'groups.group_status', '=', 'group_status.id')
                ->where('suppliers.id', $supplier_id)
                ->groupBy('groups.id')
                ->orderBy('id', 'desc')
                ->with('groupMembersMultiple')
                ->get(['groups.id', 'groups.name', 'groups.group_number', 'groups.social_token', 'groups.description', 'groups.end_date','groups.achieved_quantity', 'groups.reached_quantity', 'groups.status', 'suppliers.name as supplier_name', 'categories.name as category_name', 'sub_categories.name as sub_category_name', 'products.name as product_name','groups.target_quantity',DB::raw('(MAX(group_supplier_discount_options.discount)) AS max_discount'),DB::raw('(MIN(group_supplier_discount_options.discount)) AS min_discount'),'groups.group_status', 'group_status.name as group_status_name']);
        } else {
            $lists = Groups::leftJoin('group_supplier_discount_options', 'groups.id', '=', 'group_supplier_discount_options.group_id')
                ->leftjoin('group_suppliers', 'groups.id', '=', 'group_suppliers.group_id')
                ->leftjoin('suppliers', 'group_suppliers.supplier_id', '=', 'suppliers.id')
                ->leftjoin('categories', 'groups.category_id', '=', 'categories.id')
                ->leftjoin('sub_categories', 'groups.subCategory_id', '=', 'sub_categories.id')
                ->leftjoin('products', 'groups.product_id', '=', 'products.id')
                ->leftjoin('group_status', 'groups.group_status', '=', 'group_status.id')
                ->groupBy('groups.id')
                ->orderBy('id', 'desc')
                ->with('groupMembersMultiple')
                ->get(['groups.id', 'groups.name', 'groups.group_number', 'groups.social_token', 'groups.description', 'groups.end_date','groups.achieved_quantity', 'groups.reached_quantity', 'groups.status', 'suppliers.name as supplier_name', 'categories.name as category_name', 'sub_categories.name as sub_category_name', 'products.name as product_name','groups.target_quantity',DB::raw('(MAX(group_supplier_discount_options.discount)) AS max_discount'),DB::raw('(MIN(group_supplier_discount_options.discount)) AS min_discount'),'groups.group_status', 'group_status.name as group_status_name']);
        }

        /**begin: system log**/
        Groups::bootSystemView(new Groups());
        /**end:  system log**/
        return view('admin/groups/list', ['lists' => $lists]);
    }

    public function getGroupDetails($id){
        //Supplier And Group Details
        $groups = Groups::leftJoin('group_suppliers', 'groups.id', '=', 'group_suppliers.group_id')
            ->leftJoin('suppliers', 'group_suppliers.supplier_id', '=', 'suppliers.id')
            ->leftJoin('categories', 'groups.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'groups.subCategory_id', '=', 'sub_categories.id')
            ->leftJoin('products', 'groups.product_id', '=', 'products.id')
            ->leftjoin('units', 'groups.unit_id', '=', 'units.id')
            ->leftJoin('group_status', 'groups.group_status', '=', 'group_status.id')
            ->leftJoin('group_members', 'groups.id', '=', 'group_members.group_id')
            ->where('groups.id',$id)
            ->groupBy('groups.id')
            ->with('groupMembersMultiple')
            ->first(['groups.id', 'groups.name', 'groups.group_number', 'groups.social_token', 'groups.description', 'groups.end_date', 'groups.reached_quantity','groups.price', 'groups.status', 'suppliers.name as supplier_name', 'categories.name as category_name', 'sub_categories.name as sub_category_name', 'products.name as product_name','groups.target_quantity','groups.group_status', 'group_status.name as group_status_name', 'suppliers.contact_person_name', 'suppliers.contact_person_last_name', 'units.name as unit_name']);
        //Discount Details
        $discountRange = GroupSupplierDiscountOption::where('group_id', $id)
            ->where('deleted_at', null)
            ->get();
            // 'suppliers.contact_person_name as supplier_name', 'suppliers.name as supplier_company_name',
        //dd($groups->toArray());

        //Group Image
        $groupImages = DB::table('group_images')
            ->where('group_id', $id)
            ->where('deleted_at', null)
            ->get(['id', 'group_id', 'image']);

        $groupview = view('admin/groups/groupViewModal',[
            'data' => $groups,
            'discount'=>$discountRange,
            'groupImages' => $groupImages])->render();
        return response()->json(array('success' => true, 'groupview'=>$groupview));

    }

    public function groupAdd(){
        // dd('hello');
        $supplier_id = 0;
        if (auth()->user()->role_id == 3) {
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
            $suppliers = Supplier::where('is_deleted', 0)->where('id', $supplier_id)->where('status', 1)->get(['id', 'name']);
        } else {
            $suppliers = Supplier::where('is_deleted', 0)->where('status', 1)->get(['id', 'name']);
        }
        $categories = Category::where('status',1)->where('is_deleted',0)->get(['id', 'name']);
        $units = Unit::where('status',1)->where('is_deleted',0)->orderBy('id', 'DESC')->get(['id', 'name']);

        /**begin: system log**/
        Groups::bootSystemView(new Rfq(), 'Groups', SystemActivity::ADDVIEW);
        /**end: system log**/
        return view('admin/groups/groupAdd', ['suppliers' => $suppliers, 'categories' => $categories, 'units' => $units, 'supplier_id' => $supplier_id]);
    }

    public function create(Request $request){
        $date = str_replace('/', '-', $request->exp_date);
        $exp_date =  date('Y-m-d', strtotime($date));
        if (auth()->user()->role_id == 3) {
            $supplier = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
        }else{
            $supplier = $request->supplier;
        }
        //set last max qts as Target Qty
        $target_quantity = 0;
        $key = max(array_values($request->max_qty));
        if(!empty($key)) {
            $target_quantity = $key;
            //Groups::where(['id' => $request->id])->update(['target_quantity' => $key]);
        }

        $hash = Str::random(64);
        $group = new Groups;
        $group->name = $request->name;
        $group->location_code = $request->location_code;
        $group->category_id = $request->category;
        $group->subCategory_id = $request->sub_category;
        $group->product_id = $request->product_name;
        $group->product_description = strip_tags($request->product_description);
        $group->unit_id = $request->unit_name;
        $group->price = $request->price;
        $group->min_order_quantity = $request->min_order_quantity;
        $group->max_order_quantity = $request->max_order_quantity;
        $group->group_margin = $request->group_margin;
        $group->end_date = $exp_date;
        $group->description = $request->description;
        $group->status = 1;
        $group->target_quantity = $target_quantity;
        $group->reached_quantity = 0;
        $group->social_token = $hash;
        $group->added_by = Auth::id();
        $group->save();

        /**begin: system log**/
        $group->bootSystemActivities();
        /**end: system log**/

        $groupId = $group->id;
        $group->group_number = 'BGRP-'.$groupId;
        $group->save();

        // added Group Supplier table
            GroupSupplier::insert(['supplier_id' => $supplier, 'group_id' => $groupId]);

        // added Group tag table
        $tags  = $request->add_tags;
        $tagsArray = explode(",", $tags);
        if (isset($request->add_tags)){
            foreach ($tagsArray as $tag) {
                GroupTag::insert(['group_id' => $groupId, 'tag' => $tag]);
            }
        }

        // added Group ranges
        $discount_options = [];
        if (!empty($request->min_qty)){
            foreach ($request->min_qty as $key => $value){
                $discount_options[$key] = array(
                    'group_supplier_id' => $supplier,
                    'group_id' => $groupId,
                    'min_quantity' => $value,
                    'max_quantity' => $request->max_qty[$key],
                    'unit_id' => $request->unit_name,
                    'discount' => $request->discount[$key],
                    'discount_price' => $request->discount_price[$key],
                );
            }
        }
        $group_discount_option = GroupSupplierDiscountOption::insert($discount_options);
        //group activity
        if (Auth::user()->id) {
            $groupActivity = new GroupActivity();
            $groupActivity->user_id = Auth::user()->id;
            $groupActivity->group_id = $groupId;
            $groupActivity->key_name = 'create';
            $groupActivity->old_value = '';
            $groupActivity->new_value = 'Group Created - ' . $group->group_number;
            $groupActivity->user_type = User::class;
            $groupActivity->save();
        }
        //check any user belongs to this categories
        $CompanyConsumption = CompanyConsumption::where('is_deleted',0)->where('product_cat_id',$request->category)->count();
        //mail send supplier and admin
        $groupData = Groups::leftjoin('group_suppliers', 'groups.id', '=', 'group_suppliers.group_id')
            ->leftjoin('suppliers', 'group_suppliers.supplier_id', '=', 'suppliers.id')
            ->leftjoin('categories', 'groups.category_id', '=', 'categories.id')
            ->leftjoin('sub_categories', 'groups.subCategory_id', '=', 'sub_categories.id')
            ->leftjoin('products', 'groups.product_id', '=', 'products.id')
            ->leftjoin('units', 'groups.unit_id', '=', 'units.id')
            ->where('groups.id',$group->id)
            ->get(['groups.id', 'groups.name', 'groups.group_number', 'groups.description','groups.price', 'groups.end_date','groups.category_id', 'suppliers.name as supplier_name', 'suppliers.email as supplier_email', 'categories.name as category_name', 'sub_categories.name as sub_category_name', 'products.name as product_name','units.name as unit_name']);
        $groupRanges = GroupSupplierDiscountOption::where('group_id', $group->id)->where('deleted_at', null)->get()->toArray();
        $shareLinks = getGroupsLinks($group->id);
        $groupsMailData = [
            'group' => $groupData[0],
            'groupRanges' => $groupRanges,
            'shareLinks' =>$shareLinks,
        ];

        dispatch(new GroupCreateJob($groupsMailData,$groupData[0]->supplier_email));
        if($CompanyConsumption > 0){
            dispatch(new GroupCreateBuyerJob($groupsMailData));
        }

        return response()->json(array('success' => true,'groupId' => $groupId));
        //return redirect('/admin/groups');
    }

    public function joinGroup($hash){
        dd('welcomee');
    }

    public function delete(Request $request){
        $group = Groups::find($request->id);
        $groupMember = GroupMember::where('group_id', $request->id)->where('is_deleted', 0)->count();
        if($groupMember>0){
            return response()->json(array('success' => false));
        }else{
            GroupActivity::insert(array('key_name' => 'group_deleted', 'user_id' => Auth::id(), 'group_id' => $request->id, 'old_value' => 'BGRP-'.$request->id , 'new_value' => '', 'user_type' => User::class, 'created_at' => Carbon::now()));
            $group->deleted_by =  Auth::id();
            $group->save();
            $group->delete();

            /**begin: system log**/
            $group->bootSystemActivities();
            /**end: system log**/

            return response()->json(array('success' => true));
            //return $request->id;
        }
    }

    public function deleteGroupMember(Request $request){
        if (!empty($request->id) && auth()->user()->role_id == 1){
            $group_leave_reason = 'Admin Removed';
        }else{
            $group_leave_reason = 'Supplier Removed';
        }
        $groupM = GroupMember::join('users', 'group_members.user_id', '=', 'users.id')
            ->where('group_members.id', $request->id)
            ->first(['users.firstname','users.lastname','group_members.rfq_id','group_members.group_id','group_members.user_id']);
        //Get rfq quantity from "rfq_products" table and update it in "groups" table
        $rfqProductQty= RfqProduct::where('rfq_id',$groupM['rfq_id'])->pluck('quantity')->first();
        $reachedQuantity = Groups::where('id',$groupM['group_id'])->pluck('reached_quantity')->first();
        $updateReachedQuantity = $reachedQuantity - $rfqProductQty;
        //update on group and rfqs tabel
        Groups::where('id', $groupM['group_id'])->update(['reached_quantity'=>$updateReachedQuantity]);
        Rfq::where('id', $groupM['rfq_id'])->update(['group_id'=> null]);
        $buyer = 'BRFQ-'. $groupM['rfq_id']. ' of ' . $groupM['firstname'].' '.$groupM->lastname;
        GroupActivity::insert(array('key_name' => 'buyer_remove', 'user_id' => Auth::id(), 'group_id' => $groupM->group_id, 'old_value' => $buyer , 'new_value' => '', 'user_type' => User::class, 'created_at' => Carbon::now()));
        $groupMember = GroupMember::find($request->id);
        $groupMember->is_deleted = 1;
        $groupMember->group_leave_reason = $group_leave_reason;
        $groupMember->removed_by = Auth::id();
        $groupMember->group_leave_date = Carbon::now();
        $groupMember->save();

        //when any buyer left group join other supplier on existing chat
        $addSupplierList = GroupChatMember::saveAllSupplierOnGroupLeft($groupM['rfq_id']);
        //end

        //mail send supplier and admin and buyer
        $group_member_user_id = $groupM['user_id'];
        $group_id = $groupM['group_id'];
        $groupData = Groups::leftjoin('group_suppliers', 'groups.id', '=', 'group_suppliers.group_id')
            ->leftjoin('suppliers', 'group_suppliers.supplier_id', '=', 'suppliers.id')
            ->leftjoin('products', 'groups.product_id', '=', 'products.id')
            ->leftjoin('group_members', 'groups.id', '=', 'group_members.group_id')
            ->leftjoin('users', 'group_members.user_id', '=', 'users.id')
            ->leftjoin('companies', 'group_members.company_id', '=', 'companies.id')
            ->where('groups.id',$group_id)
            ->where('group_members.user_id',$group_member_user_id)
            ->get(['groups.id','groups.group_number', 'suppliers.name as supplier_name', 'suppliers.email as supplier_email', 'products.name as product_name','group_members.rfq_id','users.firstname as user_firstname','users.lastname as user_lastname','users.email as user_email','companies.name as user_companies','users.id as user_id']);
        $shareLinks = getGroupsLinks($group_id);
        $groupsMailData = [
            'group' => $groupData[0],
            'shareLinks' =>$shareLinks,
        ];
        dispatch(new RemovedFromGroupJob($groupsMailData,$groupData[0]->supplier_email)); //Admin and Supplier mail send
        dispatch(new RemovedBuyerFromGroupJob($groupsMailData,$groupData[0]->user_email)); //Buyer mail send

        return response()->json(array('success' => true));
    }

    public function leaveGroupTrading(Request $request) {
        //Soft delete user rfq data from "group_members" table
        $groupMembersData = GroupMember::where([['group_id',$request->groupId],['rfq_id',$request->rfqId],['user_id',$request->userId]])->first();
        $groupMembersData->is_deleted = 1;
        $groupMembersData->save();
        //Get rfq quantity from "rfq_products" table and update it in "groups" table
        $rfq_productData = RfqProduct::where('rfq_id',$request->rfqId)->first(['quantity']);
        $groupsData = Groups::where('id',$request->groupId)->first();
        $groupsData->reached_quantity = $groupsData->reached_quantity - $rfq_productData->quantity;
        $groupsData->save();
        $groupActivity = new GroupActivity();
        $groupActivity->user_id = Auth::user()->id;
        $groupActivity->group_id = $request->groupId;
        $groupActivity->key_name = 'left_group';
        $groupActivity->old_value = '';
        $groupActivity->new_value = 'Group Left - BRFQ '. $request->rfqId;
        $groupActivity->user_type = User::class;
        $groupActivity->save();
        return response()->json(array('success' => true));
    }

    public function groupEdit($id){
        $id = Crypt::decrypt($id);

        $groups = Groups::where('deleted_at',null)
            ->with('productDetailsMultiple')
            ->with('groupTagsMultiple')
            ->with('groupImagesMultiple')
            ->find($id);

        /**begin: system log**/
        $groups->bootSystemView(new Groups(), 'Groups', SystemActivity::EDITVIEW, $groups->id);
        /**end: system log**/

        $groupSupplier = GroupSupplier::where(['group_id' => $groups->id])->get(['supplier_id']);

        if ($groups) {
            $categories = DB::table('supplier_products')
                ->join('products', 'supplier_products.product_id', '=', 'products.id')
                ->join('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
                ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
                ->where('supplier_products.supplier_id', $groupSupplier[0]->supplier_id)
                ->where('supplier_products.is_deleted', 0)
                ->groupBy('categories.id')
                ->get(['categories.name', 'categories.id']);
            //dd($categories->toArray());

            $buyerRfqs = Groups::join('group_members', 'groups.id', '=', 'group_members.group_id')
                ->join('users', 'group_members.user_id', '=', 'users.id')
                ->join('companies', 'group_members.company_id', '=', 'companies.id')
                ->join('rfqs', 'group_members.rfq_id', '=', 'rfqs.id')
                ->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
                ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
                ->join('units', 'groups.unit_id', '=', 'units.id')
                ->leftJoin('quotes','group_members.rfq_id','=','quotes.rfq_id')
                ->leftJoin('orders','quotes.id','=','orders.quote_id')
                ->where('groups.deleted_at', null)
                ->where('group_members.is_deleted', 0)
                ->where('groups.id', $id)
                ->orderBy('group_members.id','desc')
                ->get(['groups.id as group_id', 'groups.target_quantity as target_quantity', 'groups.reached_quantity as reached_quantity','groups.achieved_quantity as achieved_quantity','group_members.id as group_members_id','group_members.rfq_id as group_members_rfq_id', 'users.firstname as firstname', 'users.lastname as lastname', 'companies.name as company_name', 'companies.logo as company_logo', 'companies.name as company_name', 'rfqs.reference_number as rfq_number','rfqs.address_line_1 as address_line_1', 'rfqs.address_line_2 as address_line_2','rfqs.status_id as rfqs_status_id', 'rfq_products.quantity as quantity', 'rfq_status.name as rfq_status_name','units.name as units_name','quotes.id as quoteId','quotes.quote_number','orders.id as orderId','orders.order_number','group_members.user_id as buyer_user_id']);
            //preDump($buyerRfqs);
            $buyerRfqsCount = count($buyerRfqs);

            //get order_id who have refund discount
            $groupMembersDiscounts = $groups->groupMembersDiscounts()->where('refund_discount','!=',0)->get(['order_id'])->pluck('order_id')->toArray();

            $achievedQty = Groups::where('id',$id)->pluck('achieved_quantity')->first();
            $prospectDiscount =  GroupSupplierDiscountOption::where('group_id',$id)->max('discount');

            $discountData = RfqProduct::join('rfqs','rfq_products.rfq_id','=','rfqs.id')
                ->join('group_supplier_discount_options','rfqs.group_id','=','group_supplier_discount_options.group_id')
                ->join('groups','group_supplier_discount_options.group_id','=','groups.id')
                ->where('rfqs.group_id',$id)
                ->where('group_supplier_discount_options.min_quantity','<=',$achievedQty)
                ->where('group_supplier_discount_options.max_quantity','>=',$achievedQty)
                ->first(['group_supplier_discount_options.discount_price','group_supplier_discount_options.discount']);

            //Calculate Prospect Revenue
            $groupBasePrice = Groups::where('id',$id)->pluck('price')->first();
            $groupMembersCount = Groups::join('group_members','groups.id','=','group_members.group_id')->where('groups.id',$id)->get('group_members.*')->count();
            $prospectDiscountPriceRev = ($prospectDiscount / 100) * ($groupBasePrice * $groupMembersCount);
            $groupProspectRevenue = ($groupBasePrice * $groupMembersCount) - $prospectDiscountPriceRev;

            //Calculate Obtained Revenue
            $obtainedDiscount = isset($discountData->discount) ? $discountData->discount : 0;
            $obtainedDiscountPriceRev = ($obtainedDiscount / 100) * ($groupBasePrice * $groupMembersCount);
            $groupObtainedRevenue = ($groupBasePrice * $groupMembersCount) - $obtainedDiscountPriceRev;

            $suppliers = Supplier::where('is_deleted', 0)->where('status', 1)->get(['id', 'name']);
            $groupStatus = GroupStatus::where('status',1)->where('is_deleted',0)->get(['id', 'name']);
            $units = Unit::where('status',1)->where('is_deleted',0)->orderBy('id', 'DESC')->get(['id', 'name']);
            $subCategories = SubCategory::all()->where('category_id',$groups->category_id);
            $products = Product::all()->where('subcategory_id',$groups->subCategory_id);

            $groupImages = DB::table('group_images')
                ->where('group_id', $id)
                ->where('deleted_at', null)
                ->get(['id', 'group_id', 'image']);
            /**begin: system log**/
            $groups->bootSystemView(new Rfq(), 'Groups', SystemActivity::EDITVIEW, $groups->id);
            /**end: system log**/
            return view('/admin/groups/groupEdit', [
                                                            'groups' => $groups,
                                                            'suppliers' => $suppliers,
                                                            'categories' => $categories,
                                                            'groupStatus' =>$groupStatus ,
                                                            'units' => $units,
                                                            'subCategories' => $subCategories,
                                                            'products' => $products,
                                                            'groupSupplier' => $groupSupplier,
                                                            'productDetailsMultiple' => $groups->productDetailsMultiple->toArray(),
                                                            'groupTagsMultiple' => $groups->groupTagsMultiple->toArray(),
                                                            'groupImagesMultiple'=>$groups->groupImagesMultiple->toArray(),
                                                            'buyerRfqs'=>$buyerRfqs,
                                                            'buyerRfqsCount'=>$buyerRfqsCount,
                                                            'prospectDiscount' => $prospectDiscount,
                                                            'discountData' => $discountData,
                                                            'groupMembersDiscounts'=>$groupMembersDiscounts,
                                                            'groupProspectRevenue' => $groupProspectRevenue,
                                                            'groupObtainedRevenue' => $groupObtainedRevenue,
                                                            'totalBuyerRefundCount'=>getBuyerRefundCount($id)
                                                        ]);
        } else {
            return redirect('/admin/groups/groupList');
        }
    }

    public function update(Request $request){
        $update_group_range_flag = 0;  //check any changes in group range
        $date = str_replace('/', '-', $request->exp_date);
        $exp_date =  date('Y-m-d', strtotime($date));
        $update_activity = [];
        $group = Groups::join('group_suppliers', 'groups.id', '=', 'group_suppliers.group_id')
        ->select('name', 'category_id as category', 'subCategory_id as sub_category', 'product_id as product_name', 'unit_id as unit_name', 'description', 'end_date as exp_date', 'location_code', 'price', 'min_order_quantity', 'max_order_quantity','group_suppliers.supplier_id as supplier','group_margin','group_status','product_description')->find($request->id);

        //if supplier chnage then send group create mail
        if($request->supplier != $group['supplier']){
            //mail send supplier and admin
            $groupData = Groups::leftjoin('group_suppliers', 'groups.id', '=', 'group_suppliers.group_id')
                ->leftjoin('suppliers', 'group_suppliers.supplier_id', '=', 'suppliers.id')
                ->leftjoin('categories', 'groups.category_id', '=', 'categories.id')
                ->leftjoin('sub_categories', 'groups.subCategory_id', '=', 'sub_categories.id')
                ->leftjoin('products', 'groups.product_id', '=', 'products.id')
                ->leftjoin('units', 'groups.unit_id', '=', 'units.id')
                ->where('groups.id',$request->id)
                ->get(['groups.id', 'groups.name', 'groups.group_number', 'groups.description','groups.price', 'groups.end_date','groups.category_id', 'suppliers.name as supplier_name', 'suppliers.email as supplier_email', 'categories.name as category_name', 'sub_categories.name as sub_category_name', 'products.name as product_name','units.name as unit_name']);
            $groupRanges = GroupSupplierDiscountOption::where('group_id', $request->id)->where('deleted_at', null)->get()->toArray();
            $shareLinks = getGroupsLinks($request->id);
            $groupsMailData = [
                'group' => $groupData[0],
                'groupRanges' => $groupRanges,
                'shareLinks' =>$shareLinks,
            ];

            dispatch(new GroupCreateJob($groupsMailData,$groupData[0]->supplier_email));
        }
        //group close and expire check mail send or not
        $old_status = strval($group['group_status']);
        if(getBuyerRefundCount($request->id)){//if buyer refund initiated then status can't be change
            $new_status = $old_status;
        }else {
            $new_status = $request->group_status;
        }
        // value store in variable

        // Activity log
        $i = 0;
        foreach ($request->all() as $key => $value) {
            if ($key == 'exp_date') {
                //$value = date('Y-m-d', strtotime($request->exp_date));
                $value =  $exp_date;//dd(date('Y-m-d', strtotime($group['exp_date'])),$value);
            }
            if (!in_array($key, ['_token', 'id', 's_id', 'custom_add', 'min_qty', 'max_qty', 'discount', 'discount_price','add_tags']) && $group[$key] != $value) {
                $update_activity[$i]['key_name'] = $key;
                $update_activity[$i]['user_id'] = Auth::id();
                $update_activity[$i]['group_id'] = $request->id;
                if(($key == 'exp_date') && (date('Y-m-d', strtotime($group['exp_date'])) != $value)){
                    $update_activity[$i]['old_value'] = date('Y-m-d', strtotime($group['exp_date']));
                    $update_activity[$i]['new_value'] = $value;
                } else {
                    $update_activity[$i]['old_value'] = $group[$key];
                    $update_activity[$i]['new_value'] = $value;
                }
                $update_activity[$i]['user_type'] = User::class;
                $update_activity[$i]['created_at'] = Carbon::now();
                $i++;
            }
        }
        //dd($update_activity);
        //signgle value activity done
        if ($update_activity) {
            GroupActivity::insert($update_activity);
        }
        //$supplier_id = 0;
        if (auth()->user()->role_id == 3) {
            $supplier = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
        }else{
            $supplier = $request->supplier;
        }
        //manage target qty
        $target_quantity = 0;
        $key = max(array_values($request->max_qty));
        if(!empty($key)) {
            $target_quantity = $key;
            //Groups::where(['id' => $request->id])->update(['target_quantity' => $key]);
        }

        //update group table data
        $group = Groups::find($request->id);
        $group->name = $request->name;
        $group->location_code = $request->location_code;
        $group->category_id = $request->category;
        $group->subCategory_id = $request->sub_category;
        $group->product_id = $request->product_name;
        $group->product_description = strip_tags($request->product_description);
        $group->unit_id = $request->unit_name;
        $group->description = $request->description;
        $group->group_status = $new_status;
        $group->end_date = $exp_date;
        $group->price = $request->price;
        $group->min_order_quantity = $request->min_order_quantity;
        $group->max_order_quantity = $request->max_order_quantity;
        $group->group_margin = $request->group_margin;
        $group->target_quantity = $target_quantity;
        $group->added_by = Auth::id();
        $group->save();

        /**begin: system log**/
        $group->bootSystemActivities();
        /**end: system log**/

        $groupId = $request->id;

        $supplier_group = GroupSupplier::where(['group_id' => $request->id])->update(['supplier_id'=>$supplier]);

        //update group range
        $getRfqProduct = GroupSupplierDiscountOption::where('group_id', $request->id)->get();
        $old_keys = $getRfqProduct->pluck('id')->toArray();
        $product_details_id = $request->s_id;

        if(empty($product_details_id)){
            $product_details_id = [];
        }
        //($product_details_id);
        $diff_values = array_diff($old_keys, $product_details_id);
        if (!empty($diff_values)){
            foreach ($diff_values as $delete_key){
                $remove_range = GroupSupplierDiscountOption::where('id', $delete_key)->first();
                $old_value_name = $remove_range->min_quantity .' - '.$remove_range->max_quantity;
                GroupActivity::insert(array('key_name' => 'remove_discount_range', 'user_id' => Auth::id(), 'group_id' => $request->id, 'old_value' => $old_value_name, 'new_value' => '', 'user_type' => User::class, 'created_at' => Carbon::now()));

                GroupSupplierDiscountOption::where('id', $delete_key)->delete();
            }
            $update_group_range_flag = $update_group_range_flag + 1; //use for mail
        }

        $product_details = $request->min_qty;
        foreach ($product_details as $key => $value){
            $request->min_qty[$key];$request->max_qty[$key];$request->discount[$key];$request->discount_price[$key];
            if (isset($request->custom_add[$key]) && $request->custom_add[$key] == 1){
                //dd($request->min_qty[$key]);
                $add_product = new GroupSupplierDiscountOption;
                $add_product->group_supplier_id = $supplier;
                $add_product->group_id = $request->id;
                $add_product->min_quantity = $request->min_qty[$key];
                $add_product->max_quantity = $request->max_qty[$key];
                $add_product->unit_id = $request->unit_name;
                $add_product->discount = $request->discount[$key];
                $add_product->discount_price = $request->discount_price[$key];
                $add_product->save();

                if($request->min_qty[$key]){
                    $new_message_min_qty = $request->min_qty[$key];
                    GroupActivity::insert(array('key_name' => 'added_min_qty', 'user_id' => Auth::id(), 'group_id' => $request->id, 'old_value' => '', 'new_value' => $new_message_min_qty, 'user_type' => User::class, 'created_at' => Carbon::now()));
                }
                if($request->max_qty[$key]){
                    $new_message_max_qty = $request->max_qty[$key];
                    GroupActivity::insert(array('key_name' => 'added_max_qty', 'user_id' => Auth::id(), 'group_id' => $request->id, 'old_value' => '', 'new_value' => $new_message_max_qty, 'user_type' => User::class, 'created_at' => Carbon::now()));
                }
                if($request->discount[$key]){
                    $new_message_discount = $request->discount[$key];
                    GroupActivity::insert(array('key_name' => 'added_discount', 'user_id' => Auth::id(), 'group_id' => $request->id, 'old_value' => '', 'new_value' => $new_message_discount, 'user_type' => User::class, 'created_at' => Carbon::now()));
                }
                if($request->discount_price[$key]){
                    $new_message_discount_price = $request->discount_price[$key];
                    GroupActivity::insert(array('key_name' => 'added_discount_price', 'user_id' => Auth::id(), 'group_id' => $request->id, 'old_value' => '', 'new_value' => $new_message_discount_price, 'user_type' => User::class, 'created_at' => Carbon::now()));
                }
                $update_group_range_flag = $update_group_range_flag + 1; //use for mail

            } else {
                $add_product = GroupSupplierDiscountOption::find($request->s_id[$key]);
                if ($add_product->min_quantity  != $request->min_qty[$key]) {
                    $old_message_min_quantity = $add_product->min_quantity;
                    $new_message_min_quantity = $request->min_qty[$key];
                    GroupActivity::insert(array('key_name' => 'updated_min_qty', 'user_id' => Auth::id(), 'group_id' => $request->id, 'old_value' => $old_message_min_quantity, 'new_value' => $new_message_min_quantity, 'user_type' => User::class, 'created_at' => Carbon::now()));
                    $update_group_range_flag = $update_group_range_flag + 1; //use for mail
                }
                if ($add_product->max_quantity  != $request->max_qty[$key]) {
                    $old_message_max_quantity = $add_product->max_quantity;
                    $new_message_max_quantity = $request->max_qty[$key];
                    GroupActivity::insert(array('key_name' => 'updated_max_qty', 'user_id' => Auth::id(), 'group_id' => $request->id, 'old_value' => $old_message_max_quantity, 'new_value' => $new_message_max_quantity, 'user_type' => User::class, 'created_at' => Carbon::now()));
                    $update_group_range_flag = $update_group_range_flag + 1; //use for mail
                }
                if ($add_product->discount  != $request->discount[$key]) {
                    $old_message_discount = $add_product->discount;
                    $new_message_discount = $request->discount[$key];
                    GroupActivity::insert(array('key_name' => 'updated_discount', 'user_id' => Auth::id(), 'group_id' => $request->id, 'old_value' => $old_message_discount, 'new_value' => $new_message_discount, 'user_type' => User::class, 'created_at' => Carbon::now()));
                    $update_group_range_flag = $update_group_range_flag + 1; //use for mail
                }
                if ($add_product->discount_price  != $request->discount_price[$key]) {
                    $old_message_discount_price = $add_product->discount_price;
                    $new_message_discount_price = $request->discount_price[$key];
                    GroupActivity::insert(array('key_name' => 'updated_discount_price', 'user_id' => Auth::id(), 'group_id' => $request->id, 'old_value' => $old_message_discount_price, 'new_value' => $new_message_discount_price, 'user_type' => User::class, 'created_at' => Carbon::now()));
                    $update_group_range_flag = $update_group_range_flag + 1; //use for mail
                }

                $add_product->id = $request->s_id[$key];
                $add_product->group_supplier_id = $supplier;
                $add_product->group_id = $request->id;
                $add_product->min_quantity = $request->min_qty[$key];
                $add_product->max_quantity = $request->max_qty[$key];
                $add_product->unit_id = $request->unit_name;
                $add_product->discount = $request->discount[$key];
                $add_product->discount_price = $request->discount_price[$key];
                $add_product->save();
                //$update_group_range_flag = $update_group_range_flag + 1; //use for mail
            }
        }

        //update tegs
        $tags  = $request->add_tags;
        $tagsArray = explode(",", $tags);
        $old_tagGet = GroupTag::where('group_id', $request->id)->get();
        $old_tagKey = $old_tagGet->pluck('tag')->toArray();
        $diff_values_tag = array_diff($old_tagKey, $tagsArray);
        if (!empty($diff_values_tag)){
            foreach ($diff_values_tag as $delete_value){
                $new_message = '';
                $old_message = $delete_value;
                GroupActivity::insert(array('key_name' => 'tag_deleted', 'user_id' => Auth::id(), 'group_id' => $request->id, 'old_value' => $old_message, 'new_value' => $new_message, 'user_type' => User::class, 'created_at' => Carbon::now()));
                GroupTag::where('tag', $delete_value)->delete();
            }
        }
        if($tagsArray[0] != ''){
            foreach($tagsArray as $key => $value){
                $old_tagdata = GroupTag::where('group_id', $request->id)->where('tag',$value)->first();
                if(empty($old_tagdata)){
                    $new_message =  $value;
                    $old_message = '';
                    GroupActivity::insert(array('key_name' => 'tag_added', 'user_id' => Auth::id(), 'group_id' => $request->id, 'old_value' => $old_message, 'new_value' => $new_message, 'user_type' => User::class, 'created_at' => Carbon::now()));
                    $group_tags = GroupTag::insert(['group_id' => $request->id, 'tag' => $value]);
                }
            }
        }

        //mail send supplier and admin and buyer for group close and expire
        $groupData = Groups::leftjoin('group_suppliers', 'groups.id', '=', 'group_suppliers.group_id')
            ->leftjoin('suppliers', 'group_suppliers.supplier_id', '=', 'suppliers.id')
            ->leftjoin('categories', 'groups.category_id', '=', 'categories.id')
            ->leftjoin('sub_categories', 'groups.subCategory_id', '=', 'sub_categories.id')
            ->leftjoin('products', 'groups.product_id', '=', 'products.id')
            ->leftjoin('units', 'groups.unit_id', '=', 'units.id')
            ->where('groups.id',$request->id)
            ->get(['groups.id', 'groups.name', 'groups.group_number', 'groups.target_quantity','groups.achieved_quantity','groups.end_date', 'suppliers.name as supplier_name', 'suppliers.email as supplier_email', 'categories.name as category_name', 'sub_categories.name as sub_category_name', 'products.name as product_name','units.name as unit_name']);
        $groupRanges = GroupSupplierDiscountOption::where('group_id', $group->id)->where('deleted_at', null)->get()->toArray();
        $url = route('group-details', ['id' => Crypt::encrypt($request->id)]);
        $shareLinks = getGroupsLinks($group->id);
        $groupsMailData = [
            'group' => $groupData[0],
            'groupRanges' => $groupRanges,
            'url' => $url,
            'shareLinks' =>$shareLinks,
        ];

        if($old_status !== $new_status) {
            if($new_status == '3'){   //--close--
                dispatch(new GroupCloseJob($groupsMailData,$groupData[0]->supplier_email)); //mail send admin and supplier
                dispatch(new GroupCloseBuyerJob($request->id,$url,$shareLinks)); // mail send buyer
            }
            if($new_status == '4'){    //--expire--
                dispatch(new GroupExpireJob($groupsMailData,$groupData[0]->supplier_email)); //mail send admin and supplier
                dispatch(new GroupExpireBuyerJob($request->id,$url,$shareLinks)); // mail send buyer
            }
        }
        //end mail group

        //mail send supplier and admin and buyer for group range change
        $buyer_count = GroupMember::where('group_members.group_id',$request->id )->count(); //Count buyer  in rfq
        if($update_group_range_flag > 0 ){
            dispatch(new GroupRangeChangedJob($groupsMailData,$groupData[0]->supplier_email)); //mail send admin and supplier
            if($buyer_count > 0 ){
                dispatch(new GroupRangeChangedBuyerJob($request->id,$url,$shareLinks)); // mail send buyer
            }
        }

        /**begin: system log**/
        $group->bootSystemActivities();
        /**end: system log**/
        return response()->json(array('success' => true,'groupId' => $request->id));
        //return redirect('/admin/groups/list');
    }

    public function add_update(Request $request){
        $date = str_replace('/', '-', $request->exp_date);
        $exp_date =  date('Y-m-d', strtotime($date));
        $update_activity = [];
        $group = Groups::join('group_suppliers', 'groups.id', '=', 'group_suppliers.group_id')
            ->select('name', 'category_id as category', 'subCategory_id as sub_category', 'product_id as product_name', 'unit_id as unit_name', 'description', 'end_date as exp_date', 'location_code', 'price', 'min_order_quantity', 'max_order_quantity','group_suppliers.supplier_id as supplier','group_margin','group_status','product_description')->find($request->id);
        // Activity log
        $i = 0;
        foreach ($request->all() as $key => $value) {
            if ($key == 'exp_date') {
                //$value = date('Y-m-d', strtotime($request->exp_date));
                $value =  $exp_date;//dd(date('Y-m-d', strtotime($group['exp_date'])),$value);
            }
            if (!in_array($key, ['_token', 'id', 's_id', 'custom_add', 'min_qty', 'max_qty', 'discount', 'discount_price','add_tags']) && $group[$key] != $value) {
                $update_activity[$i]['key_name'] = $key;
                $update_activity[$i]['user_id'] = Auth::id();
                $update_activity[$i]['group_id'] = $request->id;
                if(($key == 'exp_date') && (date('Y-m-d', strtotime($group['exp_date'])) != $value)){
                    $update_activity[$i]['old_value'] = date('Y-m-d', strtotime($group['exp_date']));
                    $update_activity[$i]['new_value'] = $value;
                } else {
                    $update_activity[$i]['old_value'] = $group[$key];
                    $update_activity[$i]['new_value'] = $value;
                }
                $update_activity[$i]['user_type'] = User::class;
                $update_activity[$i]['created_at'] = Carbon::now();
                $i++;
            }
        }
        //dd($update_activity);
        //signgle value activity done
        if ($update_activity) {
            GroupActivity::insert($update_activity);
        }
        //$supplier_id = 0;
        if (auth()->user()->role_id == 3) {
            $supplier = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();
        }else{
            $supplier = $request->supplier;
        }
        //manage target qty
        $target_quantity = 0;
        $key = max(array_values($request->max_qty));
        if(!empty($key)) {
            $target_quantity = $key;
        }

        //update group table data
        $group = Groups::find($request->id);
        $group->name = $request->name;
        $group->location_code = $request->location_code;
        $group->category_id = $request->category;
        $group->subCategory_id = $request->sub_category;
        $group->product_id = $request->product_name;
        $group->product_description = strip_tags($request->product_description);
        $group->unit_id = $request->unit_name;
        $group->description = $request->description;
        $group->group_status = $request->group_status;
        $group->end_date = $exp_date;
        $group->price = $request->price;
        $group->min_order_quantity = $request->min_order_quantity;
        $group->max_order_quantity = $request->max_order_quantity;
        $group->group_margin = $request->group_margin;
        $group->target_quantity = $target_quantity;
        $group->added_by = Auth::id();
        $group->save();
        $groupId = $request->id;

        $supplier_group = GroupSupplier::where(['group_id' => $request->id])->update(['supplier_id'=>$supplier]);

        //delete all old group range records
        GroupSupplierDiscountOption::where('group_id', $request->id)->delete();
        // insert group range table
        $discount_options = [];
        if (!empty($request->min_qty)){
            foreach ($request->min_qty as $key => $value){
                $discount_options[$key] = array(
                    'group_supplier_id' => $supplier,
                    'group_id' => $groupId,
                    'min_quantity' => $value,
                    'max_quantity' => $request->max_qty[$key],
                    'unit_id' => $request->unit_name,
                    'discount' => $request->discount[$key],
                    'discount_price' => $request->discount_price[$key],
                );
            }
        }
        $group_discount_option = GroupSupplierDiscountOption::insert($discount_options);

        //update tegs
        $tags  = $request->add_tags;
        $tagsArray = explode(",", $tags);
        $old_tagGet = GroupTag::where('group_id', $request->id)->get();
        $old_tagKey = $old_tagGet->pluck('tag')->toArray();
        $diff_values_tag = array_diff($old_tagKey, $tagsArray);
        if (!empty($diff_values_tag)){
            foreach ($diff_values_tag as $delete_value){
                $new_message = '';
                $old_message = $delete_value;
                GroupActivity::insert(array('key_name' => 'tag_deleted', 'user_id' => Auth::id(), 'group_id' => $request->id, 'old_value' => $old_message, 'new_value' => $new_message, 'user_type' => User::class, 'created_at' => Carbon::now()));
                GroupTag::where('tag', $delete_value)->delete();
            }
        }
        if($tagsArray[0] != ''){
            foreach($tagsArray as $key => $value){
                $old_tagdata = GroupTag::where('group_id', $request->id)->where('tag',$value)->first();
                if(empty($old_tagdata)){
                    $new_message =  $value;
                    $old_message = '';
                    GroupActivity::insert(array('key_name' => 'tag_added', 'user_id' => Auth::id(), 'group_id' => $request->id, 'old_value' => $old_message, 'new_value' => $new_message, 'user_type' => User::class, 'created_at' => Carbon::now()));
                    $group_tags = GroupTag::insert(['group_id' => $request->id, 'tag' => $value]);
                }
            }
        }

        return response()->json(array('success' => true,'groupId' => $request->id));
        //return redirect('/admin/groups/list');
    }

    public function groupImagesUpdate(Request $request){
        if($request->hasfile('group_image'))
        {
            $groupImageFilePath = '';
            foreach($request->file('group_image') as $key => $file)
            {
                $groupFileName = Str::random(10) . '_' . time() . 'group_image_' . $request->file('group_image')[$key]->getClientOriginalName();
                $groupImageFilePath = $request->file('group_image')[$key]->storeAs('uploads/group_image', $groupFileName, 'public');

                $insert[$key]['group_id'] = $request->id;
                $insert[$key]['image'] = $groupImageFilePath;
                $insert[$key]['added_by'] = Auth::id();

                $groupActivity[$key]['key_name'] = 'image_added';
                $groupActivity[$key]['user_id'] = Auth::id();
                $groupActivity[$key]['group_id'] = $request->id;
                $groupActivity[$key]['old_value'] = '';
                $groupActivity[$key]['new_value'] = $groupImageFilePath;
                $groupActivity[$key]['user_type'] = User::class;
                $groupActivity[$key]['created_at'] = Carbon::now();
            }
        }
        GroupActivity::insert($groupActivity);
        GroupImages::insert($insert);
        return response()->json(array('success' => true,'groupId' => $request->id));
    }

    public function groupImagesDelete(Request $request){
        $id = $request->id;
        $image = GroupImages::select('image','group_id')->where('id', $id)->first();
        if(!empty($image->image)){
            GroupActivity::insert(array('key_name' => 'image_deleted', 'user_id' => Auth::id(), 'group_id' => $image->group_id, 'old_value' => $image->image, 'new_value' => '', 'user_type' => User::class, 'created_at' => Carbon::now()));
            Storage::delete('/public/' . $image->image);
            GroupImages::where('id', $id)->forceDelete();
        }

        return response()->json(array('success' => true));
    }

    public function getGroupsImages($groupId){
        $groupImages = DB::table('group_images')
            ->where('group_id', $groupId)
            ->where('deleted_at', null)
            ->orderBy('image', 'desc')
            ->get();
        $storeImageCount = $groupImages->count();
        return response()->json(array('success' => true, 'groupImages' => $groupImages,'storeImageCount'=>$storeImageCount));

    }

    public function groupActivity($id)
    {
        $groupActivity = GroupActivity::where('group_id', $id)->orderBy('id', 'DESC')->get();
        $group = Groups::join('users', 'groups.added_by', '=', 'users.id')->where('groups.id', $id)->where('groups.deleted_at', null)->get(['groups.*','users.firstname','users.lastname','users.role_id']);
        $user = User::first('firstname','lastname','id','role_id')->toArray();
        $suppliers = Supplier::pluck('name', 'id')->toArray();

        $category= Category::pluck('name', 'id')->toArray();
        $subcategory = SubCategory::pluck('name', 'id')->toArray();
        $product = Product::pluck('name', 'id')->toArray();
        $unit = Unit::pluck('name', 'id')->toArray();
        $status = GroupStatus::pluck('name', 'id')->toArray();
        $activityhtml = view('admin/groups/groupactivities', ['groupActivies' => $groupActivity, 'group' => $group,'user' => $user,'suppliers' => $suppliers, 'category' => $category , 'subcategory' => $subcategory , 'product' => $product , 'unit' => $unit, 'status' => $status])->render();
        return response()->json(array('success' => true, 'activityhtml' => $activityhtml));
    }

    public function supplierProductRangeSetinGroup($productId,$supplierId){
        //dd($productId,$supplierId);
        $supplierProduct = DB::table('supplier_products')
            ->where('product_id', $productId)
            ->where('supplier_id', $supplierId)
            ->where('is_deleted', 0)
            ->get(['price', 'min_quantity', 'max_quantity', 'quantity_unit_id', 'description']);
        $supplierProductDiscountRanges =  DB::table('supplier_product_discount_ranges')
            ->where('product_id', $productId)->where('supplier_id',$supplierId)
            ->get();
        //dd($supplierProductDiscountRanges);
        $spdrCount = DB::table('supplier_product_discount_ranges')->where('product_id', $productId)->where('supplier_id',$supplierId)->count();
        $units = Unit::where('status',1)->where('is_deleted',0)->orderBy('id', 'DESC')->get(['id', 'name']);
        $returnHTML = view('admin/groups/supplierProductGetDiscountRange', ['supplierProductDiscountRanges' => $supplierProductDiscountRanges,'units' => $units,'supplierUnit' => $supplierProduct[0]->quantity_unit_id])->render();
        //dd($returnHTML);
        return response()->json(array('success' => true, 'supplierProduct' => $supplierProduct, 'html' => $returnHTML, 'spdrCount' => $spdrCount, 'units' => $units));
    }

    //Check group name is already exist or not
    public function checkGroupNameExist(Request $request) {
        $groupName = $request->input('groupName');
        $isExists = Groups::where('name','LIKE',"%{$groupName}%")->first();
        if($isExists){
            return response()->json(array("exists" => true));
        }else{
            return response()->json(array("exists" => false));
        }
    }

    //get buyer refund detail modal
    function getBuyerRefundDetail($orderId){
        $order = Order::find($orderId);
        $buyer = $order->user()->first(['id','firstname','lastname','email','phone_code','mobile']);
        $buyerCompany = $buyer->company()->first(['name']);
        $buyerBank = $buyer->buyerBank()->where('is_primary',1)->first(['bank_id','account_holder_name','account_number']);
        $bankDetails = null;
        if(isset($buyerBank)){
            $bankDetails = $buyerBank->AvailableBanks()->first(['name','code','logo']);
        }
        $returnHTML = view('admin/groups/buyer_refund_detail_modal', [
                                                                            'order' => $order,
                                                                            'buyer'=>$buyer,
                                                                            'buyerCompany'=>$buyerCompany,
                                                                            'buyerBank'=>$buyerBank,
                                                                            'bankDetails'=>$bankDetails,
                                                                            'buyerRefundAmount'=>getBuyerRefundAmount($order->quote_id),
                                                                            'disbursementCharge'=>getDisbursementCharge(),
                                                                            'minDisbursementAmount'=>(getMinDisbursementAmount()+getDisbursementCharge())
                                                                           ])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

}
