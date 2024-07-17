<?php

namespace App\Console\Commands;

use App\Models\Rfq;
use App\Models\RfqProduct;
use Illuminate\Console\Command;

class RfqProductItemNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rfqProductItemNumber:cron';

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
        $allRfqs = Rfq::all();
        $updateRfqProductItemNumber = [];
        foreach ($allRfqs as $rfq){
            $updateRfqProductItemNumber = array(
                'rfq_product_item_number' => 'BRFQ-'.$rfq->id.'/101'
            );
            RfqProduct::where('id', $rfq->id)->update($updateRfqProductItemNumber);
        }

    }
}
