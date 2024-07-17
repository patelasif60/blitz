<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\OrderItemStatus;
use App\Models\OrderStatus;
use App\Models\Rfq;
use App\Models\Role;
use App\Models\UserSupplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class NotificationController extends Controller
{
     /**
    define $service variable;
    */

    protected $service;

    public function __construct()
    {
        $this->middleware('permission:create notifications|edit notifications|delete notifications|publish notifications|unpublish notifications', ['only' => ['list']]);
        $this->middleware('permission:create notifications', ['only' => ['create']]);
        $this->middleware('permission:edit notifications', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete notifications', ['only' => ['destroy']]);
        $this->service = new NotificationService;
    }

    public function list(){
        $commanData = $this->notificationCommon();
        $notifications = $commanData['notifications']->groupBy(function ($val) { return Carbon::parse($val->created_at)->format('d-m-Y'); });
        $orderStatus = $commanData['orderStatus'];
        $orderItemStatus = $commanData['orderItemStatus'];

        /**begin: system log**/
        Notification::bootSystemView(new Notification());
        /**end:  system log**/
        return view('admin/notification/index', ['notifications' => $notifications, 'orderStatus' => $orderStatus, 'OrderStatusItems' => $orderItemStatus ]);
    }
     public function getNotificationFilterData(Request $request){
         $commanData = $this->notificationCommon($request->all());
         $notifications = $commanData['notifications']->groupBy(function ($val) { return Carbon::parse($val->created_at)->format('d-m-Y'); });;
         $orderStatus = $commanData['orderStatus'];
         $orderItemStatus = $commanData['orderItemStatus'];
        $notificationFilterDataView =  view('admin/notification/notificationFilterData', ['notifications' => $notifications, 'orderStatus' => $orderStatus, 'OrderStatusItems' => $orderItemStatus])->render();

        return response()->json(array('success' => true, 'notificationFilterDataView' => $notificationFilterDataView));

    }
    public function getSeprateNotification(){
        $this->service->getSeprateNotification();
    }
    public function getAirwayNotification(){
        $this->service->getAirwayNotification();
    }
    public function getNotificationCount(){
        $commanData = $this->notificationCommon();
        $notifications = $commanData['notifications'];
        $orderStatus = $commanData['orderStatus'];
        $orderItemStatus = $commanData['orderItemStatus'];
        $notificationDropDownView =  view('admin/notification/dropdownNotification', ['notifications' => $notifications->take(5), 'orderStatus' => $orderStatus, 'OrderStatusItems' => $orderItemStatus])->render();
        return response()->json(array('success' => true, 'notificationDropDownView' => $notificationDropDownView, 'counts' => $notifications->where('is_multiple_show', 0)->count()));
    }

    public function removeNotificationCount(){
        $where = $this->checkSupplierOrUser();
        $notifications = Notification::where($where)->update(['is_multiple_show' => 1]);
        return response()->json(array('success' => true));
    }

    public function notificationCommon($data = ''){
        $where = $this->checkSupplierOrUser();
        if(isset($data['notificationCategory']) && $data['notificationCategory'] != ''){
            $where = array_merge($where, ['notification_type' => $data['notificationCategory']]);
        }
        $notifications = Notification::join('users', 'notifications.user_id', '=', 'users.id')->where($where)->orderBy('id', 'desc')->orderBy('notifications.created_at', 'desc')->get(['notifications.*', 'users.firstname','users.lastname', 'users.role_id']);
        $orderStatus = OrderStatus::all();
        $orderItemStatus = OrderItemStatus::all();

        return ['notifications' => $notifications, 'orderStatus' => $orderStatus, 'orderItemStatus' => $orderItemStatus];
    }

    public function checkSupplierOrUser(){
        $where = [];
        if (Auth::user()->role_id == 3){
            $supplier_id = UserSupplier::where('user_id', auth()->user()->id)->pluck('supplier_id')->first();;
            $where = ['supplier_id' => $supplier_id, 'admin_id' => 0];
        } else if(Auth::user()->role_id == Role::ADMIN || Auth::user()->role_id == Role::BUYER) {
            $where = ['admin_id' => Auth::user()->id, 'supplier_id' => 0];
        }
        return $where;
    }

    public function getSideCount($text){
        if ($text == 'all'){
            $rfqCount = $this->rfqCount();
            $quoteCount = $this->quoteCount();
            $orderCount = $this->orderCount();
            return response()->json(array('success' => true, 'rfqs' => $rfqCount, 'quotes' => $quoteCount, 'orders' => $orderCount));
        }

        if ($text == 'rfqs_count'){
            $rfqCount = $this->rfqCount();
            return response()->json(array('success' => true, 'rfqs' => $rfqCount));
        }
        if ($text == 'quotes_count'){
            $quoteCount = $this->quoteCount();
            return response()->json(array('success' => true, 'quotes' => $quoteCount));
        }
        if ($text == 'orders_count'){
            $orderCount = $this->orderCount();
            return response()->json(array('success' => true, 'orders' => $orderCount));
        }
    }

    public function rfqCount(){
        $where = $this->checkSupplierOrUser();
        $where = array_merge($where, ['user_activity' => 'Generate RFQ', 'notification_type' => 'rfq', 'side_count_show' => 0]);
        $rfqs = Notification::where($where)->get()->count();
        $counts = $this->changeCount($rfqs);
        return $counts;
    }

    public function quoteCount(){
        $where = $this->checkSupplierOrUser();
        $where = array_merge($where, ['user_activity' => 'Create Quote', 'notification_type' => 'quote', 'side_count_show' => 0]);
        $quotes = Notification::where($where)->get()->count();
        $counts = $this->changeCount($quotes);
        return $counts;
    }

    public function orderCount(){
        $where = $this->checkSupplierOrUser();
        $where = array_merge($where, ['user_activity' => 'Place Order', 'notification_type' => 'order', 'side_count_show' => 0]);
        $orders = Notification::where($where)->get()->count();
        $counts = $this->changeCount($orders);
        return $counts;
    }

    public function changeCount($count){
        if ($count > 9){
            return '9+';
        } else {
            return $count;
        }
    }

    /**
     * addLoanNotification: add loan notification
     */
    public function addLoanNotification($data)
    {
        $getAllAdmin = getAllAdmin();
        $sendAdminNotification = [];
        if (!empty($getAllAdmin)){
            foreach ($getAllAdmin as $key => $value){
                $sendAdminNotification[] = [ 
                    'user_id' => 1, 'admin_id' => $value, 'user_activity' => $data['user_activity'], 
                    'translation_key' => $data['translation_key'], 'notification_type' => 'loan', 'notification_type_id'=> $data['type_id'], 
                    'common_data' => json_encode([
                        'loan_number' => $data['loan_number'], 'status' => $data['status'], 
                        'updated_by' => "Blitznet Team", 'icons' => 'fa-gear'
                    ]),
                    'created_at' => Carbon::now()
                ];
            }
            Notification::insert($sendAdminNotification);
        }
        return true;
    }

    /**
     * addLimitNotification: add limit notification
     */
    public function addLimitNotification($data)
    {
        $getAllAdmin = getAllAdmin();
        $sendAdminNotification = [];
        if (!empty($getAllAdmin)){
            foreach ($getAllAdmin as $key => $value){
                $sendAdminNotification[] = [ 
                    'user_id' => $data['user_id'], 'admin_id' => $value, 'user_activity' => $data['user_activity'], 
                    'translation_key' => $data['translation_key'], 'notification_type' => 'limit', 'notification_type_id'=> $data['type_id'], 
                    'common_data' => json_encode([
                        'limit_number' => $data['limit_number'], 'status' => $data['status'], 
                        'updated_by' => $data['updated_by'], 'icons' => 'fa-gear'
                    ]),
                    'created_at' => Carbon::now()
                ];
            }
            Notification::insert($sendAdminNotification);
        }
        return true;
    }
}
