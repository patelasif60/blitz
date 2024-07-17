<?php

namespace App\Http\Controllers;

use App\Events\BuyerNotificationEvent;
use App\Events\BuyerRfqNotificationEvent;
use App\Events\rfqsCountEvent;
use App\Events\rfqsEvent;
use App\Http\Controllers\Buyer\RFN\RfnController;
use App\Jobs\RfqReceivedEmailBuyerJob;
use App\Jobs\RfqReceivedEmailSupplierJob;
use App\Models\CountryOne;
use App\Models\Product;
use App\Models\RfqMaximumProducts;
use App\Models\RfqAttachment;
use App\Models\Settings;
use App\Models\State;
use App\Models\Notification;
use App\Models\Supplier;
use App\Models\SupplierDealWithCategory;
use App\Models\SupplierProduct;
use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Rfq;
use App\Models\RfqProduct;
use App\Models\UserRfq;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Grade;
use App\Models\Unit;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAddresse;
use App\Models\Order;
use App\Models\RfqActivity;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Mail\SendRfqToBuyer;
use App\Models\Company;
use App\Models\GroupActivity;
use App\Models\GroupMember;
use App\Models\Groups;
use App\Models\PreferredSuppliersRfq;
use App\Models\UserCompanies;
use Illuminate\Support\Facades\Log;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\PreDec;

