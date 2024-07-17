<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldLoanProviderIdBankDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_details', function (Blueprint $table) {
            $table->unsignedBigInteger('loan_provider_id')->after('bank_name')->nullable(true);
            $table->foreign('loan_provider_id')->references('id')->on('loan_providers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_details', function (Blueprint $table) {
            $table->dropForeign(['loan_provider_id']);
            $table->dropColumn('loan_provider_id');
        });
    }
}
