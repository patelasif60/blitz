<?php

namespace Database\Seeders\Loan;

use App\Models\NumberOfEmployees;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class NumberOfEmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        NumberOfEmployees::truncate();
        NumberOfEmployees::insert([
            ['name' => '1-50', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => '51-100', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => '101-250', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => '251-500', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => '501-1000', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => '1000+', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
