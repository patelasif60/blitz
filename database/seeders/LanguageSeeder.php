<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->insert([
            
            [
				'name'=>'ID',
            	'description' =>'Indonesia',
            ],
            [
            	'name'=>'EN',
            	'description' =>'English',
            ],
            
        ]);
    }
}
