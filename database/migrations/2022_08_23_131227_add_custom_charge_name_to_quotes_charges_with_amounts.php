<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomChargeNameToQuotesChargesWithAmounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes_charges_with_amounts', function (Blueprint $table) {
            $table->string('custom_charge_name',512)->nullable(true)->after('charge_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes_charges_with_amounts', function (Blueprint $table) {
             $table->dropColumn('custom_charge_name');
        });
    }
}
