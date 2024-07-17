<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogisticsApiResponses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_api_responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('logistics_provider_id')->nullable()->comment('foriegn key for logistics provider');
            $table->unsignedBigInteger('user_id')->comment('user_id as foreign key');
            $table->json('request_data')->nullable(true)->comment('API requested data');
            $table->text('response_code')->nullable(true)->comment('Api response code');
            $table->text('response_data')->nullable(true)->comment('Api response data');
            $table->timestamps();
            $table->softDeletes(); // this will create deleted_at field for softdelete
        
            $table->foreign('logistics_provider_id')->references('id')->on('logistics_providers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logistics_api_responses');
    }
}
