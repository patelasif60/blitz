<?php

namespace Database\Seeders\Loan;

use App\Models\HasLivedHere;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class HasLivedHereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        HasLivedHere::truncate();
        HasLivedHere::insert([
            ['name' => 'Ya', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Tidak', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
