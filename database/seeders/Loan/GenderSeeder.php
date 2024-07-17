<?php

namespace Database\Seeders\Loan;

use App\Models\Gender;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Gender::truncate();
        Gender::insert([
            ['name' => 'Male', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Female', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
