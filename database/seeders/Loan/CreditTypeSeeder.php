<?php

namespace Database\Seeders\Loan;

use App\Models\LoanType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CreditTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        LoanType::truncate();

        LoanType::insert([
            ['name' => 'SB Loan', 'description' => 'Small buisness loan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);

        Schema::enableForeignKeyConstraints();

    }
}
