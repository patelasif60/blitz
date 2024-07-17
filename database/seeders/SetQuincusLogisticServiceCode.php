<?php

namespace Database\Seeders;

use App\Models\QuoteItem;
use Illuminate\Database\Seeder;

class SetQuincusLogisticServiceCode extends Seeder
{
    /**
     * Run the database seeds.
     * update logistic_service_code,pickup_service,pickup_fleet,insurance_flag
     * @return void
     */
    public function run()
    {
        try {
            $logisticService  = QuoteItem::where('logistic_check',1)->where('logistic_provided',0)->whereNull('logistics_service_code')->get(['id', 'logistics_service_code', 'pickup_service','pickup_fleet', 'insurance_flag']);
            $logisticService->each(function($services){
                $services->logistics_service_code = 'REG19'; //  set logistics_service_code to 'REG19'
                $services->pickup_service = 'Express'; //  set logistics_service_code to 'REG19'
                $services->pickup_fleet = 'Motorcycle'; //  set logistics_service_code to 'REG19'
                $services->insurance_flag = '1'; //  set logistics_service_code to 'REG19'
                $services->save();
            });
            dd('Quote Items changes updated successfully.');
        } catch(QueryException $e) {
            dd('Something went wrong !!');
        }
    }
}
