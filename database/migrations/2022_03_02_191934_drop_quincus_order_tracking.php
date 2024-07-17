<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropQuincusOrderTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quincus_order_tracking', function (Blueprint $table) {
            Schema::dropIfExists('quincus_order_tracking');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        
        Schema::table('quincus_order_tracking', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('airwaybill_number', 30);
            $table->unsignedBigInteger('blitznet_status_id');
            $table->string('quincus_status_code', 10);
            $table->string('process_status', 256);
            $table->string('quincus_status_description', 512);
            $table->string('quincus_status_stage', 256);
            $table->timestamp('process_datetime');
            $table->string('process_location',512);
            $table->string('process_signature',512)->nullable(true);
            $table->string('process_photo',512)->nullable(true);
            $table->string('process_latitude',512)->nullable(true);
            $table->string('process_longitude',512)->nullable(true);
            $table->string('process_maps_location',512)->nullable(true);
            $table->string('process_received_by',512)->nullable(true);
            $table->string('process_received_relation',512)->nullable(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('blitznet_status_id')->references('id')->on('order_status')->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();
    }
}
