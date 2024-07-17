<?php
/*
 * this file created by munir
 * */
namespace App\Jobs;

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\DashboardController;
use App\Models\GroupPlaceOrderLog;
use App\Models\GroupTransactions;
use App\Models\Order;
use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreateGroupOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $groupTransaction;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($invoiceId)
    {
        $this->groupTransaction = GroupTransactions::where('invoice_id',$invoiceId)->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $groupPlaceOrderLog = GroupPlaceOrderLog::where(['group_id'=>$this->groupTransaction->group_id,'quote_id'=>$this->groupTransaction->quote_id])->pluck('request_data')->first();
        if (!empty($groupPlaceOrderLog)){
            $groupPlaceOrderLog = json_decode($groupPlaceOrderLog);
            $poComment = '';
            if (isset($groupPlaceOrderLog->comment)){
                $poComment = $groupPlaceOrderLog->comment;
                unset($groupPlaceOrderLog->comment);
            }
            $quoteData = $groupPlaceOrderLog->quote_data;
            unset($groupPlaceOrderLog->quote_data);
            //this will update quote if buyer get more discount
            Quote::groupQuoteUpdateOnPayment($groupPlaceOrderLog->quoteId,$quoteData);
            try {
                dispatch(new RealtimeQuoteUpdateForGroupJob($this->groupTransaction->group_id));
            } catch (\Exception $e) {}
            $authUserId = $groupPlaceOrderLog->user_id;
            $order = Order::where(['user_id'=>$authUserId,'quote_id'=>$groupPlaceOrderLog->quoteId])->first();
            if (empty($order)) {
                $dashController = new DashboardController;
                try {
                    $order = $dashController->setOrder($groupPlaceOrderLog, 1);
                } catch (\Exception $e) {}
                if (empty($order)) {
                    $order = Order::where(['user_id' => $authUserId, 'quote_id' => $groupPlaceOrderLog->quoteId])->first();
                }
                $this->groupTransaction->order_id = $order->id;
                $this->groupTransaction->save();
                $adminOrderObj = new OrderController;
                try {
                    $adminOrderObj->generatePO(['id'=>$order->id,'comment'=>$poComment,'user_id'=>$authUserId]);
                } catch (\Exception $e) {}
                try {
                    $adminOrderObj->setOrderStatusChange(3, $order->id,['group_id'=>$this->groupTransaction->group_id]);
                } catch (\Exception $e) {}
            }
        }
    }
}
