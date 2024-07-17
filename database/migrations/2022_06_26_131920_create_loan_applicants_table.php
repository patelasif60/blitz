<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_applicants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('primary key of table');
            $table->unsignedBigInteger('company_id')->comment('company id from company able');
            $table->unsignedBigInteger('loan_provider_id')->comment('foriegn key loan provider id');
            $table->string('first_name',255)->collation('utf8mb4_unicode_ci')->comment('Applicant first name');
            $table->string('last_name',255)->collation('utf8mb4_unicode_ci')->comment('Applicant last name');
            $table->string('email',255)->collation('utf8mb4_unicode_ci')->comment('Applicant email name');
            $table->string('phone_code',6)->comment('Applicant phone code');
            $table->string('phone_number',255)->collation('utf8mb4_unicode_ci')->comment('Applicant phone number');
            $table->string('ktp_nik',255)->collation('utf8mb4_unicode_ci')->comment('Applicant identity unique number');
            $table->text('ktp_image')->collation('utf8mb4_unicode_ci')->comment('Applicant identityKtp image');
            $table->text('ktp_with_selfie_image')->collation('utf8mb4_unicode_ci')->comment('Applicantdelfie image path');
            $table->text('family_card_image')->comment('Applicant family card image path');
            $table->tinyInteger('gender')->default(1)->comment('1=Male,2=Female');
            $table->string('place_of_birth',512)->collation('utf8mb4_unicode_ci')->nullable(true)->comment('Applicant Place of birth ');
            $table->date('date_of_birth')->collation('utf8mb4_unicode_ci')->nullable(true)->comment('Date of birth applicant');
            $table->tinyInteger('marital_status')->default(1)->comment('1=KAWIN,2=BELUM KAWIN,3=CERAI MATI,4=CERAI HIDUP');
            $table->tinyInteger('religion')->default(1)->comment('1=ISLAM,2=KATHOLIK,3=KRISTEN,4=BUDHA,5=HINDU,6=KONGHUCHU,7=OTHER');
            $table->string('education',200)->nullable(true)->comment('Applicant education');
            $table->string('occupation',200)->nullable(true)->comment('Applicant occupation');
            $table->double('total_other_income')->default(0)->comment('Applicant total income');
            $table->tinyInteger('other_source_of_income')->default(0)->comment('1=BUSINESS REVENUE,2=FUND REVENUE,3=INHERIRTANCE,4=SALARY,5=PARENT/GUARDIAN');
            $table->double('net_salary')->default(0)->comment('Net salary of applicant');
            $table->text('my_position')->comment('applicant position');
            $table->timestamp('first_account_created_at')->nullable(true);
            $table->text('contracts')->nullable(true)->comment('contracts');
            $table->softDeletes(); // this will create deleted_at field for softdelete
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
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
        Schema::dropIfExists('loan_applicants');
    }
}
