<?php

namespace App\Console\Commands;

use App\Jobs\KoinworksLimitStatusJob;
use App\Models\LoanApplicant;
use App\Models\LoanApplication;
use Illuminate\Console\Command;

class koinworksLimitStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'koinworksLimitStatus:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update limit status via koinworks get limit API ';

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
        $statusArray = array_flip(LoanApplication::STATUS);
        $loanApplications = LoanApplication::whereNotIn('status', [$statusArray['Rejected'], $statusArray['Declined'], $statusArray['Expired'], $statusArray['Approved']])->get(['id', 'provider_application_id', 'applicant_id']);

        if(!empty($loanApplications)){
            dispatch(new KoinworksLimitStatusJob($loanApplications));
        }

        echo "limit status updated";
    }
}
