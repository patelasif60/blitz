<?php

namespace Database\Seeders\Loan;

use App\Models\RelationshipWithBorrower;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class RelationshipWithBorrowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        RelationshipWithBorrower::truncate();
        RelationshipWithBorrower::insert([
            ['name' => 'PARENT', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'SIBLING', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'SPOUSE', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'COLLEAGUE', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'PROFESSIONAL', 'description' => null, 'status' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'OTHER', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
