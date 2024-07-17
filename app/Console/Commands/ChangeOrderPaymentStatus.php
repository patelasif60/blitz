<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderTransactions;
use Illuminate\Console\Command;

class ChangeOrderPaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ChangeOrderPaymentStatus';

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
        $orders = Order::where(function($query) {
                                        return $query->where(function($query) {
                                            return $query->where('is_credit',0)->where('order_status','>=',3)->where('order_status','!=',10);
                                        })->orWhere(function($query) {
                                            return $query->where('is_credit',1)->whereIn('order_status',[3,5,6,7]);
                                        });
                            })->get();
        foreach ($orders as $order){
            if($order->orderTransaction()->where('status','PAID')->value('id')){
                $order->payment_status = 1;//online paid
                $order->save();
            }else{
                $order->payment_status = 2;//offline paid
                $order->save();
            }
        }
        $orderTransactions = OrderTransactions::where(['order_id'=>null,'status'=>'PAID'])->get();
        foreach ($orderTransactions as $orderTransaction){
            $bulkPayment = $orderTransaction->bulkPayment()->withTrashed()->first();
            foreach ($bulkPayment->bulkOrderPayments()->get() as $bulkOrderPayments){
                $order = $bulkOrderPayments->order()->first();
                $order->payment_status = 1;//online paid
                $order->save();
            }
        }
        return 'done';
    }
}
