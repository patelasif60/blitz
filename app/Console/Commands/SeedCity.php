<?php

namespace App\Console\Commands;

use App\Jobs\SeedCityJob;
use App\Models\CountryOne;
use App\Models\State;
use Illuminate\Console\Command;
use Log;
use Carbon\Carbon;

class SeedCity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:city';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync city by countries and state - API countrystatecity';

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

        $delayMinutes = 1;
        foreach ($countries as $country) {

            $states = State::where('country_id',$country->id)->get();

            foreach ($states as $state) {

                SeedCityJob::dispatch($country, $state)->delay(Carbon::now()->addMinutes($delayMinutes));

                $delayMinutes++;

            }

        }

    }

}
