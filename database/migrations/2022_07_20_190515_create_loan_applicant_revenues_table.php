<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanApplicantRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_applicant_revenues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('applicant_id')->comment('loan apllicant id as foreign key');
            $table->unsignedBigInteger('company_id')->comment('company id from company able');
            $table->date('monthly_date')->nullable(true)->comment('Date of applicant revenue');
            $table->double('revenue')->default(0)->comment('applicant revenue');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('applicant_id')->references('id')->on('loan_applicants')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_applicant_revenues');
    }
}
