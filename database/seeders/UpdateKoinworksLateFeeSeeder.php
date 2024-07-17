<?php

namespace Database\Seeders;

use App\Models\LoanProviderCharges;
use App\Models\LoanProviderChargesType;
use Illuminate\Database\Seeder;

class UpdateKoinworksLateFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LoanProviderCharges::where('charges_type_id',LoanProviderChargesType::LATE_FEE)->update([
            'value' => 0.2
        ]);
    }
}
