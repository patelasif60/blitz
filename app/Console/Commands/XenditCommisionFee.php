<?php

namespace App\Console\Commands;

use App\Models\OtherCharge;
use App\Models\UserCompanies;
use App\Models\XenditCommisionFee as XenditCommisionFees;
use Illuminate\Console\Command;

class XenditCommisionFee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xenditCommisionFee:cron';

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
        $getAllUserCompany = UserCompanies::groupBy('company_id')->get();
        $getAllCharges = OtherCharge::where('charges_type', 2)->get();
        foreach ($getAllUserCompany as $company){
            foreach ($getAllCharges as $charge){
                XenditCommisionFees::updateOrCreate(['charge_id' => $charge->id, 'company_id' => $company->company_id],['charge_id' => $charge->id, 'company_id' => $company->company_id, 'is_delete' => $charge->is_deleted]);
            }
        }
        return 1;
    }
}
