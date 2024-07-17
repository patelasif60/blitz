<?php

namespace App\Console\Commands;

use App\Jobs\DailyUserRegisterJob;
use Illuminate\Console\Command;

class BuyerSupplierNewRegistration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BuyerSupplierNewRegistration:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send List of New Registered Buyer & Supplier to operation team every morning.';

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
        DailyUserRegisterJob::dispatch();
    }
}
