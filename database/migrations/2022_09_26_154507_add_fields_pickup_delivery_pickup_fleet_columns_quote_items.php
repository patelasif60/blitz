<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsPickupDeliveryPickupFleetColumnsQuoteItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quote_items', function (Blueprint $table) {
            $table->string('pickup_service',255)->nullable(true)->after('logistics_service_code');
            $table->string('pickup_fleet',255)->nullable(true)->after('pickup_service');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_items', function (Blueprint $table) {
            $table->dropColumn('pickup_service');
            $table->dropColumn('pickup_fleet');
        });
    }
}
