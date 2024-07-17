<?php

namespace Database\Seeders\Loan;

use App\Models\OtherSourceOfIncome;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class OtherSourceOfIncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        OtherSourceOfIncome::truncate();
        OtherSourceOfIncome::insert([
            ['name' => 'BUSINESS REVENUE', 'description' => null, 'status' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'FUND REVENUE', 'description' => null, 'status' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'INHERITANCE', 'description' => null, 'status' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'SALARY', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'PARENT/GUARDIAN', 'description' => null, 'status' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
