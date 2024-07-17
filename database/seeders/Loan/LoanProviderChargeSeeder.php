<?php

namespace Database\Seeders\Loan;

use App\Models\LoanProvider;
use App\Models\LoanProviderCharges;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class LoanProviderChargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        LoanProviderCharges::truncate();
        LoanProviderCharges::insert([
            [
                'loan_provider_id'=>LOAN_PROVIDERS['KOINWORKS'],
                'charges_type_id'=>LOAN_PROVIDER_CHARGE_TYPE['INTEREST'],
                'amount_type'=>0,//0=>%,1=>Flat
                'addition_substraction'=>1,// 0=>minus(-),1=>plus(+)
                'value'=>2,
                'period_in_days'=>30,
                'period_in_month'=>null,
            ],
            [
                'loan_provider_id'=>LOAN_PROVIDERS['KOINWORKS'],
                'charges_type_id'=>LOAN_PROVIDER_CHARGE_TYPE['REPAYMENT_CHARGE'],
                'amount_type'=>1,//0=>%,1=>Flat
                'addition_substraction'=>1,// 0=>minus(-),1=>plus(+)
                'value'=>10545,
                'period_in_days'=>30,
                'period_in_month'=>null,
            ],
            [
                'loan_provider_id'=>LOAN_PROVIDERS['KOINWORKS'],
                'charges_type_id'=>LOAN_PROVIDER_CHARGE_TYPE['INTERNAL_TRANSFER_CHARGE'],
                'amount_type'=>1,//0=>%,1=>Flat
                'addition_substraction'=>1,// 0=>minus(-),1=>plus(+)
                'value'=>10000,
                'period_in_days'=>30,
                'period_in_month'=>null,
            ],
            [
                'loan_provider_id'=>LOAN_PROVIDERS['KOINWORKS'],
                'charges_type_id'=>LOAN_PROVIDER_CHARGE_TYPE['ORIGINATION_CHARGE'],
                'amount_type'=>0,//0=>%,1=>Flat
                'addition_substraction'=>1,// 0=>minus(-),1=>plus(+)
                'value'=>0,
                'period_in_days'=>30,
                'period_in_month'=>null,
            ],
            [
                'loan_provider_id'=>LOAN_PROVIDERS['KOINWORKS'],
                'charges_type_id'=>LOAN_PROVIDER_CHARGE_TYPE['VAT'],
                'amount_type'=>0,//0=>%,1=>Flat
                'addition_substraction'=>1,// 0=>minus(-),1=>plus(+)
                'value'=>11,
                'period_in_days'=>30,
                'period_in_month'=>null,
            ],
            [
                'loan_provider_id'=>LOAN_PROVIDERS['KOINWORKS'],
                'charges_type_id'=>LOAN_PROVIDER_CHARGE_TYPE['LATE_FEE'],
                'amount_type'=>0,//0=>%,1=>Flat
                'addition_substraction'=>1,// 0=>minus(-),1=>plus(+)
                'value'=>6,
                'period_in_days'=>1,
                'period_in_month'=>null,
            ],
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
