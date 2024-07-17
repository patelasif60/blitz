<?php

namespace Database\Seeders;

use App\Models\Quote;
use App\Models\SupplierAddress;
use Illuminate\Database\Seeder;

class AddCountryInPickUpAddress extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SupplierAddress::where('country_id', null)->update(['country_id' => 102]);
        Quote::where('country_id', null)->update(['country_id' => 102]);
    }
}
