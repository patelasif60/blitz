<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldBatchIdColumnAirwaybillNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('airwaybill_number', function (Blueprint $table) {
            $table->unsignedBigInteger('order_batch_id')->after('order_id')->nullable()->comment('batch id from order_batches table');
            
            $table->foreign('order_batch_id')->references('id')->on('order_batches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('airwaybill_number', function (Blueprint $table) {
            $table->dropForeign('order_batch_id');
            $table->dropColumn('order_batch_id');
        });
    }
}
