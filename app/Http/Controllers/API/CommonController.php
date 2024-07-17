<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AirWayBillNumber;
use App\Models\Order;
use App\Models\OrderActivity;
use App\Models\OrderItem;
use App\Models\OrderItemStatus;
use App\Models\OrderItemTracks;
use App\Models\QuincusOrderTracking;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
    /* Update Order Status */
    public function updateOrderStatusQuincus(Request $request) {
        //get order id and status in Quincus example
        /*$quincus_data = [
          ['id' => 2, 'status' => 5], ['id' => 22, 'status' => 5], ['id' => 25, 'status' => 5], ['id' => 27, 'status' => 5],
        ];*/

        $orderBatch = AirWayBillNumber::select('order_batch_id')->where('airwaybill_number',$request->airwaybill_number)->first();
        if(isset($orderBatch)) {
            /*$order_id = str_replace('BORN-', '', $order->order_id);*/

            $header = $request->header('token');
            $authUserId = Auth::check()? Auth::id(): 1;
            $orderTrackUserType = User::class;
            if(isset($header)) {
                if ($header != 'Hs4jzy8pbUTJqw208APk3DCyByQop8bWmxFdjgS50TuAuoKjlSBtp7QVGDhnkDRw'){
                    return response()->json(array('success' => false, 'message' => 'Invalid Token'));
                }

                $order = OrderItem::where('order_batch_id',$orderBatch->order_batch_id)->pluck('id');
                $orderItems = OrderItem::where('order_batch_id',$orderBatch->order_batch_id)->get();
                if (!$order){
                    return response()->json(array('success' => false, 'message' => 'Order ID Not Found.'));
                }
                if (in_array($request->status_id, [3,4,5,6])){
                    //old values - 6,7,8,9,12    //New values - 3,4,5,6
                    //3 - Order pickedup, 4 - In transit, 5 - Out for delivery, 6 - Under QC, 10 - Delivered
                    if(($request->status_id >= 3) && empty($orderItems->first()->order_latter)) {
                        return response()->json(array('success' => true, 'message' => 'Please Contact to Blitznet Team and Upload Order Letter First!'));
                    }
                    OrderItem::whereIn('id', $order)->update(['order_item_status_id' => $request->status_id]);
                    $orderItemStatusFirst = OrderItemStatus::where('id', $request->status_id)->first();
                    foreach ($orderItems as $orderItemStatus){
                        $lastOrderItemStatus = $orderItemStatus->order_item_status_id;
                        if ($lastOrderItemStatus > $request->status_id){
                            $orterTrackExist = OrderItemTracks::where('order_id',$orderItemStatus->order_id)->where('order_item_id',$orderItemStatus->id)->get();
                            if (count($orterTrackExist) > 0){
                                OrderItemTracks::where('order_id',$orderItemStatus->order_id)->where('order_item_id',$orderItemStatus->id)->where('status_id','>=',$request->status_id)->delete();
                            }
                        }
                        $this->addAllMissingItemStatus($orderItemStatus->order_id, $orderItemStatus->id, $orderItemStatusFirst, $authUserId);
                        OrderItemTracks::createOrUpdateOrderItemTrack(['order_id'=>$orderItemStatus->order_id,'order_item_id'=>$orderItemStatus->id,'status_id'=>$request->status_id,'user_id'=>$authUserId]);
                        OrderActivity::createOrUpdateOrderActivity(['order_id'=>$orderItemStatus->order_id,'order_item_id'=>$orderItemStatus->id,'user_id'=>$authUserId,'user_type'=>$orderTrackUserType,'key_name'=>'order_item_status', 'new_value' => $request->status_id, 'old_value' => $lastOrderItemStatus]);
                    }
                   return response()->json(array('success' => true, 'message' => 'Order Status updated Successfully.'));
                } else {
                    return response()->json(array('success' => false, 'message' => 'Not Valid Status ID.'));
                }
            }  else {
                return response()->json(array('success' => false, 'message' => 'Token Is Missing'));
            }
        } else {
            return response()->json(array('success' => false, 'message' => 'Invalid AirWayBill Number'));
        }
    }

    //add All Missing item Status
    public function addAllMissingItemStatus($orderId, $orderItemId, $currentOrderStatus, $orderTrackUserId)
    {
        $orderTracks = OrderItemTracks::with('orderItemStatus:id,name,sort,description')->where('order_item_id',$orderItemId)->get();
        $sort = 0;
        if (count($orderTracks) == $currentOrderStatus->sort){
            $sort = $orderTracks[0]->sort;
        }
        //dd(count($orderTracks));
        $order_returned = [8];//QC Passed
        for ($i = $sort + 1; $i < $currentOrderStatus->sort; $i++) {

            $orderStatus = OrderItemStatus::where('sort', $i)->first();
            //'Order Troubleshooting' && $order_returned
            if (($currentOrderStatus->id == 9) && in_array($orderStatus->id,$order_returned)){
                continue;
            }
            //'QC Failed' && Order Troubleshooting
            if ($orderStatus->id != 7 && $orderStatus->id != 9) {
                OrderItemTracks::createOrUpdateOrderItemTrack(['order_id'=>$orderId,'order_item_id'=>$orderItemId,'status_id'=>$orderStatus->id,'user_id'=>$orderTrackUserId,'user_type'=>User::class]);
            }
        }
    }

    /* Quincus shipment tracking api */
    public function QuincusShipmentTracking(Request $request) {
        $header = $request->header('token');

        if(isset($header)) {
            if ($header != 'Hs4jzy8pbUTJqw208APk3DCyByQop8bWmxFdjgS50TuAuoKjlSBtp7QVGDhnkDRw') {
                return response()->json(array('success' => false, 'message' => 'Invalid Token'));
            }

            $AWB = AirWayBillNumber::select('airwaybill_number')->where('airwaybill_number',$request->airwaybill_number)->first();
            if(!isset($AWB->airwaybill_number)) {
                return response()->json(array('success' => false, 'message' => "AirWayBill number is invalid"));
            }

            $QuincusOrderTracking = new QuincusOrderTracking();
            //Check airwaybill_number should not be null
            if($request->airwaybill_number == null) {
                return response()->json(array('success' => false, 'message' => "AirWayBill number should not be null"));
            }

            //Check process_status should not be null
            if($request->process_status == null) {
                return response()->json(array('success' => false, 'message' => "Process status should not be null"));
            }

            //Check quincus_status_stage should not be null
            if($request->quincus_status_stage == null) {
                return response()->json(array('success' => false, 'message' => "Status stage should not be null"));
            }

            //Check process_datetime should not be null
            if($request->process_datetime == null) {
                return response()->json(array('success' => false, 'message' => "Status datetime should not be null"));
            }

            //Check process_location should not be null
            if($request->process_location == null) {
                return response()->json(array('success' => false, 'message' => "Location should not be null"));
            }

            $QuincusOrderTracking->airwaybill_number = $request->airwaybill_number;
            if(isset($request->blitznet_status_id) || $request->blitznet_status_id != null){
                if (in_array($request->blitznet_status_id, [3,4,5,6])) {
                    $QuincusOrderTracking->blitznet_status_id = $request->blitznet_status_id;
                } else {
                    return response()->json(array('success' => false, 'message' => 'Not Valid Status ID.'));
                }
            }
            $QuincusOrderTracking->quincus_status_code = $request->quincus_status_code;;
            $QuincusOrderTracking->process_status = $request->process_status;
            $QuincusOrderTracking->quincus_status_description = $request->quincus_status_description;
            $QuincusOrderTracking->quincus_status_stage = $request->quincus_status_stage;
            $QuincusOrderTracking->process_datetime = changeDateTimeFormat($request->process_datetime,'Y-m-d H:i:s');
            $QuincusOrderTracking->process_location = $request->process_location;
            $QuincusOrderTracking->process_signature = $request->process_signature;
            $QuincusOrderTracking->process_photo = $request->process_photo;
            $QuincusOrderTracking->process_latitude = $request->process_latitude;
            $QuincusOrderTracking->process_longitude = $request->process_longitude;
            $QuincusOrderTracking->process_maps_location = $request->process_maps_location;
            $QuincusOrderTracking->process_received_by = $request->process_received_by;
            $QuincusOrderTracking->process_received_relation = $request->process_received_relation;
            $QuincusOrderTracking->save();
            if($QuincusOrderTracking == true) {
                return response()->json(array('success' => true, 'message' => 'Order status inserted successfully.'));
            }
        } else {
            return response()->json(array('success' => false, 'message' => 'Token Is Missing'));
        }
    }
}
