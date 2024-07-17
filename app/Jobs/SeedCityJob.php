<?php

namespace App\Jobs;

use App\Models\City;
use App\Models\CountryOne;
use App\Models\State;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class SeedCityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $country;
    protected $state;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($country, $state)
    {
        $this->country  = $country;
        $this->state    = $state;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $country    = $this->country;
        $state      = $this->state;

        $client = new Client();

        $res = $client->request('GET', config('api.countrystatecity.country').'/'.$country->iso2.'/states/'.$state->iso2.'/cities',[
            'headers' => [
                'X-CSCAPI-KEY'      => config('services.countrystatecity.client_secret'),
                'accept'            => 'application/json'
            ]
        ]);

        if ($res->getStatusCode() == '200') {

            $cities = $res->getBody()->getContents();

            $cityCollection = collect(json_decode($cities, true));

            if (!empty($cityCollection)) {

                foreach ($cityCollection as $city) {

                    try {
                        City::UpdateOrCreate([
                            'name'          =>  $city['name'],
                            'country_id'    =>  $country->id,
                            'state_id'      =>  $state->id,
                            'created_by'    =>  0,
                            'updated_by'    =>  0
                        ]);
                    } catch (\Exception $e) {

                        Log::error('City CRON: Something went wrong!!'. $city['name']);

                    }

                }

                Log::info('City CRON: Success 200!! '.$state->name);

            }

        } else {

            Log::error('City CRON: Something went wrong!! Error:401');
        }
    }
}
