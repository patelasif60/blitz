<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\buyer\Credit\KoinWorks\KoinworksLoanLateFeeJob;

class KoinworksLoanLateFee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'koinworks:latefee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute Koinworks Late Fee Job';

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
        dispatch(new KoinworksLoanLateFeeJob());
    }
}
