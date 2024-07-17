<?php

namespace Database\Seeders;

use App\Jobs\SeedCityJob;
use App\Models\CountryOne;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SeedIndonesianCity extends Seeder
{
    /**
     * Seed indonesian cities by API.
     *
     * @return void
     */
    public function run()
    {
        $countries = CountryOne::where('id','102')->get();

        $delay = 30;
        foreach ($countries as $country) {

            $states = State::where('country_id',$country->id)->get();

            foreach ($states as $state) {

                SeedCityJob::dispatch($country, $state)->delay(Carbon::now()->addSecond($delay));

                $delay = $delay+5;

            }

        }
    }
}
