<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogisticsServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('logistics_provider_id')->nullable()->comment('foriegn key for logistics provider');
            $table->string('service_code',255)->nullable(true);
            $table->string('service_name',255)->nullable(true);
            $table->text('service_description')->nullable(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes(); //this will create deleted_at field for softdelete

            $table->foreign('logistics_provider_id')->references('id')->on('logistics_providers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logistics_services');
    }
}
