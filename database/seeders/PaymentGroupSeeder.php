<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PaymentGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_groups')->insert([
            [
            	'name'=>'Credit',
            ],
            [
				'name'=>'NEFT',
            ],
            [
				'name'=>'Bank Transfer',
            ],
            [
				'name'=>'Direct Deposite',
            ],
            
        ]);
    }
}
