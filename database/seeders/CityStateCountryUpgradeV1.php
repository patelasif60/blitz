<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\CountryOne;
use App\Models\Order;
use App\Models\Quote;
use App\Models\Rfq;
use App\Models\State;
use App\Models\SupplierAddress;
use App\Models\UserAddresse;
use Illuminate\Database\Seeder;

class CityStateCountryUpgradeV1 extends Seeder
{
    /**
     * Update city, state, and country (Indonesia) in RFQ, SupplierAddress, UserAddresses, Quotes, Orders table.
     *
     * @return void
     */
    public function run()
    {
        Rfq::all()->each( function($rfq){

            $rfq->country_one_id = CountryOne::DEFAULTCOUNTRY;

            if (!empty($rfq->state)) {

                $stateId = State::where('country_id', CountryOne::DEFAULTCOUNTRY)->where('name', $rfq->state)->pluck('id')->first();

                if (!empty($stateId)) {

                    $rfq->state_id = $stateId;
                    $rfq->save();

                } else {

                    $rfq->state_id = -1;
                    $rfq->save();

                }

                $cityId = City::where('country_id', CountryOne::DEFAULTCOUNTRY)->where('state_id', $stateId)->where('name', $rfq->city)->pluck('id')->first();

                if (!empty($cityId)) {

                    $rfq->city_id = $cityId;
                    $rfq->save();

                } else {

                    $rfq->city_id = -1;
                    $rfq->save();

                }

            }

        });

        SupplierAddress::all()->each( function ($supplierAddress){

            $supplierAddress->country_one_id = CountryOne::DEFAULTCOUNTRY;

            if (!empty($supplierAddress->state)) {

                $stateId = State::where('country_id', CountryOne::DEFAULTCOUNTRY)->where('name', $supplierAddress->state)->pluck('id')->first();

                if (!empty($stateId)) {

                    $supplierAddress->state_id = $stateId;
                    $supplierAddress->save();

                } else {

                    $supplierAddress->state_id = -1;
                    $supplierAddress->save();

                }

                $cityId = City::where('country_id', CountryOne::DEFAULTCOUNTRY)->where('state_id', $stateId)->where('name', $supplierAddress->city)->pluck('id')->first();

                if (!empty($cityId)) {

                    $supplierAddress->city_id = $cityId;
                    $supplierAddress->save();

                } else {

                    $supplierAddress->city_id = -1;
                    $supplierAddress->save();

                }

            }

        });

        UserAddresse::all()->each( function ($userAddress){

            $userAddress->country_one_id = CountryOne::DEFAULTCOUNTRY;

            if (!empty($userAddress->state)) {

                $stateId = State::where('country_id', CountryOne::DEFAULTCOUNTRY)->where('name', $userAddress->state)->pluck('id')->first();

                if (!empty($stateId)) {

                    $userAddress->state_id = $stateId;
                    $userAddress->save();

                } else {

                    $userAddress->state_id = -1;
                    $userAddress->save();

                }

                $cityId = City::where('country_id', CountryOne::DEFAULTCOUNTRY)->where('state_id', $stateId)->where('name', $userAddress->city)->pluck('id')->first();

                if (!empty($cityId)) {

                    $userAddress->city_id = $cityId;
                    $userAddress->save();

                } else {

                    $userAddress->city_id = -1;
                    $userAddress->save();

                }

            }

        });

        Quote::all()->each( function ($quote){

            $quote->country_one_id = CountryOne::DEFAULTCOUNTRY;

            if (!empty($quote->state)) {

                $stateId = State::where('country_id', CountryOne::DEFAULTCOUNTRY)->where('name', $quote->state)->pluck('id')->first();

                if (!empty($stateId)) {

                    $quote->state_id = $stateId;
                    $quote->save();

                } else {

                    $quote->state_id = -1;
                    $quote->save();

                }

                $cityId = City::where('country_id', CountryOne::DEFAULTCOUNTRY)->where('state_id', $stateId)->where('name', $quote->city)->pluck('id')->first();

                if (!empty($cityId)) {

                    $quote->city_id = $cityId;
                    $quote->save();

                } else {

                    $quote->city_id = -1;
                    $quote->save();

                }

            }

        });

        Order::all()->each( function ($order){

            $order->country_one_id = CountryOne::DEFAULTCOUNTRY;

            if (!empty($order->state)) {

                $stateId = State::where('country_id', CountryOne::DEFAULTCOUNTRY)->where('name', $order->state)->pluck('id')->first();

                if (!empty($stateId)) {

                    $order->state_id = $stateId;
                    $order->save();

                } else {

                    $order->state_id = -1;
                    $order->save();

                }

                $cityId = City::where('country_id', CountryOne::DEFAULTCOUNTRY)->where('state_id', $stateId)->where('name', $order->city)->pluck('id')->first();

                if (!empty($cityId)) {

                    $order->city_id = $cityId;
                    $order->save();

                } else {

                    $order->city_id = -1;
                    $order->save();

                }

            }

        });

    }
}
