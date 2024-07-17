<?php

namespace Database\Seeders;

use Database\Seeders\Loan\CreditProviderAPISeeder;
use Database\Seeders\Loan\CreditProviderSeeder;
use Database\Seeders\Loan\CreditTypeSeeder;
use Database\Seeders\Loan\LoanProviderChargeSeeder;
use Database\Seeders\Loan\LoanProviderChargeTypeSeeder;
use Database\Seeders\Loan\SeedLoanBusinessCategory;
use Database\Seeders\Loan\BusinessTypeSeeder;
use Database\Seeders\Loan\GenderSeeder;
use Database\Seeders\Loan\HasLivedHereSeeder;
use Database\Seeders\Loan\HomeOwnershipStatusSeeder;
use Database\Seeders\Loan\MaritalStatusSeeder;
use Database\Seeders\Loan\MyPositionSeeder;
use Database\Seeders\Loan\NumberOfEmployeesSeeder;
use Database\Seeders\Loan\OtherSourceOfIncomeSeeder;
use Database\Seeders\Loan\RelationshipWithBorrowerSeeder;
use Database\Seeders\Loan\ReligionSeeder;
use Database\Seeders\Loan\LoanStatusSeeder;
use Database\Seeders\Loan\PaymentProviderSeeder;
use Database\Seeders\Loan\BankDetailSeeder;
use Database\Seeders\Loan\TransactionsTypeSeeder;
use Database\Seeders\Loan\PaymentProviderAccountSeeder;
use Illuminate\Database\Seeder;

class SetupCreditProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CreditTypeSeeder::class);
        $this->call(CreditProviderSeeder::class);
        $this->call(CreditProviderAPISeeder::class);
        $this->call(SeedLoanBusinessCategory::class);

        $this->call(BusinessTypeSeeder::class);
        $this->call(GenderSeeder::class);
        $this->call(HasLivedHereSeeder::class);
        $this->call(HomeOwnershipStatusSeeder::class);
        $this->call(MaritalStatusSeeder::class);
        $this->call(MyPositionSeeder::class);
        $this->call(NumberOfEmployeesSeeder::class);
        $this->call(OtherSourceOfIncomeSeeder::class);
        $this->call(RelationshipWithBorrowerSeeder::class);
        $this->call(ReligionSeeder::class);
        $this->call(LoanProviderChargeTypeSeeder::class);
        $this->call(LoanProviderChargeSeeder::class);
        $this->call(LoanStatusSeeder::class);
        $this->call(PaymentProviderSeeder::class);
        $this->call(BankDetailSeeder::class);
        $this->call(TransactionsTypeSeeder::class);
        $this->call(PaymentProviderAccountSeeder::class);
    }
}
