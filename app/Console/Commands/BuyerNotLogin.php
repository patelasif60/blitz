<?php

namespace App\Console\Commands;

use App\Jobs\BuyerNotLoginJob;
use Illuminate\Console\Command;

class BuyerNotLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BuyerNotLogin:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Buyer has not loged in from last 7 days.';

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
        BuyerNotLoginJob::dispatch();
    }
}
