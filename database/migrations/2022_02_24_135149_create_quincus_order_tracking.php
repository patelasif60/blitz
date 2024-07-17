<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuincusOrderTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quincus_order_tracking', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('airwaybill_number', 30);
            $table->bigInteger('blitznet_status_id')->nullable()->unsigned();
            $table->string('quincus_status_code', 10);
            $table->text('process_status');
            $table->text('quincus_status_description')->nullable();
            $table->text('quincus_status_stage');
            $table->timestamp('process_datetime');
            $table->text('process_location');
            $table->text('process_signature')->nullable(true);
            $table->text('process_photo')->nullable(true);
            $table->string('process_latitude',512)->nullable(true);
            $table->string('process_longitude',512)->nullable(true);
            $table->text('process_maps_location')->nullable(true);
            $table->string('process_received_by',512)->nullable(true);
            $table->string('process_received_relation',512)->nullable(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quincus_order_tracking');
    }
}




