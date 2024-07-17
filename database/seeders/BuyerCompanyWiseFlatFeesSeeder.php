<?php

namespace Database\Seeders;

use App\Models\OtherCharge;
use App\Models\XenditCommisionFee;
use Illuminate\Database\Seeder;

class BuyerCompanyWiseFlatFeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $otherCharges = OtherCharge::where('charges_type', 2)->get();
        foreach ($otherCharges as $charge){
            $data = array('type' => $charge->type, 'charges_value' => $charge->charges_value, 'charges_type' => $charge->charges_type, 'addition_substraction' => $charge->addition_substraction);
            XenditCommisionFee::where('charge_id', $charge->id)->update($data);
        }
    }
}
