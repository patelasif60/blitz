<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanApiResponsesRequestDataColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_provider_api_responses', function (Blueprint $table) {
            $table->json('request_data')->nullable()->comment('API requested data')->after('user_id');
            $table->text('response_code')->nullable()->change();
            $table->text('response_data')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_provider_api_responses', function($table) {
            $table->dropColumn('request_data');
        });
    }
}
