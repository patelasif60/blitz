<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Console\Command;

class OrderDataToItem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orderDataToItem:cron';

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
        $orders = Order::all();
        $insert_order_item = [];
        foreach ($orders as $order){
            $quoteItem = $order->quote->quoteItem()->first(['supplier_id','product_id','product_amount', 'rfq_product_id']);
            $order->rfq_id = $order->quote()->value('rfq_id');
            $order->supplier_id = $quoteItem->supplier_id??null;
            $order->save();

            $insert_order_item[] = array(
                'order_id' => $order->id,
                'quote_item_id' => $order->quote_id,
                'order_item_number' => 'BORN-'.$order->id.'/101',
                'order_item_status_id' => $order->order_status??null,
                'product_amount' => $quoteItem->product_amount??0,
                'min_delivery_date' => $order->min_delivery_date,
                'max_delivery_date' => $order->max_delivery_date,
                'order_latter' => $order->order_latter,
                'product_id' => $quoteItem->product_id??null,
                'rfq_product_id' => $quoteItem->rfq_product_id??null,
            );
        }

        OrderItem::insert($insert_order_item);
        return 'Done';
    }
}
