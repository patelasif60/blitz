<?php

namespace Database\Seeders\Loan;

use App\Models\LoanProvider;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CreditProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        LoanProvider::truncate();

        LoanProvider::insert([
            ['name' => 'Koinworks', 'description' => 'Koinworks', 'production_base_path' => 'https://open-api.koinworks.com', 'staging_base_path' => 'https://open-sandbox.koin.works']
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
