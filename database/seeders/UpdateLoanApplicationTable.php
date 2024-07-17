<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LoanApplication;
use Illuminate\Support\Facades\DB;

class UpdateLoanApplicationTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        LoanApplication::whereNotNull('reserved_amount')->update([
            'senctioned_amount' => DB::raw('loan_limit'),
            'remaining_amount' => DB::raw('reserved_amount'),
        ]);

    }
}
