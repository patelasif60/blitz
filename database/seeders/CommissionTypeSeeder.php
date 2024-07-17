<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CommissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //this code will empty table
        Schema::disableForeignKeyConstraints();
        DB::table('commission_types')->truncate();
        Schema::enableForeignKeyConstraints();

        //this code will insert data
        DB::table('commission_types')->insert([
            [
                'id'=>1,
                'name'=>'Group Commission',
                'description'=>'',
            ],
            [
                'id'=>2,
                'name'=>'Blitznet Commission',
                'description'=>'',
            ],
        ]);
    }
}
