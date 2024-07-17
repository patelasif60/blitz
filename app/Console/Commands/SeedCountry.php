<?php

namespace App\Console\Commands;

use App\Models\CountryOne;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Log;


class SeedCountry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:country';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This CRON will fetch from API (countrystatecity) and seed the country data in database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new Client();
        
        $res = $client->request('GET', config('api.countrystatecity.country'),[
            'headers' => [
                'X-CSCAPI-KEY'      => config('services.countrystatecity.client_secret'),
                'accept'            => 'application/json'
            ]
        ]);

        if ($res->getStatusCode() == '200') {

            $countries = $res->getBody()->getContents();

            $countryCollection = collect(json_decode($countries, true));

            if (!empty($countryCollection)) {

                foreach ($countryCollection as $country) {

                    try {
                        CountryOne::UpdateOrCreate([
                            'iso2'          =>  $country['iso2'],
                        ],[
                            'name'          =>  $country['name'],
                            'created_by'    =>  0,
                            'updated_by'    =>  0
                        ]);
                    } catch (\Exception $e) {

                        Log::error('Country CRON: Something went wrong. Error:500');

                        echo 'Something went wrong. Error:500'; exit;
                    }

                }

                Log::info('Country CRON: Data updated successfully. Success:200');

                echo 'Data updated successfully. Success:200'; exit;

            }

        } else {

            Log::error('Country CRON: Something went wrong. Error:401');

            echo 'Something went wrong. Error:401';

        }


    }
}
