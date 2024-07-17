<?php

namespace App\Console\Commands;

use App\Models\CountryOne;
use App\Models\State;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Log;

class SeedState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:state';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync state by countries - API countrystatecity';

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
        $countries = CountryOne::all();

        $error = collect();

        foreach ($countries as $country) {

            $response = json_decode($this->state($country)->getContent());

            $error = $error->push([

                'status'    => $response->success,
                'country'   => $country->id,
                'message'   => $response->message

            ]);

        }

        $error->contains('success', 'false') == true ? Log::error('State CRON: Something went wrong!!', $error) : Log::info('State CRON: Success 200!!');

        echo $error->contains('success', 'false') == true ? 'State CRON: Something went wrong!!' :  'State CRON: Success 200!!';
    }

    /**
     * Get state data
     *
     * @return array
     */
    public function state($country)
    {
        $client = new Client();

        $res = $client->request('GET', config('api.countrystatecity.country').'/'.$country->iso2.'/states',[
            'headers'   => [
                'X-CSCAPI-KEY'      => config('services.countrystatecity.client_secret'),
                'accept'            => 'application/json'
            ]
        ]);

        if ($res->getStatusCode() == '200') {

            $states = $res->getBody()->getContents();

            $stateCollection = collect(json_decode($states, true));

            if (!empty($stateCollection)) {

                foreach ($stateCollection as $state) {

                        try {
                            State::UpdateOrCreate([

                                'iso2'          =>  $state['iso2'],
                                'name'          =>  $state['name'],
                                'country_id'    =>  $country->id,
                                'created_by'    =>  0,
                                'updated_by'    =>  0

                            ]);

                        } catch (\Exception $e) {
                            return response()->json(['success' => false, 'message' => 'Something went wrong. Error:500']);

                        }
                }

                return response()->json(['success' => true, 'message' => 'Data updated successfully. Success:200']);

            }

        } else {
            return response()->json(['success' => false, 'message' => 'Something went wrong. Error:401']);
        }



    }

}
