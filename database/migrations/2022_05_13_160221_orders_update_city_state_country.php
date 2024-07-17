<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrdersUpdateCityStateCountry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->bigInteger('city_id')->nullable()->after('city')->comment('City table id');
            $table->bigInteger('state_id')->nullable()->after('state')->comment('State table id');
            $table->bigInteger('country_one_id')->nullable()->after('pincode')->comment('Country one table id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['city_id', 'state_id', 'country_one_id']);
        });
    }
}
