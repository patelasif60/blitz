<?php

namespace Database\Seeders\Loan;

use App\Models\BankDetails;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class BankDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        BankDetails::truncate();
        BankDetails::insert([
            ['bank_name' => 'Mandiri', 'loan_provider_id' => null, 'ac_name' => 'PT. Blitznet Upaya Indonesia', 'ac_no' => '101-00-1160974-8', 'bank_code' => '008', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['bank_name' => 'Koinworks', 'loan_provider_id' => LOAN_PROVIDERS['KOINWORKS'], 'ac_name' => 'Blitznet', 'ac_no' => '133341118692861', 'bank_code' => 'BRI', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
