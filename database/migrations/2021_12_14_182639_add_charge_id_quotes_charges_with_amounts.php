<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChargeIdQuotesChargesWithAmounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes_charges_with_amounts', function (Blueprint $table) {
            $table->unsignedBigInteger('charge_id')->after('charge_name');
            $table->foreign('charge_id')->references('id')->on('other_charges')->onDelete('cascade');
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
            $table->dropForeign(['charge_id']);
            $table->dropColumn('charge_id');
        });
    }
}
