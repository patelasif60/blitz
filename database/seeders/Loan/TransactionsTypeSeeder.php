<?php

namespace Database\Seeders\Loan;

use App\Models\TransactionsType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TransactionsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        TransactionsType::truncate();
        TransactionsType::insert([
            ['name' => 'Internal transfer(xendit)', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'External transfer(xendit)', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Disbursment to supplier', 'description' => 'disbursment to supplier bank ac', 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Disbursement koinworks to blitznet', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Buyer Re-Payment', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Disbursement blitznet to koinworks', 'description' => null, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Commission transfer to Blitznet', 'description' => 'commission transfer to blitznet xen ac', 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Charges', 'description' => 'loan related charges', 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
