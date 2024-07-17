<?php

namespace App\Console\Commands;

use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Console\Command;

class QuoteDataToItem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quoteDataToItem:cron';

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
        $quotes = Quote::all();
        $inser_item_quote = [];
        foreach ($quotes as $quote){
            $rfq_prod_id = Quote::join('rfqs', function($join) {
                $join->on('rfqs.id', '=', 'quotes.rfq_id');
            })->join('rfq_products', function($join) {
                $join->on('rfqs.id', '=', 'rfq_products.rfq_id');
            })->where('quotes.id', $quote->id)->first(['rfq_products.id as id']);

            $inser_item_quote[] = array(
                'rfq_product_id' => $rfq_prod_id->id??null,
                'quote_id' => $quote->id??null,
                'quote_item_number' => 'BQTN-'.$quote->id.'/101',
                'supplier_id' => $quote->supplier_id??null,
                'product_id' => $quote->product_id??null,
                'product_price_per_unit' => $quote->product_price_per_unit??0,
                'product_quantity' => $quote->product_quantity??0,
                'price_unit'=> $quote->price_unit??0,
                'product_amount' => $quote->product_amount??0,
                'min_delivery_days' => $quote->min_delivery_days,
                'max_delivery_days' => $quote->max_delivery_days,
                'supplier_final_amount' => $quote->supplier_final_amount??0,
                'supplier_tex_value' => $quote->supplier_tex_value??0,
                'logistic_check' => $quote->logistic_check,
                'logistic_provided' => $quote->logistic_provided
            );
        }

        QuoteItem::insert($inser_item_quote);
        echo 'Done';
    }
}
