<?php

namespace Database\Seeders\Loan;

use App\Models\HomeOwnershipStatus;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class HomeOwnershipStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        HomeOwnershipStatus::truncate();
        HomeOwnershipStatus::insert([
            ['name' => 'FAMILY/KELUARGA', 'description' => null,  'status' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'PARENT/ORANG TUA', 'description' => null, 'status' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'RENTAL/KOS', 'description' => null,  'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'OWNED/MILIK SENDIRI', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'OFFICE RESIDENCE/RUMAH DINAS', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
