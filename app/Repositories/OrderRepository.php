<?php

namespace App\Repositories;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderStatus;
use App\Models\OrderTransactions;
use App\Models\GroupMembersDiscount;
use Auth;
use DB;
use App\Models\OrderTrack;
use App\Models\BuyerNotification;
use App\Models\BulkOrderPayments;
use App\Models\OrderItem;
use App\Models\OrderItemStatus;
use App\Models\Settings;

class OrderRepository extends BaseRepository
{
    public function getRfqList($perPage,$page,$favorder,$searchedData,$status)
    {
        $result = Settings::where(['key'=>'system_date_time_format','status'=>1,'is_deleted'=>0])->first(['value']);
        $orders = Order::with(['rfq','rfqProduct','quote','quote.quoteChargesWithAmounts','quote.quoteItems','quote.quoteItems.orderItem.orderItemStatus','quote.quoteItems.orderItem.orderItemStatus','quote.quoteItems.rfqProduct','quote.getUser','Company','User','Supplier','orderPo','orderStatus','orderTransactions','orderItems','orderItems.orderAirwayBillNumber', 'bulkOrderPayments','OrderTrack','orderItemTracks','City','State','orderItems.quoteItem','orderItems.quoteItem.rfqProduct','bulkOrderPayments.bulkPayment']);
        $authUser = Auth::user();
        $isOwner = User::checkCompanyOwner();
        $default_company = $authUser->default_company;
        if ($authUser->hasPermissionTo('list-all buyer orders') || $isOwner == true) {
            $orders =  $orders->whereHas("rfq",function($q) use($default_company){$q->where("company_id",$default_company);});
        }else {
            $orders = $orders->where('orders.user_id', $authUser->id)->whereHas("rfq",function($q) use($default_company){$q->where("company_id",$default_company);});
        }

         /** Search for Order status  */
        if (!empty($status) && trim($status) != 'all' ) {
            $orders = $orders->where('orders.order_status','=',$status);
        }
        /**
         * Custom search for order
         */
        if (!empty($searchedData)) {
             $orders = $orders->Where(function($q) use($searchedData){
                $q->where(function($query) use($searchedData){
                    $query->where('orders.id','LIKE','%'.$searchedData.'%');
                });
                $q->orWhereHas('rfqProduct', function($orders) use($searchedData){
                    $orders->where('product', 'LIKE',"%$searchedData%");
                });
                $q->orWhereHas('rfqProduct', function($orders) use($searchedData){
                    $orders->where('category', 'LIKE',"%$searchedData%");
                });
                $q->orWhereHas('rfqProduct', function($orders) use($searchedData){
                    $orders->where('sub_category', 'LIKE',"%$searchedData%");
                });
            });
        }
        /***end search***/

        $orders = $orders->where('orders.is_deleted', 0)->orderBy('orders.id', 'desc');
        $total = clone $orders->groupBy('orders.id');
        $totalRecord = count($total->get()) ;

        $orders = $orders->groupBy('orders.id')
            ->paginate($perPage, ['orders.*','orders.user_id as assigned_to'], null, $page);

        $orders->totalRecord = $totalRecord;
        if(count($orders)==10)
        {
           $orders->currentRecord = $perPage*$page;
        }
        else{
           $orders->currentRecord = $perPage* ($page - 1) + count($orders);
        }

        $orderItemStatus = OrderItemStatus::all(['order_item_status.id as order_item_status_id', 'order_item_status.name as status_name']);
        $orderStatus = OrderStatus::all(['order_status.id as order_status_id','order_status.name as status_name','order_status.show_order_id', 'order_status.credit_sorting']);
        $isOwner = User::checkCompanyOwner();
        $setting = getSettingValueByKey('system_date_time_format');
        $buyerOrderCountWhere = ['user_id' => $authUser->id, 'user_activity' => 'Order Placed', 'notification_type' => 'order', 'side_count_show' => 0];
        BuyerNotification::where($buyerOrderCountWhere)->update(['side_count_show' => 1]);
        return $this->getOrderWisedata($orders,$orderItemStatus,$orderStatus,$isOwner,$setting,$result,$authUser);
        
    }
    public function getOrderWisedata($orders,$orderItemStatus,$orderStatus,$isOwner,$setting,$result,$authUser)
    {
        foreach ($orders as $order) {
            $order->rfq_mobile = $order->rfq->mobile;
            $order->phone_code = $order->rfq->phone_code;
            $order->order_status_name = $order->orderStatus->name;
            $order->quote_number =  $order->quote->quote_number;
            $order->valid_till =  $order->quote->valid_till;
            $order->product_final_amount =  $order->quote->final_amount;
            $order->tax =  $order->quote->tax;
            $order->tax_value =  $order->quote->tax_value;
            $order->company_name =  $order->Company->name;
            $order->product_quantity =  $order->rfqProduct->first()->quantity;
            $order->firstname =  $order->User->firstname;
            $order->lastname =  $order->User->lastname;
            $order->supplier_name =  $order->Supplier->contact_person_name;
            $order->supplier_company_name =  $order->Supplier->name;
            $order->supplier_profile_username =  $order->Supplier->supplier_profile_username;
            $order->po_number =  $order->orderPo ? $order->orderPo->po_number : '';
            $order->inv_number = $order->orderPo ? $order->orderPo->inv_number : '';
            $order->product_name =  $order->rfqProduct->first()->product;
            $order->product_description =  $order->rfqProduct->first()->product_description;
            $order->sub_category_name =  $order->rfqProduct->first()->sub_category;
            $order->category_name =  $order->rfqProduct->first()->category;
            $transactions =$order->orderTransactions->sortByDesc('id')->first();
            $order->full_quote_by = $order->quote->getUser->role_id ?? 0;
            $order->bulk_discount = (float)0;
            $quotes_charges_with_amounts = $order->quote->quoteChargesWithAmounts;
            $order->quotes_charges_with_amounts = $quotes_charges_with_amounts;
            $order->closeflag = $order->orderItems->first()->order_item_status_id ?? 0;
            $order->orderItemCategory = $order->orderItems->first()->quoteItem->rfqProduct->category;
            $order->orderItemCategoryId = $order->orderItems->first()->quoteItem->rfqProduct->category_id;
            $order->orderlogisticProvided =  $order->orderItems->first()->quoteItem;
            $order->orderAirwayBill = $order->orderItems->first()->orderAirwayBillNumber;
            $order->orderTracksIds = $order->OrderTrack->pluck('id','status_id')->toArray();
            $order->orderTracksdate = $order->OrderTrack->pluck('created_at','status_id')->toArray();
            $order->orderItemTracksId = $order->orderItemTracks->pluck('status_id','order_item_id')->toArray();
            $order->orderItemTracksdate = $order->orderItemTracks->pluck('created_at','order_item_id')->toArray();
            $order->orderItemTracksStatus = $order->orderItemTracks->whereIn('status_id', [6, 7, 8])->pluck('status_id','order_item_id')->toArray();
            $order->orderItemStatus =$orderItemStatus;
            $order->orderDateSetting = $result->value;
            $order->request_days_status = $order->orderCreditDay ? $order->orderCreditDay->status : 0;
            $order->assigned_to = $order->User->full_name;
            $order->city = $order->city_id > 0 ? $order->City->name : $order->city;
            $order->state = $order->state_id > 0 ? $order->State->name : $order->state;
            $order->quoteItems = $order->quote->quoteItems;
            if($order->bulkOrderPayments->first())
            {
                $order->bulk_discount = '';
                if($order->bulkOrderPayments->first()->bulkPayment){
                    $order->bulk_discount = $order->bulkOrderPayments->first()->bulkPayment->orderTransaction->status == 'PAID' ? (float)$order->bulkOrderPayments->first()->discounted_amount : '';
                }            
            }
            $order->orderTracks = $order->OrderTrack->pluck('status_id');
            $order->invoice_status = '';
            $order->invoice_url = '';
            $order->expiry_date = '';
            if($transactions)
            {
                $transactions = $transactions->toArray();
            }
            else{
                $transactions = [];
            }
            $order->invoice_status = $transactions['status']??'';
            $order->invoice_url = $transactions['invoice_url']??'';
            $order->expiry_date = $transactions['expiry_date']??'';
            if ($order->payment_type == 1 || $order->payment_type == 2) {
                if($order->is_credit == ORDER_IS_CREDIT['LOAN_PROVIDER_CREDIT']){
                    $whereNotIn = [8];
                    if (!empty($order->orderTracks->contains(9))){//if credit approved
                        $whereNotIn = array_merge($whereNotIn,[10]);
                    }
                    $orderStatus = $orderStatus->whereNotIn('order_status_id',$whereNotIn);
                }
                $order->orderAllStatus = $orderStatus->sortBy('credit_sorting')->values()->all();
            }
            else{
                $order->orderAllStatus = $orderStatus->sortBy('show_order_id')->take(10)->values();
            }
            //fetch group member discounts
            if ($order->group_id){
                $order->group_members_discount = GroupMembersDiscount::where(['group_id'=>$order->group_id,'order_id'=>$order->id])->first();
                $order->product_total_amount = $order->orderItems->pluck('product_amount')->first();
            }
            /*********begin:Payment set permissions based on custom role.**************/
            if ($authUser->hasPermissionTo('list-all buyer payments') || $isOwner == true) { // check list all and owner Admin condition
                $payNowPermission = 1; // set flag to 1 for Pay NOW option
            }else {
                if ($authUser->id == $order->user_id && $authUser->hasPermissionTo('create buyer payments')) { // user id and order user id  is same then display Pay Now
                    $payNowPermission = 1;  // set flag to 1 for Pay NOW option
                }else{
                    $payNowPermission = 0; // set flag For not display Pay NOW option
                }
            }
            /*********end: Payment set permissions based on custom role.**************/
            $order->payNowPermission = $payNowPermission;  // create new properties for check Pay NOW option
            $order->setting =$setting; 
        }
        return $orders;
    }

}