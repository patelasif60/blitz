<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AirWayBillNumber;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use URL;
use PDF;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class QuincusController extends Controller
{
    public function index()
    {
        //find maximum number of product to be added
        $max_product = Settings::where('key', 'multiple_rfq_max_added_product')->first()->value;
        return view('admin/priceCheck', ['max_products' => $max_product]);
    }

    function checkPrice(Request $request)
    {
        $product_data = json_decode($request->product_details);

        foreach ($product_data as $item) {
            unset($item->id);
        }
        // unset reference
        unset($item);

        // make json again
        // you may remove JSON_PRETTY_PRINT flag, I kept it just to see o/p
        $json_string_modified = json_encode($product_data, JSON_PRETTY_PRINT);

        $amount = (int)$request->goods_amount;
        $weight = (int)$request->weight;
        $quantity = (int)$request->quantity;
        $length = (int)$request->length;
        $width = (int)$request->width;
        $height = (int)$request->height;
        if ($request->insurance_flag) {
            $insurance = $request->insurance_flag;
        } else {
            $insurance = 'N';
        }
        if (config('app.env') == "live") {
            $apiKey = '10e800c1-576a-4ecd-96fb-7e363c706181';
        } else {
            $apiKey = 'a33ad093-b123-4a81-8ded-ac276e8a98dc';
        }
        $data = "{\"3pl\": \"$request->tpl\",\n\"from\": \"$request->from\",\n\"thru\": \"$request->thru\",\n\"goods_amount\": $amount,\n\"insurance_flag\": \"$insurance\",\n\"detail_charged\": $json_string_modified\n}";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://blitznet.omile.id/blitznet/restapi/basic/price/list_all_ltime_text_detail_gen_belink",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "api-key: " . $apiKey,
                "cache-control: no-cache",
                "content-type: application/json",
                "postman-token: 976aecd1-c345-b1a3-b6f6-2e9afc19b391"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $checkPriceData['origin_shipment_pincode'] = $request->from;
        $checkPriceData['destination_shipment_pincode'] = $request->thru;
        $checkPriceData['goods_amount'] = $request->goods_amount;

        if ($request->tpl === 'JNE') {
            //dd($response);
            $response = (array)json_decode($response);
            if (!empty($response['JNE'])) {
                $response = $response['JNE'];
            } else {
                return response()->json(array('success' => false, 'response' => $response));
            }
        } else {
            return response()->json(array('success' => false, 'response' => (array)json_decode($response)));
        }
        $priceDataHtml = view('admin/priceCheckResultData', ['checkPriceData' => $checkPriceData, 'results' => $response])->render();
        return response()->json(array('success' => true, 'response' => $priceDataHtml));
    }

    /* Show all airwaybills for shipping label (By Vrutika on 03/11/2022) */
    public function showShippingLabel()
    {
        //show all airwaybill numbers
        $airwayBills = AirWayBillNumber::whereNotNull('order_batch_id')->get();
        return view('admin/shippingLabel', ['airwayBillNumbers' => $airwayBills]);
    }

    public function getShippingLabel($awbno)
    {
        try {
            $endpoint = "https://blitznet.omile.id/api_connote_blitznet.php?id=" . $awbno;
            $client = new Client();

            $response = $client->request('GET', $endpoint, [
                'sink' => Storage::path('public/uploads/shippingLabel/' . $awbno . '.pdf')
            ]);

            $filePath = url('storage/uploads/shippingLabel/' . $awbno . '.pdf');
            return response()->json([
                'status' => true,
                'message' => 'Successfully get url',
                'pdfUrl' => $filePath ? $filePath : null,
                'pdfFileName' => $awbno . '.pdf',
                'ExceptionError' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something wrong please retry',
                'pdfUrl' => null,
                'pdfFileName' => null,
                'ExceptionError' => $e
            ]);
        }
    }

    public function deleteShippingLabel($awbFileName){
        if ($awbFileName!=''){
            Storage::delete('public/uploads/shippingLabel/'.$awbFileName);
            return response()->json(array('status' => true));
        }else{
            return response()->json(array('status' => false));
        }
    }
}
