<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('designations')->insert([
            [
            	'name'=>'Php developer'
            ],
            [
				'name'=>'Quality analyst'
            ]
            
        ]);
    }
}
