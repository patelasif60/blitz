<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->insert([
           
            [
				'name'=>'Indonesian Rp',
            ],
            
        ]);
    }
}
