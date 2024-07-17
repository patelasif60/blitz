<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldEmailToLoanApplicantSpauseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_applicant_spouses', function (Blueprint $table) {
            $table->string('email',255)->nullable(true)->after('last_name')->comment('Spouse email name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_applicant_spouses', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
}
