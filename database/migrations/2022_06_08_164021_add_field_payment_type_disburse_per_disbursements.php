<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldPaymentTypeDisbursePerDisbursements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disbursements', function (Blueprint $table) {
            $table->boolean('payment_type')->default(1)->after('status')->comment('0=>%,1=>Flat');
            $table->double('disbursement_per')->nullable(true)->after('payment_type')->comment('Disbursement Percentage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('disbursements', function (Blueprint $table) {
            $table->dropColumn('payment_type');
            $table->dropColumn('disbursement_per');
        });
    }
}
