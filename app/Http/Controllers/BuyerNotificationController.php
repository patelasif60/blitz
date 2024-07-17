<?php

namespace App\Http\Controllers;

use App\Models\BuyerNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerNotificationController extends Controller
{
    public function index(){
        $userNotification = BuyerNotification::where(['user_id' => Auth::user()->id])->get()->sortByDesc("created_at");
        return view('dashboard/notification/index', ['userNotification' => ChangeCount($userNotification->where('is_multiple_show', 0)->count()), 'allnotification' => $userNotification->groupBy(function ($val) { return Carbon::parse($val->created_at)->format('d-m-Y'); })->sortByDesc("created_at")]);
    }

    public function buyerSideCountNotification($sideCountName){
        if ($sideCountName == 'All'){
            $rfqCount = $this->rfqCount();
            $quoteCount = $this->quoteCount();
            $orderCount = $this->orderCount();
            return response()->json(array('success' => true, 'rfqs' => $rfqCount, 'quotes' => $quoteCount, 'orders' => $orderCount));
        }

        if ($sideCountName == 'rfqs_count'){
            $rfqCount = $this->rfqCount();
            return response()->json(array('success' => true, 'rfqs' => $rfqCount));
        }
        if ($sideCountName == 'quotes_count'){
            $quoteCount = $this->quoteCount();
            return response()->json(array('success' => true, 'quotes' => $quoteCount));
        }
        if ($sideCountName == 'orders_count'){
            $orderCount = $this->orderCount();
            return response()->json(array('success' => true, 'orders' => $orderCount));
        }
    }
    public function rfqCount(){
        $where = ['user_id' => Auth::user()->id, 'user_activity' => 'RFQ Created', 'notification_type' => 'rfq', 'side_count_show' => 0];
        $rfqs = BuyerNotification::where($where)->get()->count();
        $counts = ChangeCount($rfqs);
        return $counts;
    }

    public function quoteCount(){
        $where = ['user_id' => Auth::user()->id, 'user_activity' => 'Create Quote', 'notification_type' => 'quote', 'side_count_show' => 0];
        $quotes = BuyerNotification::where($where)->get()->count();
        $counts = ChangeCount($quotes);
        return $counts;
    }

    public function orderCount(){
        $where = ['user_id' => Auth::user()->id ,'user_activity' => 'Place Order', 'notification_type' => 'order', 'side_count_show' => 0];
        $orders = BuyerNotification::where($where)->get()->count();
        $counts = ChangeCount($orders);
        return $counts;
    }

    public function markAsAll(){
        BuyerNotification::where('user_id', Auth::user()->id)->update(['is_show' => 1]);
        //$userActivity = UserActivity::all()->where('user_id', Auth::user()->id)->where('is_deleted', 0)->sortByDesc('id');
        $userActivity = BuyerNotification::join('users', 'buyer_notifications.user_id', '=', 'users.id')->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get(['buyer_notifications.*', 'users.firstname','users.lastname']);
        $userActivityHtml = view('dashboard/activity', ['userActivity' => $userActivity])->render();
        return response()->json(array('success' => true, 'userActivityHtml' => $userActivityHtml));
    }

    public function getBuyerNotificationFilterData(Request $request){
        $where = ['user_id' => Auth::user()->id];
        if(isset($request->notificationCategory) && $request->notificationCategory != ''){
            $where = array_merge($where, ['notification_type' => $request->notificationCategory]);
        }
        $userNotification = BuyerNotification::where($where)->get()->sortByDesc("created_at");
        $notificationFilterDataView =  view('dashboard/notification/notificationBuyerFilterData', ['allnotification' => $userNotification->groupBy(function ($val) { return Carbon::parse($val->created_at)->format('d-m-Y'); })->sortByDesc("created_at")])->render();

        return response()->json(array('success' => true, 'notificationFilterDataView' => $notificationFilterDataView));
    }

    //@vrutika for buyer side notification indicator
    public function buyerSideIndicatorNotification($sideCountName){
        if ($sideCountName == 'All'){
            $rfqCount = $this->rfqIndicator();
            $quoteCount = $this->quoteIndicator();
            $rfqCount = $rfqCount + $quoteCount;
            $orderCount = $this->orderIndicator();
            //return response()->json(array('success' => true, 'rfqs' => $rfqCount, 'orders' => $orderCount));
        }

        if ($sideCountName == 'rfqs_count'){
            $rfqCount = $this->rfqIndicator();
            $quoteCount = $this->quoteIndicator();
            $rfqCount = $rfqCount + $quoteCount;
            //return response()->json(array('success' => true, 'rfqs' => $rfqCount??0));
        }
        if ($sideCountName == 'orders_count'){
            $orderCount = $this->orderIndicator();
            //return response()->json(array('success' => true, 'orders' => $orderCount??0));
        }
        return response()->json(array('success' => true, 'rfqs' => $rfqCount??0, 'orders' => $orderCount??0));
    }

    public function rfqIndicator(){
        $where = ['user_id' => Auth::user()->id, 'user_activity' => 'RFQ Created', 'notification_type' => 'rfq', 'side_count_show' => 0];
        $rfqs = BuyerNotification::where($where)->get()->count();
        return $rfqs;
    }
    public function quoteIndicator(){
        $where = ['user_id' => Auth::user()->id, 'user_activity' => 'Quote Create', 'notification_type' => 'quote', 'side_count_show' => 0];
        $quotes = BuyerNotification::where($where)->get()->count();
        return $quotes;
    }
    public function orderIndicator(){
        $where = ['user_id' => Auth::user()->id ,'user_activity' => 'Order Placed', 'notification_type' => 'order', 'side_count_show' => 0];
        $orders = BuyerNotification::where($where)->get()->count();
        return $orders;
    }

    public function buyerSingleMark($id){
        BuyerNotification::where('id', $id)->update(['is_show' => 1]);
        //$userActivity = UserActivity::all()->where('user_id', Auth::user()->id)->where('is_deleted', 0)->sortByDesc('id');
        $userActivity = BuyerNotification::join('users', 'buyer_notifications.user_id', '=', 'users.id')->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get(['buyer_notifications.*', 'users.firstname','users.lastname']);
        $userActivityHtml = view('dashboard/activity', ['userActivity' => $userActivity])->render();
        return response()->json(array('success' => true, 'userActivityHtml' => $userActivityHtml));
    }
}
