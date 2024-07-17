<?php

namespace Database\Seeders\Loan;

use App\Models\LoanProviderChargesType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class LoanProviderChargeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        LoanProviderChargesType::truncate();
        LoanProviderChargesType::insert([
            [
                'loan_provider_id'=>LOAN_PROVIDERS['KOINWORKS'],
                'name'=>'Interest',
                'description'=> '2% Interest for 30 Days',
                'interest_rate_is_by_buyer'=>0,
                'status'=>1
            ],
            [
                'loan_provider_id'=>LOAN_PROVIDERS['KOINWORKS'],
                'name'=>'Repayment Charges',
                'description'=>'10,545 Transaction Charges',
                'interest_rate_is_by_buyer'=>0,
                'status'=>1
            ],
            [
                'loan_provider_id'=>LOAN_PROVIDERS['KOINWORKS'],
                'name'=>'Internal Transfer Charges',
                'description'=>'10000 Xen Credit A/C to Supplier A/C Transfer Charges',
                'interest_rate_is_by_buyer'=>0,
                'status'=>1
            ],
            [
                'loan_provider_id'=>LOAN_PROVIDERS['KOINWORKS'],
                'name'=>'Origination Charges',
                'description'=>'origination charges which will deduct on loan disbursement from loan provider side',
                'interest_rate_is_by_buyer'=>1,
                'status'=>0
            ],
            [
                'loan_provider_id'=>LOAN_PROVIDERS['KOINWORKS'],
                'name'=>'VAT',
                'description'=>'Tax',
                'interest_rate_is_by_buyer'=>0,
                'status'=>0
            ],
            [
                'loan_provider_id'=>LOAN_PROVIDERS['KOINWORKS'],
                'name'=>'Late Fee',
                'description'=>'Late Fee Charges',
                'interest_rate_is_by_buyer'=>0,
                'status'=>1
            ],
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
