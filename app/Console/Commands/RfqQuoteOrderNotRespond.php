<?php

namespace App\Console\Commands;
use App\Jobs\RfqQuoteOrderNotRespondJob;
use Illuminate\Console\Command;

class RfqQuoteOrderNotRespond extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RfqQuoteOrderNotRespond:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily List of not responded RFQ, Quote and Order';

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
        RfqQuoteOrderNotRespondJob::dispatch();
    }
}