class RfqController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create buyer join group', ['only' => ['viewFullFormRfq']]);
    }

    function editQuickRfq(Request $request)
    {
        $getRfq = Rfq::where('id',$request->rfq_id)->where('is_deleted','0')->first();
        $getRfqAttachments = RfqAttachment::where('rfq_id',$request->rfq_id)->get();
        $oldRfqAttachment = $getRfqAttachments->implode('attached_document', ', ');
        $getRfqProduct = RfqProduct::where('rfq_id',$request->rfq_id)->where('is_deleted', 0)->get();
        $oldRfqQuantity = $getRfqProduct[0]->quantity;
        $input['user_id'] = Auth::user()->id;
        $input['rfq_id'] = $request->rfq_id;
        $change = 0;
        if($request->firstname && $getRfq->firstname !== $request->firstname){
            $input['key_name'] = 'firstname';
            $input['old_value'] = $getRfq->firstname??'';
            $input['new_value'] = $request->firstname;
            $change = 1;
            RfqActivity::create($input);
        }
        if($request->lastname && $getRfq->lastname !== $request->lastname){
            $input['key_name'] = 'lastname';
            $input['old_value'] = $getRfq->lastname??'';
            $input['new_value'] = $request->lastname;
            $change = 1;
            RfqActivity::create($input);
        }
        if( $request->email && $getRfq->email !== $request->email){
            $input['key_name'] = 'email';
            $input['old_value'] = $getRfq->email??'';
            $input['new_value'] = $request->email;
            RfqActivity::create($input);
            $change = 1;
        }
        if( $request->phone_code && $getRfq->phone_code !== '+'.$request->phone_code){
            $input['key_name'] = 'phone_code';
            $input['old_value'] = $getRfq->phone_code??'';
            $input['new_value'] = $request->phone_code;
            RfqActivity::create($input);
            $change = 1;
        }
        if( $request->mobile && $getRfq->mobile !== $request->mobile){
            $input['key_name'] = 'mobile';
            $input['old_value'] = $getRfq->mobile??'';
            $input['new_value'] = $request->mobile;
            RfqActivity::create($input);
            $change = 1;
        }
        if($request->address_name && $getRfq->address_name !== $request->address_name){
            $input['key_name'] = 'address_name';
            $input['old_value'] = $getRfq->address_name??'';
            $input['new_value'] = $request->address_name;
            RfqActivity::create($input);
            $change = 1;
        }
        if($request->address_line_1 && $getRfq->address_line_1 !== $request->address_line_1){
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
        if($request->city && $getRfq->city !== $request->city){
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
        if($request->state && $getRfq->state !== $request->state){
            $input['key_name'] = 'state';
            $input['old_value'] = $getRfq->state??'';
            $input['new_value'] = $request->state;
            RfqActivity::create($input);
            $change = 1;
        }
        if($request->pincode && ($getRfq->pincode != $request->pincode)){
            $input['key_name'] = 'pincode';
            $input['old_value'] = $getRfq->pincode??'';
            $input['new_value'] = $request->pincode;
            RfqActivity::create($input);
            $change = 1;
        }
        if($getRfq->is_require_credit !== (int)$request->is_require_credit){
            $input['key_name'] = 'is require credit';
            $input['old_value'] = $getRfq->is_require_credit;
            $input['new_value'] = $request->is_require_credit;
            RfqActivity::create($input);
            $change = 1;
        }
        if( $getRfq->rental_forklift !== (int)$request->rental_forklift){
            $input['key_name'] = 'rental forklift';
            $input['old_value'] = $getRfq->rental_forklift??'';
            $input['new_value'] = $request->rental_forklift;
            RfqActivity::create($input);
            $change = 1;
        }
        if($getRfq->unloading_services !== (int)$request->unloading_services){
            $input['key_name'] = 'unloading services';
            $input['old_value'] = $getRfq->unloading_services??'';
            $input['new_value'] = $request->unloading_services;
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
        //multiple
        $product_details = json_decode($request->product_details);

        $old_keys = $getRfqProduct->pluck('id')->toArray();
        $exits_product_key = array_reduce($product_details, function($carry, $item) {
            if (!isset($item->custom_add)){ $carry[] = $item->id; }
            return $carry;
        });
        if (!empty($product_details)){
            if (empty($exits_product_key)){
                $exits_product_key = [];
            }
            $diff_values = array_diff($old_keys, $exits_product_key);
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
            $i = count($old_keys)+101;
            foreach ($product_details as $product_detail){
                if (isset($product_detail->custom_add) && $product_detail->custom_add == 1){
                    $add_product = new RfqProduct;
                    $add_product->rfq_id = $input['rfq_id'];
                    $add_product->rfq_product_item_number = 'BRFQ-'.$input['rfq_id'].'/'.$i;
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
                    $add_product->expected_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->expected_date)->format('Y-m-d');
                    $add_product->save();
                    $i++;
                    $input['key_name'] = 'New Product';
                    $input['old_value'] = '';
                    $input['new_value'] = $product_detail->product_name;
                    RfqActivity::create($input);
                    $change = 1;
                } else {
                    $product_rfq = RfqProduct::find($product_detail->id);
                    if(!empty($product_rfq)) {
                        if ($product_detail->unit && $product_detail->unit != $product_rfq->unit_id) {
                            $input['key_name'] = 'unit';
                            $input['old_value'] = $product_rfq->unit_id ?? '';
                            $input['new_value'] = $product_detail->unit;
                            RfqActivity::create($input);
                            $change = 1;
                        }
                        if ($product_detail->product_name && $product_detail->product_name !== $product_rfq->product) {
                            $input['key_name'] = 'product name';
                            $input['old_value'] = $product_rfq->product ?? '';
                            $input['new_value'] = $product_detail->product_name;
                            RfqActivity::create($input);
                            $change = 1;

                        }
                        if ($product_detail->product_description && $product_detail->product_description !== $product_rfq->product_description) {
                            $input['key_name'] = 'product description';
                            $input['old_value'] = $product_rfq->product_description ?? '';
                            $input['new_value'] = $product_detail->product_description;
                            RfqActivity::create($input);
                            $change = 1;

                        }
                        if ($product_detail->quantity && (int)$product_detail->quantity !== (int)$product_rfq->quantity) {
                            $input['key_name'] = 'quantity';
                            $input['old_value'] = $product_rfq->quantity ?? '';
                            $input['new_value'] = $product_detail->quantity;
                            RfqActivity::create($input);
                            $change = 1;

                        }
                        if ($request->category && $product_rfq->sub_category) {
                            if ($request->category && $product_rfq->category !== $request->category) {
                                $input['key_name'] = 'category';
                                $input['old_value'] = $product_rfq->category ?? '';
                                $input['new_value'] = $request->category;
                                RfqActivity::create($input);
                                $change = 1;

                            }
                            if ($product_detail->product_sub_category && $product_detail->product_sub_category !== $product_rfq->sub_category) {
                                $input['key_name'] = 'sub category';
                                $input['old_value'] = $product_rfq->sub_category ?? '';
                                $input['new_value'] = $product_detail->product_sub_category;
                                RfqActivity::create($input);
                                $change = 1;

                            }
                        } else {
                            $rfqProduct['category'] = $request->othercategory;
                            if ($request->othercategory && $product_detail->category !== $request->othercategory) {
                                $input['key_name'] = 'category';
                                $input['old_value'] = $product_detail->category ?? '';
                                $input['new_value'] = $request->othercategory;
                                RfqActivity::create($input);
                                $change = 1;

                            }
                        }
                        if ($request->expected_date && strtotime($product_rfq->expected_date) !== strtotime($request->expected_date)) {
                            $input['key_name'] = 'expected date';
                            $input['old_value'] = $product_rfq->expected_date ?? '';
                            $input['new_value'] = Carbon::createFromFormat('d-m-Y', $request->expected_date)->format('Y-m-d');
                            RfqActivity::create($input);
                            $change = 1;
                        }
                        if (isset($request->expected_date)){
                            $expected_date = Carbon::createFromFormat('d-m-Y', $request->expected_date)->format('Y-m-d');
                        } else {
                            $expected_date = $product_rfq->expected_date;
                        }
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
                            'comment' => $request->comment,
                            'expected_date' => $expected_date
                        );
                        $rfqProduct = RfqProduct::where('id', $product_detail->id)->where('is_deleted', '0')->update($rfqProducts);
                    }
                }
            }
        }
        $inputrfq['firstname'] = $request->firstname ?? $getRfq->firstname;
        $inputrfq['lastname'] = $request->lastname ?? $getRfq->lastname;
        $inputrfq['phone_code'] = '+'.$request->phone_code ?? $getRfq->phone_code;
        $inputrfq['mobile'] = $request->mobile ?? $getRfq->mobile;
        $inputrfq['email'] = $request->email ?? $getRfq->email;
        $inputrfq['address_id'] = $request->useraddress_id ? $request->useraddress_id : '';
        $inputrfq['address_name'] = $request->address_name ? $request->address_name : '';
        $inputrfq['address_line_1'] = $request->address_line_1 ? $request->address_line_1 : '';
        $inputrfq['address_line_2'] = $request->address_line_2 ? $request->address_line_2 : '';
        $inputrfq['city']       = $request->cityId > 0 ? '' : $request->city;
        $inputrfq['state']      = $request->stateId > 0 ? '' : $request->state;
        $inputrfq['city_id']    = $request->cityId;
        $inputrfq['state_id']   = $request->stateId;
        $inputrfq['sub_district'] = $request->sub_district ? $request->sub_district : '';
        $inputrfq['district'] = $request->district ? $request->district : '';
        $inputrfq['pincode'] = $request->pincode ? $request->pincode : '';
        $inputrfq['rental_forklift'] = $request->rental_forklift ?? $getRfq->rental_forklift;
        $inputrfq['unloading_services'] = $request->unloading_services ?? $getRfq->unloading_services;
        $inputrfq['is_preferred_supplier'] = $request->is_preferred_supplier ?? 0;

        /**
         * Vrutika Rana 25/07/2022
         * add Multiple RFQ Attachments start
         */

        if ($request->file('attached_document')) {

            foreach ($request->attached_document as $attachment) {
                $rfqAttachmentFileName = Str::random(5) . '_' . time() . '_' . $attachment->getClientOriginalName();
                $rfqAttachmentFilePath = $attachment->storeAs('uploads/rfq_docs/'.$getRfq->reference_number , $rfqAttachmentFileName, 'public');
                $rfqAttachmentArray[] = array(
                    'rfq_id' => $request->rfq_id,
                    'attached_document' => $rfqAttachmentFilePath
                );
            }
            RfqAttachment::insert($rfqAttachmentArray);
        }

         /**
         * add Multiple RFQ Attachments end
         */

        $inputrfq['termsconditions_file'] = $request->termsconditions_file ?? $getRfq->termsconditions_file;
        if ($request->file('termsconditions_file')) {
            $termsconditionsFileName = Str::random(5) . '_' . time() . 'termsconditions_file_' . $request->file('termsconditions_file')->getClientOriginalName();
            $termsconditionsFilePath = $request->file('termsconditions_file')->storeAs('uploads/rfq_docs/rfq_tcdoc', $termsconditionsFileName, 'public');
            if (!empty($getRfq->termsconditions_file)) {
                Storage::delete('/public/' . $getRfq->termsconditions_file);
            }
            $inputrfq['termsconditions_file'] = $termsconditionsFilePath;
        }
        $inputrfq['is_require_credit'] = $request->is_require_credit ?? 0;

        $inputrfq['payment_type'] = 0;
        if($request->is_require_credit == 1)
        {
            $inputrfq['payment_type'] = 1;
            if ($request->credit_days_id == "lc" ||  $request->credit_days_id == "skbdn")
            {
                $inputrfq['payment_type'] = 3;
            }
            if ($request->credit_days_id == "skbdn")
            {
                $inputrfq['payment_type'] = 4;
            }
        }
        $inputrfq['credit_days'] = $inputrfq['payment_type'] == 1 ? $request->credit_days_id : null;

        $rfq = Rfq::where('id',$request->rfq_id)->where('is_deleted','0')->update($inputrfq);

        //Update "preferred_suppliers_rfqs" if there are update in supplier ids (Ronak M - 05/07/2022)
        if(isset($request->is_preferred_supplier) && $request->is_preferred_supplier == "1") {
            $selectedSuppliersArray = PreferredSuppliersRfq::where('rfq_id',$request->rfq_id)->get()->pluck('supplier_id')->toArray();
            if($request->is_preferred_supplier == "1" && $request->preferredSuppliersIds == null) {
                $request->preferredSuppliersIds = $selectedSuppliersArray;
            } else {
                PreferredSuppliersRfq::where('rfq_id',$request->rfq_id)->whereIn('supplier_id', $selectedSuppliersArray)->delete();

                $newSuppliersArray = [];
                $allSelectedSuppliers = explode(",",$request->preferredSuppliersIds);

                foreach($allSelectedSuppliers as $newSupplierId) {
                    $newSuppliersArray[] = array(
                        'user_id' => Auth()->user()->id,
                        'supplier_id' => $newSupplierId,
                        'rfq_id' => $request->rfq_id,
                    );
                }
                PreferredSuppliersRfq::insert($newSuppliersArray);
            }
        }
        //End

        if (!empty($request->useraddress_id) || $request->useraddress_id != 0 ){
            if ($request->useraddress_id == 'Other'){
                $user_address = new UserAddresse();
                $user_address->user_id = Auth::user()->id;
                $user_address->address_name = $request->address_name;
                $user_address->company_id = Auth::user()->default_company ?? null;

            } else {
                $user_address = UserAddresse::find($request->useraddress_id);
            }
            $user_address->address_line_1 = $request->address_line_1;
            $user_address->address_line_2 = $request->address_line_2;
            $user_address->sub_district = $request->sub_district;
            $user_address->district = $request->district;
            $user_address->city = $request->cityId > 0 ? null : $request->city;
            $user_address->state = $request->stateId > 0 ? null : $request->state;
            $user_address->city_id =  $request->cityId;
            $user_address->state_id =  $request->stateId;
            $user_address->pincode = $request->pincode;
            $user_address->save();
            $rfqAddressID = $user_address->id;
            Rfq::where('id',$request->rfq_id)->update(['address_id'=>$rfqAddressID]);
        }

        $userrfq = UserRfq::where('rfq_id',$request->rfq_id)->where('is_deleted', 0)->first();
        /**get address based on permissions */
        $userAddress = UserAddresse::all();
        $isOwner = User::checkCompanyOwner();
        $userAddressCount = UserAddresse::getCompanyBuyerAddress();
        /**get address based on permissions */
        //Send mail to suppliers (Ronak M - 12/07/2022)
        dispatch(new RfqReceivedEmailBuyerJob($request->rfq_id));
        dispatch(new RfqReceivedEmailSupplierJob($request->rfq_id));

        if($userrfq && $change == 1){
            $commanData = [];
            if((int)Auth::user()->role_id == 1){
                $commanData = array('rfq_number' => $getRfq->reference_number, 'updated_by' => 'blitznet Team', 'icons' => 'fa-user');
            }else{
                $commanData = array('rfq_number' => $getRfq->reference_number, 'updated_by' => Auth::user()->full_name, 'icons' => 'fa-user');
            }
            buyerNotificationInsert($userrfq->user_id, 'Update RFQ', 'buyer_update_rfq', 'rfq', $request->rfq_id, $commanData);
            broadcast(new BuyerNotificationEvent());
        }

        if(isset($request->groupId) && $request->groupId > 0  && isset($request->product_details)) {
            $productDetails = json_decode($request->product_details);
            if(sizeof($productDetails) == 1){
                $productDetails = $productDetails[0];
                $group = Groups::find($request->groupId);
                $group->reached_quantity = ($group->reached_quantity - $oldRfqQuantity) + $productDetails->quantity;
                $group->save();
            }
        }

        $rfqProduct = $rfqProduct??[];
        //find maximum number of product to be added
        $max_product = Settings::where('key', 'multiple_rfq_max_added_product')->first()->value;
        $rfq_max_product = RfqMaximumProducts::where('rfq_id', $request->rfq_id)->first();
        if (!empty($rfq_max_product)){
            if ($max_product>=$rfq_max_product->max_products){
                RfqMaximumProducts::where(['rfq_id'=>$request->rfq_id])->update(['max_products'=>$max_product]);
            }
        }
        return response()->json(array('success' => true, 'quote' => $rfq, 'rfqProduct' => $rfqProduct, 'userAddressCount' => count($userAddressCount)));
    }

    function createQuickRfq(Request $request)
    {
        $rfq = new Rfq;
        $rfq->firstname = $request->firstname;
        $rfq->lastname = $request->lastname;
        $rfq->mobile = $request->mobilenumber;
        $rfq->email = $request->email;
        $rfq->pincode = $request->pincode;
        $rfq->status_id = 1;
        $rfq->rental_forklift = $request->rental_forklift;
        $rfq->unloading_services = $request->unloading_services;
        $rfq->save();
        $rfqId = $rfq->id;
        $rfq->reference_number = 'BRFQ-' . $rfq->id;
        $rfq->save();

        $rfqProduct = new RfqProduct;
        $rfqProduct->rfq_id = $rfqId;
        if ($request->category && $request->subcategory) {
            $rfqProduct->category = $request->category;
            $rfqProduct->sub_category = $request->subcategory;
        } else {
            $rfqProduct->category = $request->prod_cat;
        }
        $rfqProduct->product_description = $request->prod_desc;
        $rfqProduct->product = $request->prod_name;
        $rfqProduct->quantity = $request->quantity;
        $rfqProduct->unit_id = $request->unit;
        //$rfqProduct->reference_number = abs(crc32(uniqid()));
        $rfqProduct->comment = $request->comment;
        $rfqProduct->expected_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->expected_date)
            ->format('Y-m-d');
        $rfqProduct->save();

        if (Auth::user()) {
            $userRfq = new  UserRfq;
            $userRfq->user_id = Auth::user()->id;
            $userRfq->rfq_id = $rfqId;
            $userRfq->save();

            // $userActivity = new UserActivity();
            // $userActivity->user_id = Auth::user()->id;
            // $userActivity->activity = 'Request for quote';
            // $userActivity->save();
            if (Auth::user()->id) {
                $userActivity = new UserActivity();
                $userActivity->user_id = Auth::user()->id;
                $userActivity->activity = 'RFQ Created - ' . $rfq->reference_number;
                $userActivity->type = 'rfq';
                $userActivity->record_id = $rfq->id;
                $userActivity->save();
            }
        }
        dispatch(new RfqReceivedEmailBuyerJob($rfq->id));
        return response()->json(array('success' => true, 'quote' => $rfq, 'rfqProduct' => $rfqProduct));
    }

    function createQuickRfqPost(Request $request)
    {
        /**begin: Set Category RFN*/
        if (session()->has('isRfn') && session()->has('rfn')) {
            $rfnDetails = session('rfn');
            $request->category_id = $rfnDetails->item_category_id;
        }
        /**end: Set Category RFN*/

	    $groupId = null;
        if(isset($request->groupId) && $request->groupId){
            $groupId = $request->groupId;
        }
        if ($request->isRepeatRfq == 1){
            $rfqRepeatCount = Rfq::find($request->isRepeatRfqId);
            $rfqRepeatCount->no_of_repeat = $rfqRepeatCount->no_of_repeat + 1;
            $rfqRepeatCount->save();
            $existingRfqAttachments = RfqAttachment::where('rfq_id',$request->isRepeatRfqId)->get();
        }
        $termsconditionsFilePath = '';
        if ($request->file('termsconditions_file')) {
             $termsconditionsFileName = Str::random(5) . '_' . time() . 'termsconditions_file_' . $request->file('termsconditions_file')->getClientOriginalName();
             $termsconditionsFilePath = $request->file('termsconditions_file')->storeAs('uploads/rfq_docs/rfq_tcdoc', $termsconditionsFileName, 'public');
        } else if ($request->oldtermsconditions_file) {
            $old_tcdocument = explode('/',$request->oldtermsconditions_file);
            if ($request->isRepeatRfq == 1){
                $oldTCFile = $old_tcdocument[3];
                $oldTCFile = Str::substr($oldTCFile,stripos($oldTCFile, "termsconditions_file_") + 21);
                $termsconditionsFileName = Str::random(5) . '_' . time() . 'termsconditions_file_' . $oldTCFile;
            }else{
                $oldTCFile = $old_tcdocument[2];
                $termsconditionsFileName = Str::random(5) . '_' . time() . '_' . $oldTCFile;
            }
            if (Storage::exists('/public/'.$request->oldtermsconditions_file)){
                $termsconditionsFilePath = 'uploads/rfq_docs/rfq_tcdoc/'.$termsconditionsFileName;
                Storage::copy('/public/'.$request->oldtermsconditions_file, '/public/'.$termsconditionsFilePath);
            }else{
                $termsconditionsFilePath = "";
            }
        }else{
            $termsconditionsFilePath= "";
        }
        $payment_type = 0;
        if($request->is_require_credit == 1)
        {
            $payment_type = 1;
            if ($request->credit_days_id == "lc" ||  $request->credit_days_id == "skbdn")
            {
                $payment_type = 3;
            }
            if ($request->credit_days_id == "skbdn")
            {
                $payment_type = 4;
            }
        }
        //dd($payment_type);
        $rfq = Rfq::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phone_code' => $request->phone_code?'+'.$request->phone_code:'',
            'mobile' => $request->mobile_number,
            'email' => $request->email,
            'address_name' => $request->address_name,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'sub_district' => $request->sub_district,
            'district' => $request->district,
            'city'      => $request->cityId > 0 ? '' : $request->city,
            'state' => $request->stateId > 0 ? '' : $request->state,
            'city_id'   =>  $request->cityId,
            'state_id'  =>  $request->stateId,
            'pincode' => $request->pincode,
            'status_id' => 1,
            'rental_forklift' => $request->rental_forklift,
            'unloading_services' => $request->unloading_services,
            'is_require_credit' => $request->is_require_credit??0,
            'payment_type'=>$payment_type,
            'credit_days' => $payment_type == 1 ? $request->credit_days_id : null,
            'is_favourite' => (int)$request->is_favourite,
            'termsconditions_file' => $termsconditionsFilePath,
            'group_id' => $groupId,
            'is_preferred_supplier' => $request->is_preferred_supplier ?? 0,
        ]);
        $rfqId = $rfq->id;
        $rfq = Rfq::find($rfqId);
        $rfq->reference_number = 'BRFQ-' . $rfq->id;
        // While create rfq add company id defaults
        $rfq->company_id = Auth::user()->default_company ?? null;
        $rfq->save();

        /**begin: RFN*/
        if (isset($rfnDetails) && isset((json_decode($request->product_details))[0]->product_id)) {
            $getProductData = array_column(json_decode($request->product_details),'product_id');
            if (in_array($rfnDetails->item_id,$getProductData)) {
                $rfnDta = collect();
                $rfnDta->rfnId = $rfnDetails->rfn_id;
                $rfnDta->rfnResponseId = $rfnDetails->rfn_response_id;
                $rfnDta->rfqId = $rfq->id;
                (new RfnController())->updateRfnConversion($rfnDta);
            }
        }
        /**end: RFN*/

        //add Multiple rfqAttachment (Vrutika 22/07/22)
        $rfqAttachmentFilePath = '';
        if ($request->file('rfq_attachment_doc')) {
            foreach ($request->rfq_attachment_doc as $attachment) {

                $rfqAttachmentFileName = Str::random(5) . '_' . time() . '_' . $attachment->getClientOriginalName();
                $rfqAttachmentFilePath = $attachment->storeAs('uploads/rfq_docs/'.$rfq->reference_number, $rfqAttachmentFileName, 'public');
                $rfqAttachmentArray[] = array(
                    'rfq_id' => $rfqId,
                    'attached_document' => $rfqAttachmentFilePath
                );
            }
            RfqAttachment::insert($rfqAttachmentArray);
        }elseif (isset($existingRfqAttachments) && count($existingRfqAttachments)>0){
            foreach ($existingRfqAttachments as $attachment) {
                $repeatAttachmentDocument = explode('/',$attachment->attached_document);
                if (Storage::exists('/public/'.$attachment->attached_document)){
                   $Newpath = Storage::path('public/uploads/rfq_docs/'.$rfq->reference_number.'/');
                    if(!File::isDirectory($Newpath)){
                        File::makeDirectory($Newpath, 0777, true, true);
                    }
                    $rfqAttachmentFileName = Str::random(5) . '_' . time() . '_' . (Str::substr($repeatAttachmentDocument[3],17));
                    $rfqAttachmentFilePath = 'uploads/rfq_docs/'.$rfq->reference_number.'/'.$rfqAttachmentFileName;
                    Storage::copy('public/'.$attachment->attached_document , 'public/'.$rfqAttachmentFilePath);
                }else{
                    $rfqAttachmentFilePath = "";
                }
                $rfqAttachmentArray[] = array(
                    'rfq_id' => $rfqId,
                    'attached_document' => $rfqAttachmentFilePath
                );
            }
            RfqAttachment::insert($rfqAttachmentArray);
        }
        //Insert  supplier_ids  in "preferred_suppliers_rfqs" (Ronak M - 05/07/2022)
        if(isset($request->is_preferred_supplier) && $request->is_preferred_supplier == "1") {
            //Insert preferred suppliers data in "preferred_suppliers_rfqs" table
            $suppliersIds = explode(",", $request->preferredSuppliersIds);
            $preferredSuppRfqArray = array();
            foreach($suppliersIds as $supp_id) {
                $preferredSuppRfqArray[] = array(
                    'user_id' => Auth::user()->id,
                    'supplier_id' => $supp_id,
                    'rfq_id' => $rfqId
                );
            }
            PreferredSuppliersRfq::insert($preferredSuppRfqArray);
        }
        //End

        // update address every time change
        if (!empty($request->useraddress_id) || $request->useraddress_id != 0 ){
            if ($request->useraddress_id == 'Other' || $request->useraddress_id == 0){
                $user_address = new UserAddresse();
                $user_address->user_id = Auth::user()->id;
                $user_address->address_name = $request->address_name;
                $user_address->company_id = Auth::user()->default_company ?? null;
            } else {
                $user_address = UserAddresse::find($request->useraddress_id);
            }
            $user_address->address_line_1 = $request->address_line_1;
            $user_address->address_line_2 = $request->address_line_2;
            $user_address->sub_district = $request->sub_district;
            $user_address->district = $request->district;
            $user_address->city = $request->cityId > 0 ? '' : $request->city;
            $user_address->state = $request->stateId > 0 ? '' : $request->state;
            $user_address->city_id =  $request->cityId;
            $user_address->state_id =  $request->stateId;
            $user_address->pincode = $request->pincode;
            $user_address->save();
            $rfqAddressID = $user_address->id;
            $rfq->address_id = $rfqAddressID;
            $rfq->save();
        }

        //Store "other" address in user_addresses
        /*if ($request->useraddress_id == 0){
            $userAddress = new UserAddresse();
            $userAddress->user_id = Auth::user()->id;
            $userAddress->address_name = $request->address_name;
            $userAddress->address_line_1 = $request->address_line_1;
            $userAddress->address_line_2 = $request->address_line_2;
            $userAddress->sub_district = $request->sub_district;
            $userAddress->district = $request->district;
            $userAddress->city = $request->city;
            $userAddress->state = $request->state;
            $userAddress->pincode = $request->pincode;
            $userAddress->save();
        }*/

        //loccal storage data store here
        $product_data = json_decode($request->product_details);
        $multiple_rfq = [];
        $subCategoryIdArray = [];
        $i = 101;
        if (!empty($product_data)) {
            foreach ($product_data as $value) {
                $multiple_rfq[] = array(
                    'rfq_id' => $rfqId,
                    'rfq_product_item_number' => 'BRFQ-'.$rfqId.'/'.$i,
                    'category' => !empty($request->category) ? $request->category : $request->product_category,
                    'category_id' => $request->category_id,
                    'sub_category' => $value->product_sub_category,
                    'sub_category_id' => $value->product_sub_category_id,
                    'product_description' => $value->product_description,
                    'product' => $value->product_name,
                    'product_id' => $value->product_id,
                    'quantity' => $value->quantity,
                    'unit_id' => $value->unit,
                    'comment' => $request->comment,
                    'expected_date' => \Carbon\Carbon::createFromFormat('d-m-Y', $request->expected_date)->format('Y-m-d')
                );
                array_push($subCategoryIdArray,$value->product_sub_category_id);
                $i++;
            }
        }
        RfqProduct::insert($multiple_rfq);

        $max_product = Settings::where('key', 'multiple_rfq_max_added_product')->first()->value;

        $rfq_product_limit = RfqMaximumProducts::create([
            'rfq_id' => $rfqId,
            'max_products' => $max_product,
        ]);
        if (Auth::user()) {
            $userRfq = new  UserRfq;
            $userRfq->user_id = Auth::user()->id;
            $userRfq->rfq_id = $rfqId;
            $userRfq->save();

            if (Auth::user()->id) {
                /*$userActivity = new UserActivity();
                $userActivity->user_id = Auth::user()->id;
                $userActivity->activity = 'RFQ Created - ' . $rfq->reference_number;
                $userActivity->type = 'rfq';
                $userActivity->record_id = $rfq->id;
                $userActivity->save();*/
                $comman_data = array('rfq_number' => $rfq->reference_number, 'updated_by' => Auth::user()->full_name,'icons' => 'fa-user');
                buyerNotificationInsert(Auth::user()->id, 'RFQ Created', 'buyer_rfq_create', 'rfq', $rfq->id, $comman_data);
            }
        }

        /**group related changes add by ronak bhabhor 09-05-2022 */
        if($request->groupId > 0){
            $groupMember = new GroupMember();
            $company = UserCompanies::where('user_id',Auth::user()->id)->first(['company_id']);
            $groupMember->group_id = $request->groupId;
            $groupMember->user_id = Auth::user()->id;
            $groupMember->company_id = $company->company_id;
            $groupMember->rfq_id = $rfqId;
            $groupMember->save();

            //Store in group activity that user has joined a group
            $groupActivity = new GroupActivity();$groupActivity->user_id = Auth::user()->id;
            $groupActivity->group_id = $request->groupId;
            $groupActivity->key_name = 'group_joined';
            $groupActivity->old_value = '';
            $groupActivity->new_value = 'Group Joined - BRFQ '. $rfqId;
            $groupActivity->user_type = User::class;
            $groupActivity->save();

            //Update "reached_quantity" in `groups` table when user placed rfq and join group (28-03-2022 Ronak)
            // if(isset($request->groupId) && isset($request->product_details)) {
            if(isset($request->groupId) && $request->groupId > 0  && isset($request->product_details)) {
                /* $productDetails = json_decode($request->product_details)[0];
                $group = Groups::find($request->groupId);
                $group->reached_quantity = $group->reached_quantity + $productDetails->quantity;
                $group->save(); */
                $productDetails = json_decode($request->product_details);
                if(sizeof($productDetails) == 1){
                    $productDetails = $productDetails[0];
                    $group = Groups::find($request->groupId);
                    $group->reached_quantity = $group->reached_quantity + $productDetails->quantity;
                    $group->save();
                }
            }

        }

        // change query based on role and permissions
        $authUser = Auth::user();//dd($authUser->default_company);
        $isOwner = User::checkCompanyOwner();
        $groupJoinedCount = GroupMember::leftjoin('groups','group_members.group_id','=','groups.id')
        ->leftjoin('group_images','groups.id','=','group_images.group_id')
        ->leftjoin('units','groups.unit_id','=','units.id')
        ->leftjoin('products','groups.product_id','=','products.id')
        ->leftjoin('rfqs','groups.id','=','rfqs.group_id'); // added for roles and permission

        if($isOwner == true || $authUser->hasPermissionTo('list-all buyer groups')){
            $groupJoinedCount = $groupJoinedCount->where('rfqs.company_id', $authUser->default_company);
        }else {
            $groupJoinedCount = $groupJoinedCount->where('group_members.user_id', Auth::user()->id)->where('rfqs.company_id', $authUser->default_company);
        }

        $groupJoinedCount = $groupJoinedCount->where('group_members.is_deleted', 0)
        ->orderBy('groups.id', 'desc')->groupBy('groups.id')->get(['groups.id']);




        $rfqCount = DB::table('user_rfqs')
        ->join('rfqs', 'user_rfqs.rfq_id', '=', 'rfqs.id')
        ->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id') // Added for role & permissions
        ->where('rfqs.is_deleted', 0);
        $isOwner = User::checkCompanyOwner();
        if($isOwner == true || $authUser->hasPermissionTo('list-all buyer rfqs')){
            $rfqCount = $rfqCount->where('rfqs.company_id', $authUser->default_company);
        }else {
            $rfqCount = $rfqCount->where('user_rfqs.user_id', Auth::user()->id)->where('rfqs.company_id', Auth::user()->default_company);
        }
        $rfqCount = $rfqCount->distinct('rfq_products.rfq_id')->count();

        /**get address based on permissions */
        $userAddress = UserAddresse::getCompanyBuyerAddress();
        $userAddressCount = $userAddress->count();
        /**get address based on permissions */



        dispatch(new RfqReceivedEmailBuyerJob($rfq->id));
        dispatch(new RfqReceivedEmailSupplierJob($rfq->id));

	    //$rfqSuppliers = $rfq->rfqSuppliers()->groupBy('supplier_id')->get(['supplier_id']);
        $supplierProducts = SupplierProduct::with('products')->whereHas('products', function($q) use($subCategoryIdArray){
            $q->whereIn('subcategory_id',$subCategoryIdArray);
        })->get();
        $getSupplierProductsSupplierIds = $supplierProducts->pluck('supplier_id')->toArray();
        $supplierDealWithCategory = SupplierDealWithCategory::whereIN('sub_category_id',$subCategoryIdArray)->pluck('supplier_id')->toArray();
        $suppliersAll = array_merge($getSupplierProductsSupplierIds,$supplierDealWithCategory);
        $rfqSuppliers = array_unique($suppliersAll);
            foreach ($rfqSuppliers as $rfqSupplier){
                $supplier = Supplier::where('id',$rfqSupplier)->first(['id','name','contact_person_email','alternate_email']);
            	$supplierActivity = new Notification();
            	$supplierActivity->user_id = auth()->user()->id;
            	$supplierActivity->supplier_id = $supplier->id;
            	$supplierActivity->user_activity = 'Generate RFQ';
            	$supplierActivity->translation_key = 'rfq_generate_notification';
            	$supplierActivity->notification_type = 'rfq';
            	$supplierActivity->notification_type_id = $rfq->id;
            	$supplierActivity->save();
            }
        $getAllAdmin = getAllAdmin();
        $sendAdminNotification = [];
        if (!empty($getAllAdmin)){
            foreach ($getAllAdmin as $key => $value){
                $sendAdminNotification[] = array(
                    'user_id' => Auth::user()->id,
                    'admin_id' => $value,
                    'user_activity' => 'Generate RFQ',
                    'translation_key' => 'rfq_generate_notification',
                    'notification_type' => 'rfq',
                    'created_at' => Carbon::now(),
                    'notification_type_id'=> $rfq->id
                );
            }
        }
        Notification::insert($sendAdminNotification);
        broadcast(new BuyerNotificationEvent());
        broadcast(new rfqsEvent());
        broadcast(new rfqsCountEvent());
        broadcast(new BuyerRfqNotificationEvent());
        return response()->json(array('success' => true, 'rfqCount' => $rfqCount, 'groupJoinedCount' => count($groupJoinedCount), 'userAddressCount' => $userAddressCount));
    }

    function viewFullFormRfq()
    {
        $category = Category::all()->where('is_deleted', 0);
        $subCategory = SubCategory::all()->where('is_deleted', 0);
        $brand = Brand::all()->where('is_deleted', 0);
        $grade = Grade::all()->where('is_deleted', 0);
        $unit = Unit::all()->where('is_deleted', 0);
        $userAddress = UserAddresse::all()->where('user_id', Auth::user()->id)->where('is_deleted', 0)->sortByDesc('id');
        $defaultAddress = UserAddresse::where('user_id', Auth::user()->id)->where('is_deleted', 0)->where('default_address', 1)->where('is_deleted', 0)->get()->first();
        $states = State::where('country_id', CountryOne::DEFAULTCOUNTRY)->get();
        $max_attachments = Settings::where('key', 'multiple_rfq_attachments')->first()->value;
        return view('dashboard/contact', ['category' => $category, 'subCategory' => $subCategory, 'brand' => $brand, 'grade' => $grade, 'unit' => $unit, 'userAddress' => $userAddress, 'states' => $states, 'defaultAddress' => $defaultAddress,'max_attachments'=>$max_attachments]);
    }

    function addFullRfq(Request $request)
    {
        $rfq = new Rfq;
        $rfq->firstname = $request->firstname;
        $rfq->lastname = $request->lastname;
        $rfq->phone_code = $request->phone_code?'+'.$request->phone_code:'';
        $rfq->mobile = $request->mobile;
        $rfq->email = $request->email;
        $rfq->address_name = $request->address_name;
        $rfq->address_line_1 = $request->address_line_1;
        $rfq->address_line_2 = $request->address_line_2;
        $rfq->sub_district = $request->sub_district;
        $rfq->district = $request->district;
        $rfq->city      = $request->cityId > 0 ? null : $request->city;
        $rfq->state     = $request->stateId > 0 ? null : $request->state;
        $rfq->city_id   =  $request->cityId;
        $rfq->state_id  =  $request->stateId;
        $rfq->pincode = $request->pincode;
        $rfq->status_id = 1;
        $rfq->company_id = Auth::user()->default_company;
        $rfq->rental_forklift = $request->rental_forklift;
        $rfq->unloading_services = $request->unloading_services;
        $rfq->is_require_credit = $request->is_require_credit??0;
        $rfq->save();
        $rfqId = $rfq->id;
        $rfq->reference_number = 'BRFQ-' . $rfq->id;
        $rfq->group_id = $request->groupId; //Update group_id in "rfqs" table (09-05-2022 Ronak M)
        $rfq->save();

        //add Multiple rfqAttachment (Vrutika 29/07/22)
        $rfqAttachmentFilePath = '';
        if ($request->file('rfq_attachment_doc')) {
            foreach ($request->rfq_attachment_doc as $attachment) {

                $rfqAttachmentFileName = Str::random(5) . '_' . time() . '_' . $attachment->getClientOriginalName();
                $rfqAttachmentFilePath = $attachment->storeAs('uploads/rfq_docs/'.$rfq->reference_number, $rfqAttachmentFileName, 'public');
                $rfqAttachmentArray[] = array(
                    'rfq_id' => $rfqId,
                    'attached_document' => $rfqAttachmentFilePath
                );
            }
            RfqAttachment::insert($rfqAttachmentArray);
        }
        // update address every time change
        if (!empty($request->useraddress_id) || $request->useraddress_id != 0 ){
            $user_address = UserAddresse::find($request->useraddress_id);
            $user_address->address_line_1 = $request->address_line_1;
            $user_address->address_line_2 = $request->address_line_2;
            $user_address->sub_district = $request->sub_district;
            $user_address->district = $request->district;
            $user_address->city      = $request->cityId > 0 ? '' : $request->city;
            $user_address->state     = $request->stateId > 0 ? '' : $request->state;
            $user_address->city_id   =  $request->cityId;
            $user_address->state_id  =  $request->stateId;
            $user_address->pincode = $request->pincode;
            $user_address->save();
        }

        //Store "other" address in user_addresses
        if ($request->useraddress_id == 0){

            $userAddress = new UserAddresse();
            $userAddress->user_id = Auth::user()->id;
            $userAddress->address_name = $request->address_name;
            $userAddress->address_line_1 = $request->address_line_1;
            $userAddress->address_line_2 = $request->address_line_2;
            $userAddress->sub_district = $request->sub_district;
            $userAddress->district = $request->district;
            $userAddress->city      = $request->cityId > 0 ? '' : $request->city;
            $userAddress->state     = $request->stateId > 0 ? '' : $request->state;
            $userAddress->city_id   =  $request->cityId;
            $userAddress->state_id  =  $request->stateId;
            $userAddress->pincode = $request->pincode;
            $userAddress->save();
        }

        $rfqProduct = new RfqProduct;
        $rfqProduct->rfq_id = $rfqId;
        $rfqProduct->rfq_product_item_number = 'BRFQ-'.$rfqId.'/101';
        $rfqProduct->category = $request->productCategory;
        $rfqProduct->category_id = $request->categoryId;
        $rfqProduct->sub_category = $request->productSubCategory;
        $rfqProduct->sub_category_id = $request->sub_categoryId;
        $rfqProduct->product = $request->product;
        $rfqProduct->product_id = $request->GroupProductId;
        $rfqProduct->product_description = $request->grpProdDesc ? $request->grpProdDesc : $request->productDescription;
        //$rfqProduct->brand_ids = $request->brandData;
        //$rfqProduct->other_preferred_brand = $request->other_preferred_brand;
        //$rfqProduct->grade_ids = $request->gradeData;
        //$rfqProduct->other_preferred_grade = $request->other_preferred_grade;
        $rfqProduct->quantity = $request->quantity;
        $rfqProduct->unit_id = $request->unit;
        $rfqProduct->expected_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->expected_date)->format('Y-m-d');
        $rfqProduct->comment = $request->comment;
        //$rfqProduct->reference_number = abs(crc32(uniqid()));
        $rfqProduct->save();

        if (Auth::user()) {
            $userRfq = new  UserRfq;
            $userRfq->user_id = Auth::user()->id;
            $userRfq->rfq_id = $rfqId;
            $userRfq->save();

            if (Auth::user()->id) {
                $userActivity = new UserActivity();
                $userActivity->user_id = Auth::user()->id;
                $userActivity->activity = 'RFQ Created - ' . $rfq->reference_number;
                $userActivity->type = 'rfq';
                $userActivity->record_id = $rfq->id;
                $userActivity->save();
            }
        }

        // Insert data into "group_members" (25-03-22 Ronak M)
        $groupMember = new GroupMember();
        $company = UserCompanies::where('user_id',Auth::user()->id)->first(['company_id']);
        $groupMember->group_id = $request->groupId;
        $groupMember->user_id = Auth::user()->id;
        $groupMember->company_id = $company->company_id;
        $groupMember->rfq_id = $rfqId;
        $groupMember->save();

        //Store in group activity that user has joined a group
        $groupActivity = new GroupActivity();
        $groupActivity->user_id = Auth::user()->id;
        $groupActivity->group_id = $request->groupId;
        $groupActivity->key_name = 'group_joined';
        $groupActivity->old_value = '';
        $groupActivity->new_value = 'Group Joined - BRFQ '. $rfqId;
        $groupActivity->user_type = User::class;
        $groupActivity->save();
        // dd('here we go');
        //Update "reached_quantity" in `groups` table when user placed rfq and join group (28-03-2022 Ronak)
        if(isset($request->groupId) && isset($request->quantity)) {
            $group = Groups::find($request->groupId);
            $group->reached_quantity = $group->reached_quantity + $request->quantity;
            $group->save();
        }

        // $unit = Unit::find($request->unit);

        // $rfqmail = [
        //     'rfq' => $rfq,
        //     'rfqProduct' => $rfqProduct,
        //     'email' => $rfq->email,
        //     'unit' => $unit->name,
        //     // 'pdf' => public_path($path),
        // ];

        // try {
        //     //$ccUsers = ['patria.firmansyah@blitznet.co.id', 'juan.caldera@blitznet.co.id', 'rizko.siregar@blitznet.co.id', 'karina.mahardika@blitznet.co.id'];
        //     $ccUsers = \Config::get('static_arrays.bccusers');
        //     Mail::to($rfq->email)->bcc($ccUsers)->send(new SendRfqToBuyer($rfqmail));
        // } catch (\Exception $e) {
        //     //echo 'Error - ' . $e;
        // }

        // return $request;

        $rfqCount = DB::table('user_rfqs')
            ->join('rfqs', 'user_rfqs.rfq_id', '=', 'rfqs.id')
            ->join('rfq_products', 'rfqs.id', '=', 'rfq_products.rfq_id')
            ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
            ->where('user_rfqs.user_id', Auth::user()->id)
            ->where('rfqs.is_deleted', 0)
            ->count();


        dispatch(new RfqReceivedEmailBuyerJob($rfq->id));
        dispatch(new RfqReceivedEmailSupplierJob($rfq->id));

        return response()->json(array('success' => true, 'rfqCount' => $rfqCount));
    }
    /*
     * getAchieveGroupQty: get group details by groupId
     * Ronak Bhabhor - Jun 1 2022
     * */
    public function getAchieveGroupQty($id){
        $groups = Groups::where('id',$id)
            ->first(['groups.id', 'groups.reached_quantity', 'groups.achieved_quantity', 'groups.target_quantity']);
        return response()->json(array('success' => true, 'groups'=>$groups));

    }

    function cancelQuickRfq(Request $request){
        $rfq_id = Crypt::decrypt($request->rfqId);
        $commanData = [];
        Rfq::where(['id'=>$rfq_id])->update(['status_id'=>3]);
        $rfq = DB::table('rfqs')
            ->join('user_rfqs', 'rfqs.id', '=', 'user_rfqs.rfq_id')
            ->join('rfq_status', 'rfqs.status_id', '=', 'rfq_status.id')
            ->where('rfqs.id', $rfq_id)
            ->orderBy('rfqs.id', 'desc')
            ->first(['rfqs.id', 'rfqs.reference_number','rfq_status.name as status_name','user_rfqs.user_id as user_id' ]);


        $rfqStatus = $rfq->status_name;
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
       return response()->json(array('success' => true, 'rfqStatus' => $rfqStatus));
    }

    /*
     * Add/Remove RFQ as favourite
     * Vrutika - 17/11/2022
     * */

    function favouriteQuickRfq(Request $request){
        $favourite = $request->isFavouriteRfq ==0 ?1:0;
        $rfq_id = $request->rfqId;
        $rfq = Rfq::find($rfq_id);
        $rfq->is_favourite = $favourite;
        $rfq->save();
        if ($favourite == 0){
            $notificationTitle = 'buyer_remove_favourite';
            $rfqActivity = 'Removed Rfq from favourites';
        }else{
            $notificationTitle = 'buyer_add_favourite';
            $rfqActivity = 'Added Rfq to favourites';
        }
        //Buyer notification
        if((int)Auth::user()->role_id == 1){
            $commanData = array('rfq_number' => $rfq->reference_number, 'updated_by' => 'blitznet team', 'icons' => 'fa-user');
        }else{
            $commanData = array('rfq_number' => $rfq->reference_number, 'updated_by' => Auth::user()->full_name, 'icons' => 'fa-user');
        }
        buyerNotificationInsert($rfq->userRfqs[0]->user_id, $rfqActivity, $notificationTitle, 'rfq', $rfq->id, $commanData);

        //Admin notification
        $getAllAdmin = getAllAdmin();
            $sendAdminNotification = [];
            if (!empty($getAllAdmin)){
                foreach ($getAllAdmin as $key => $value){
                    $sendAdminNotification[] = array('user_id' => Auth::user()->id, 'admin_id' => $value, 'user_activity' => $rfqActivity, 'translation_key' => $notificationTitle, 'notification_type' => 'rfq', 'notification_type_id'=> $rfq->id);
                }
                Notification::insert($sendAdminNotification);
            }
        broadcast(new BuyerNotificationEvent());
        broadcast(new rfqsEvent());
       return response()->json(array('success' => true));
    }
}
