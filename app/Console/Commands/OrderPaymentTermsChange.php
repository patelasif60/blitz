<?php

namespace App\Console\Commands;
use App\Models\Order;
use App\Models\Quote;
use App\Models\Rfq;

use Illuminate\Console\Command;

class OrderPaymentTermsChange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paymentstatus:change';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change paymant status of exiting orders due to LC/SKBAN';

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
        foreach ($orders as $order){
            echo $order->id.'/n';
            if($order->is_credit == 0){
                $order->payment_type = 0;
            }
            elseif($order->is_credit == 1){
                $order->credit_days  = $order->orderCreditDay->approved_days;
                $order->payment_type = 1;   
            }
            elseif($order->is_credit == 2){
               // $order->credit_days  = $order->orderCreditDay->approved_days;
                $order->payment_type = 2;   
            }
            $order->save();
        }
        echo '****-----------------------------------------------';
        $rfqs = Rfq::all();
        foreach ($rfqs as $rfq){
            echo $rfq->id.'/n';
            if($rfq->is_require_credit == 0){
                $rfq->payment_type = 0;
            }
            elseif($rfq->is_require_credit == 1){
                $rfq->credit_days  = 14;
                if(count($rfq->order)>0){
                    $order = $rfq->order()->first();
                    if($order->orderCreditDay){
                        $rfq->credit_days  = $order->orderCreditDay->approved_days;
                    }
                }
                $rfq->payment_type = 1;   
            }
            $rfq->save();
        }
        echo '********************************************************************';
       //$quote = Quote::find(50);
       //dd($quote->rfqs->credit_days);
        $quotes = Quote::all();
        foreach ($quotes as $quote){
            echo $quote->id.'/n';
            if($quote->rfqs){
                if($quote->rfqs->is_require_credit == 0){
                    $quote->payment_type = 0;
                }
                elseif($quote->rfqs->is_require_credit == 1){
                    $abc= $quote->rfqs->credit_days;
                    $quote->credit_days  = $abc;
                    $quote->payment_type = 1;
                }
            }
            $quote->save();
        }
    }
}
