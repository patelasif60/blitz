<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreditDaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('credit_days')->insert([

            [
                'name'=>'Top 30 Days',
                'days'=>30,
                'description' =>'Top 30 Days',
            ],
            [
                'name'=>'Top 60 Days',
                'days'=>60,
                'description' =>'Top 60 Days',
            ],
            [
                'name'=>'Top 90 Days',
                'days'=>90,
                'description' =>'Top 90 Days',
            ],
        ]);
    }
}
