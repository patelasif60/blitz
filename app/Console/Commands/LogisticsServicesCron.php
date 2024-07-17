<?php

namespace App\Console\Commands;

use App\Models\LogisticsService;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class LogisticsServicesCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'LogisticsServices:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        if (config('app.env') == "live"){
            $apiKey = '10e800c1-576a-4ecd-96fb-7e363c706181';
        }else{
            $apiKey = 'a33ad093-b123-4a81-8ded-ac276e8a98dc';
        }
        //Truncate Table first (returns records count)
        $logisticsService = LogisticsService::count();
        if($logisticsService != 0) {
            LogisticsService::truncate();
        }

        //Run API and data will be insert in table
        $client = new Client();
        $response = $client->get('https://blitznet.omile.id/blitznet/restapi/basic/service/list', [
            'headers' => [
                'api-key' => $apiKey,
            ]
        ]);
        $response = $response->getBody()->getContents();

        $logisticsProvider = 1; //1 => quincus

        //Store Airwaybill number request and response (Ronak M - 06/09/2022)
        storeLogisticsAPIRequestResponse($logisticsProvider, $userId = 1, $requestData = null, $responseCode = null, $response);

        foreach(json_decode($response)->service->data as $service) {
            $serviceDataArray[] = [
                'logistics_provider_id' => $logisticsProvider,
                'service_code' => $service->SERVICE_CODE,
                'service_name' => $service->SERVICE_NAME,
                'service_description' => $service->SERVICE_NAME
            ];
        }
        LogisticsService::insert($serviceDataArray);
        echo 'Logistics service has been added successfully';

    }
}
