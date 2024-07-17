<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FeedbackReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Schema::disableForeignKeyConstraints();
        DB::table('admin_feedback_reasons')->truncate();
        Schema::enableForeignKeyConstraints();
        DB::table('admin_feedback_reasons')->insert([
            [
                'id'=>1,
                'reasons'=>'Buyer Needs a credit.',
                'reasons_type' => 1,
            ],
            [
                'id'=>2,
                'reasons'=>'Buyer Needs Working Capital Loan.',
                'reasons_type' => 1,
            ],
            [
                'id'=>3,
                'reasons'=>'Buyer wants a secure online Payment Services.',
                'reasons_type' => 1,
            ],
            [
                'id'=>4,
                'reasons'=>'Didn’t find a supplier for the RFQ product.',
                'reasons_type' => 1,
            ],
            [
                'id'=>5,
                'reasons'=>'All quotes have high price.',
                'reasons_type' => 1,
            ],
            [
                'id'=>6,
                'reasons'=>'Buyer wants multiple quote.',
                'reasons_type' => 1,
            ],
            [
                'id'=>7,
                'reasons'=>'Price was very high compared to another quote.',
                'reasons_type' => 2,
            ],
            [
                'id'=>8,
                'reasons'=>'Delivery date was very long.',
                'reasons_type' => 2,
            ],
            [
                'id'=>9,
                'reasons'=>'Supplier doesn’t have all requested product.',
                'reasons_type' => 2,
            ],
            [
                'id'=>10,
                'reasons'=>'Hesitate to pay online.',
                'reasons_type' => 2,
            ],
            [
                'id'=>11,
                'reasons'=>'Buyer wants to pay offline only.',
                'reasons_type' => 3,
            ],
            [
                'id'=>12,
                'reasons'=>'Supplier don’t have enough time to product a goods.',
                'reasons_type' => 3,
            ],
            [
                'id'=>13,
                'reasons'=>'Supplier doesn’t have enough raw material.',
                'reasons_type' => 3,
            ],
            [
                'id'=>14,
                'reasons'=>'Supplier don’t want to pay Xendit (25000) fee.',
                'reasons_type' => 3,
            ],
            [
                'id'=>15,
                'reasons'=>'Buyer needs some time to pay online.',
                'reasons_type' => 3,
            ],
            [
                'id'=>16,
                'reasons'=>'Buyer Needs a credit.',
                'reasons_type' => 4,
            ],
            [
                'id'=>17,
                'reasons'=>'Buyer Needs Working Capital Loan.',
                'reasons_type' => 4,
            ],
            [
                'id'=>18,
                'reasons'=>'Buyer wants a secure online Payment Services.',
                'reasons_type' => 4,
            ],
            [
                'id'=>19,
                'reasons'=>'Didn’t find a supplier for the RFQ product.',
                'reasons_type' => 4,
            ],
            [
                'id'=>20,
                'reasons'=>'All quotes have high price.',
                'reasons_type' => 4,
            ],
            [
                'id'=>21,
                'reasons'=>'Buyer wants multiple quote.',
                'reasons_type' => 4,
            ],
        ]);
    }
}
