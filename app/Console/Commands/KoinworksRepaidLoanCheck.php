<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\KoinworksRepaidLoanCheckJob;

class KoinworksRepaidLoanCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'koinworks:creditlimit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute Koinworks use unused amount Job';

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
        dispatch(new KoinworksRepaidLoanCheckJob());
        echo "Koinwork Repaid done successfully!!!!!!!!!!!!";
    }
}
