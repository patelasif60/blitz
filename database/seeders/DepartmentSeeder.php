<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            [
            	'name'=>'PHP',
            ],
            [
				'name'=>'QA',
            ],
            [
				'name'=>'Admin',
            ]
            
        ]);
    }
}
