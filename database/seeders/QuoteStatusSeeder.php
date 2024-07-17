<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class QuoteStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quote_status')->insert([
            [
            	'name'=>'Quotation Received',
            	'backofflice_name' =>'Quotation Sent',
            ],
            [
				'name'=>'Quotation Accepted',
            	'status' =>'Quotation Accepted',
            ],
            [
				'name'=>'Quotation Expired',
            	'status' =>'Quotation Expired',
            ]
            
        ]);
    }
}
