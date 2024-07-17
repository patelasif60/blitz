<?php

namespace App\Http\Controllers\Buyer\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\OrderStatus;

class OrdersLivewireController extends Controller
{
    //check parmission
    public function __construct()
    {
        $this->middleware('permission:publish buyer orders',['only' =>['orders']]);
    }
    public function orders(){
        $allOrderStatus = OrderStatus::orderBy('show_order_id',"ASC")->get()->whereNotIn('id',[6,9]);
        return View('buyer.orders.index',compact('allOrderStatus'));        
    }
}
