<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldUploadedContractsLoanApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->string('status_name',50)->default('Pending')->after('status')->comment('Credit limit status value');
            $table->text('uploaded_contracts')->nullable(true)->after('status_name')->comment('Above 50m user upload contracts');
            $table->boolean('verify_otp')->default(0)->after('uploaded_contracts')->comment('if otp is verify then status is 1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropColumn('status_name');
            $table->dropColumn('uploaded_contracts');
            $table->dropColumn('verify_otp');
        });
    }
}
