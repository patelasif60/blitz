<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanApplicantBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_applicant_businesses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('applicant_id')->references('id')->on('loan_apllicants')->comment('loan_apllicants id as foreign kry');
            $table->text('name')->collation('utf8mb4_unicode_ci')->comment('Applicant businesses Name');
            $table->integer('type')->default(1)->comment('1=individual,2=pt,3=cv');
            $table->text('description')->nullable(true)->collation('utf8mb4_unicode_ci')->comment('Applicant description');
            $table->text('website')->collation('utf8mb4_unicode_ci')->comment('website  of applicant businesses ');
            $table->text('email')->collation('utf8mb4_unicode_ci')->comment('email of applicant businesses ');
            $table->text('phone_code',6)->collation('utf8mb4_unicode_ci')->nullable()->comment('Applicant phone number');
            $table->text('phone_number')->collation('utf8mb4_unicode_ci')->nullable()->comment('Applicant businesses phone number');
            $table->text('owner_first_name')->collation('utf8mb4_unicode_ci')->comment('Applicant businesses owner full name');
            $table->text('owner_last_name')->collation('utf8mb4_unicode_ci')->comment('Applicant businesses owner full name');
            $table->text('npwp_image')->collation('utf8mb4_unicode_ci')->comment('Applicant businesses image');
            $table->text('average_sales')->collation('utf8mb4_unicode_ci')->comment('Average sales number of applicant businesses ');
            $table->timestamp('establish_in')->collation('utf8mb4_unicode_ci')->comment('businesses establishment year');
            $table->text('number_of_employee')->collation('utf8mb4_unicode_ci')->comment('Number of employye in businesses ');
            $table->text('bank_statement_image')->collation('utf8mb4_unicode_ci')->comment('phone area  of applicant spose ');
            $table->text('ownership_percentage')->collation('utf8mb4_unicode_ci')->comment('ownership percentage ');
            $table->unsignedBigInteger('category')->default(1)->comment('businesses category ');
            $table->unsignedBigInteger('my_position')->default(1)->collation('utf8mb4_unicode_ci')->comment('1=DIRECTOR,2=VICE DIRECTOR,3=MANAGER,4=COMMISIONER');
            $table->text('siup_number')->collation('utf8mb4_unicode_ci')->comment('SIUP number');
            $table->text('license_image')->collation('utf8mb4_unicode_ci')->comment('businesses license image ');
            $table->timestamps();
            $table->softDeletes(); // this will create deleted_at field for softdelete

            $table->foreign('applicant_id')->references('id')->on('loan_applicants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_applicant_businesses');
    }
}
