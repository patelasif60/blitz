<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldChargeTypeIdLoanTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('charge_type_id')->after('remarks')->nullable(true)->comment('loan_provider_charges table');
            $table->foreign('charge_type_id')->references('id')->on('loan_provider_charges')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_transactions', function (Blueprint $table) {
            $table->dropForeign(['charge_type_id']);
            $table->dropColumn('charge_type_id');
        });
    }
}
