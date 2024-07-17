<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Console\Command;

class ChangeOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changeOrderStatus:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $all_order = Order::where('order_status', '>=', 4)->where('order_status', '<=', 13)->get();
        foreach ($all_order as $key => $value){
            Order::where('id', $value->id)->update(['order_status' => 4]);
        }
        Order::where('order_status', 19)->update(['order_status' => 10]);
        Order::where('order_status', 18)->update(['order_status' => 9]);
        Order::where('order_status', 17)->update(['order_status' => 8]);
        Order::where('order_status', 16)->update(['order_status' => 7]);
        Order::where('order_status', 15)->update(['order_status' => 6]);
        Order::where('order_status', 14)->update(['order_status' => 5]);

        $get_all_order_item = OrderItem::all();
        foreach ($get_all_order_item as $key => $value){
            $order_status = null;
            if ($value->order_item_status_id == 4){
                $order_status = 1;
            } else if ($value->order_item_status_id == 5){
                $order_status = 2;
            } else if ($value->order_item_status_id == 6){
                $order_status = 3;
            } else if ($value->order_item_status_id == 7){
                $order_status = 4;
            } else if ($value->order_item_status_id == 8){
                $order_status = 5;
            } else if ($value->order_item_status_id == 9){
                $order_status = 6;
            } else if ($value->order_item_status_id == 10){
                $order_status = 7;
            } else if ($value->order_item_status_id == 11){
                $order_status = 8;
            } else if ($value->order_item_status_id == 12){
                $order_status = 9;
            } else if ($value->order_item_status_id == 13){
                $order_status = 10;
            } else if ($value->order_item_status_id < 4 || $value->order_item_status_id == 19) {
                $order_status = null;
            } else if ($value->order_item_status_id > 13) {
                $order_status = 10;
            }
            OrderItem::where('id', $value->id)->update(['order_item_status_id' => $order_status]);
        }
        return 1;
    }
}
