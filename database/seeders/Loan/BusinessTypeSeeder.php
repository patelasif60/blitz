<?php

namespace Database\Seeders\Loan;

use App\Models\BusinessType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class BusinessTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        BusinessType::truncate();
        BusinessType::insert([
            ['name' => 'individual', 'description' => null, 'status' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'pt', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'cv', 'description' => null, 'status' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
