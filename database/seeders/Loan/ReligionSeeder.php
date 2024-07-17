<?php

namespace Database\Seeders\Loan;

use App\Models\Religion;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Religion::truncate();
        Religion::insert([
            ['name' => 'ISLAM', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'KATHOLIK', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'KRISTEN', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'BUDHA', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'HINDU', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'KONGHUCHU', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'OTHER', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
