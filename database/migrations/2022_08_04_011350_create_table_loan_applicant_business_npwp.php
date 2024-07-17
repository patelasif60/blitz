<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLoanApplicantBusinessNpwp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_applicant_businesses', function (Blueprint $table) {
            $table->text('npwp_number')->nullable()->comment('NPWP number')->after('owner_last_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_applicant_businesses', function (Blueprint $table) {
            $table->dropColumn('npwp_number');
        });
    }
}
