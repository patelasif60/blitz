<?php

namespace App\Jobs;

use App\Models\Groups;
use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RealtimeQuoteUpdateForGroupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $group;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($groupId)
    {
        $this->group = Groups::where('id',$groupId)->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $quotes = $this->group->quotes()->where('status_id',1)->get();
        foreach ($quotes as $quote){
            if ($quote->status_id!=1){//if status is not "Quotation Received"
                continue;
            }
            $groupFinalAmount = Quote::calculateGroupFinalAmount($quote->id,0);
            $quoteItem = $quote->quoteItems()->first(['product_quantity','product_price_per_unit']);
            $productRealPrice = $quoteItem->product_price_per_unit;
            $orderQty = $quoteItem->product_quantity;
            //get current group discount
            $groupDiscountAmount = Quote::getGroupDiscountAmount($this->group,$orderQty,$productRealPrice,0);
            $quoteData = (object)array_merge($groupFinalAmount,(array)$groupDiscountAmount);
            Quote::groupQuoteUpdateOnPayment($quote->id,$quoteData);
        }
    }
}
