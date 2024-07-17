<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInsuranceFlagWoodPackingColumnsQuoteItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quote_items', function (Blueprint $table) {
            $table->unsignedBigInteger('insurance_flag')->nullable(true)->default(0)->after('logistics_service_code');
            $table->unsignedBigInteger('wood_packing')->nullable(true)->default(0)->after('insurance_flag');
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
            $table->dropColumn('insurance_flag');
            $table->dropColumn('wood_packing');
        });
    }
}
