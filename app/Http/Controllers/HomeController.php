<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Auth;
use Illuminate\Support\Facades\View;
use Mail;
class HomeController extends Controller
{
    //
    public function lang($locale)
    {
        App::setLocale($locale);
        if(Auth::user()){
            // dd(Auth::user()->id);
            session()->put('locale', $locale);
        }else{
            session()->put('localelogin', $locale);
        }
        // echo app()->getLocale();
        // dd(session()->get('locale'));
        return redirect()->back();
    }

    public function calendlyWebhookRegister()
    {
        $access_token = env('CALENDLY_ACCESS_KEY');

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.calendly.com/users/me",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer ".$access_token,
                "Content-Type: application/json"
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response);
            $current_organization = $response->resource->current_organization;
            // dd($response->resource);
            $curl = curl_init();
            // $clweb_url = "http://localhost:8000/calendly_response";
            // $clweb_url = "http://beta.blitznet.co.id/test";
            $clweb_url = url('calendly_response');
            // dd($clweb_url);
            $account = [
                // "url"=>"https://localhost:8000/bar",
                "url"=> $clweb_url,
                "events"=> ["invitee.created","invitee.canceled"],
                "organization"=> $current_organization,
                // "user" => "https://api.calendly.com/users/BBBBBBBBBBBBBBBB",
                // "scope"=> "user",
                "scope"=> "organization",
                // "signing_key"=> $access_token
            ];
            $accountDetails = json_encode($account);

            curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.calendly.com/webhook_subscriptions",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $accountDetails,
            // CURLOPT_POSTFIELDS => "{\n  \"url\": \"https://localhost:8000/bar\",\n  \"events\": [\n    \"invitee.created\",\n    \"invitee.canceled\"\n  ],\n  \"organization\": \"https://api.calendly.com/organizations/AAAAAAAAAAAAAAAA\",\n  \"user\": \"https://api.calendly.com/users/BBBBBBBBBBBBBBBB\",\n  \"scope\": \"user\",\n  \"signing_key\": \"WHALR2TOXWLYM6Q3TD5TRVECBAM2EM2U\"\n}",
            CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer ".$access_token,
                    "Content-Type: application/json"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
            echo "cURL Error #:" . $err;
            } else {
                echo "success <br>";
                echo $response;
            }
        }
    }

    public function calendlyWebhook(Request $request)
    {
        // Mail::send('calendly-test', ['token' => $token,'full_name' => $user->firstname .' '. $user->lastname], function($message) use($request){
        Mail::send('calendly-test', ['token' => "token"], function($message) use($request){
            $message->to("ronakbhabhor96@gmail.com");
            $message->subject('Calendly test');
        });
        echo 'mail send done';
        return 'mail send done';
    }

    /**
     * Sitemap XML - Front Website
     *
     * @return mixed
     */
    public function sitemap()
    {

        return \Illuminate\Support\Facades\Redirect::to('uploads/sitemap.xml');

    }
}
