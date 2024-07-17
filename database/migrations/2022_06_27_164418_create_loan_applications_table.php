<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('loan_application_number',215)->nullable(true);
            $table->unsignedBigInteger('user_id')->comment('Applicant businesses user id');
            $table->unsignedBigInteger('loan_provider_id')->comment('foriegn key loan provider id');
            $table->text('provider_user_id')->nullable(true)->comment('Provider user id');
            $table->text('provider_application_id')->nullable(true)->comment('Provider application id');
            $table->unsignedBigInteger('applicant_id')->comment('loan_apllicants id as foreign key');
            $table->unsignedBigInteger('company_id')->comment('loan applications company id');
            $table->text('loan_limit')->collation('utf8mb4_unicode_ci')->comment('Loan limit');
            $table->text('senctioned_amount')->collation('utf8mb4_unicode_ci')->comment('Sanctioned Loan');
            $table->text('reserved_amount')->collation('utf8mb4_unicode_ci')->comment('Reserved Loan');
            $table->timestamp('expire_date')->nullable()->comment('Limit Expire date');
            $table->text('status')->collation('utf8mb4_unicode_ci')->nullable()->comment('Applicantion status');
            $table->timestamps();
            $table->softDeletes(); // this will create deleted_at field for softdelete

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('loan_provider_id')->references('id')->on('loan_providers')->onDelete('cascade');
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
        Schema::dropIfExists('loan_applications');
    }
}
