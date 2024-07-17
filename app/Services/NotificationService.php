<?php

namespace App\Services;

use App\Repositories\NotificationRepository;
use Auth;
use Carbon\Carbon;
use App\Events\OrderDeliverySeprate;
use App\Models\OrderActivity;

/**
 * Company class to handle operator interactions.
 */
class NotificationService
{

    protected $repository; 
    
    public function __construct()
    {
        $this->repository = new NotificationRepository;
    }

    // send notification order delivary sprate

    public function sendOrderDeliverySeprateNotification($order)
    {
        $authUserId = Auth::check()? Auth::id(): 1;
        $authUserRoleId = Auth::check()? Auth::user()->role_id : 1;
        $supplier_id = $order->supplier_id;
        $user_activity = 'Manage order delivery separately';
        $translation_key = 'manage_order_delivery_separately';
        $notification_type = 'order';
        $this->notificationCommon($authUserId,$authUserRoleId,$supplier_id,$user_activity,$translation_key,$notification_type,$order);
        OrderActivity::createOrUpdateOrderActivity(['order_id'=>$order->id,'user_id'=>$authUserId,'user_type'=>'1','key_name'=>'status', 'new_value' => $user_activity, 'old_value' => '1']);
        broadcast(new OrderDeliverySeprate());
    }
    public function getSeprateNotification(){
        $where = [];
        if (Auth::user()->role_id == 3){
            $supplier_id = $this->repository->getSupplierId();
            $where = ['supplier_id' => $supplier_id, 'admin_id' => 0];
        } else if(Auth::user()->role_id == Role::ADMIN || Auth::user()->role_id == Role::BUYER) {
            $where = ['admin_id' => Auth::user()->id, 'supplier_id' => 0];
        }
        $notifications = Notification::where($where)->orderBy('id', 'desc')->orderBy('notifications.created_at', 'desc')->get();
        $notificationDropDownView =  view('admin/notification/dropdownNotification', ['notifications' => $notifications->take(5)])->render();
        return response()->json(array('success' => true, 'notificationDropDownView' => $notificationDropDownView, 'counts' => $notifications->where('is_multiple_show', 0)->count()));
    }

    public function sendAirwayBillNotification($order,$batchId,$awb_number)
    {
        $authUserId = Auth::check()? Auth::id(): 1;
        $authUserRoleId = Auth::check()? Auth::user()->role_id : 1;
        $supplier_id = $order->supplier_id;
        $user_activity = 'Airway bill ganreted';
        $translation_key = 'airway_bill_ganrate';
        $notification_type = 'order';
        $comonData = json_encode(['old_key' =>$batchId, 'new_key' => $awb_number, 'order_item' => '', 'is_credit' => '']);
        $this->notificationCommon($authUserId,$authUserRoleId,$supplier_id,$user_activity,$translation_key,$notification_type,$order,$comonData);
        broadcast(new OrderDeliverySeprate());
    }
    public function notificationCommon($authUserId,$authUserRoleId,$supplier_id,$user_activity,$translation_key,$notification_type,$order,$comonData=null){
        if ($authUserRoleId == 1){
            $sendAdminNotification[] = array('user_id' => $authUserId, 'admin_id' => $authUserId, 'user_activity' => $user_activity, 'translation_key' => $translation_key, 'notification_type' => $notification_type, 'notification_type_id'=> $order->id, 'common_data' => $comonData, 'created_at' => Carbon::now());
            $sendSupplierNotification[] = array('user_id' => $authUserId, 'supplier_id' => $supplier_id, 'user_activity' => $user_activity, 'translation_key' => $translation_key, 'notification_type' => $notification_type, 'notification_type_id'=> $order->id, 'common_data' => $comonData, 'created_at' => Carbon::now());
        }
        else
        {
            if ($authUserRoleId == 3) {
                $supplier_id = $this->repository->getSupplierId();
            }
            $getAllAdmin = getAllAdmin();
            $sendAdminNotification = [];
            if (!empty($getAllAdmin)){
                foreach ($getAllAdmin as $key => $value){
                    $sendAdminNotification[] = array('user_id' => $authUserId, 'admin_id' => $value, 'user_activity' => $user_activity, 'translation_key' => $translation_key, 'notification_type' => $notification_type, 'notification_type_id' => $order->id, 'common_data' => $comonData, 'created_at' => Carbon::now());
                }
            }
            $sendSupplierNotification = array('user_id' => $authUserId, 'supplier_id' => $supplier_id, 'user_activity' => $user_activity, 'translation_key' => $translation_key, 'notification_type' => $notification_type, 'notification_type_id'=> $order->id, 'common_data' => $comonData, 'created_at' => Carbon::now());
        }
        $this->repository->sendOrderDeliverySeprateNotification($sendAdminNotification,$sendSupplierNotification);
    }
}