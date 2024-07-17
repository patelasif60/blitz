<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanProviderApiResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_provider_api_responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('loan_provider_id')->comment('foriegn key loan provider id');
            $table->unsignedBigInteger('applicant_id')->comment('loan_apllicants id as foreign kry');
            $table->unsignedBigInteger('user_id')->comment('loan_apllicants id as foreign kry');
            $table->text('response_code')->comment('Api response Code');
            $table->json('response_data')->comment('Api response data');
            $table->timestamps();
            $table->softDeletes(); // this will create deleted_at field for softdelete

            $table->foreign('loan_provider_id')->references('id')->on('loan_providers')->onDelete('cascade');
            $table->foreign('applicant_id')->references('id')->on('loan_applicants')->onDelete('cascade');
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
        Schema::dropIfExists('loan_provider_api_responses');
    }
}
