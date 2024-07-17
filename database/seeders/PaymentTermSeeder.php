<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PaymentTermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_terms')->insert([
            [
            	'name'=>'Payment in Advance',
            	'payment_group_id' =>'1',
            ],
            [
				'name'=>'Payment 7 day after invoice date',
            	'payment_group_id' =>'1',
            ],
            
        ]);
    }
}
