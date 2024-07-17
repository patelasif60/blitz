<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanApplicantBusinessAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_applicant_business_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('applicant_id')->comment('loan_apllicants id as foreign kry');
            $table->unsignedBigInteger('applicant_business_id')->comment('loan_apllicants id as foreign kry');
            $table->text('name')->comment('Applicant Business Branch Name');
            $table->text('address1')->collation('utf8mb4_unicode_ci')->comment('Applicant Business Address');
            $table->text('address2')->collation('utf8mb4_unicode_ci')->comment('Applicant Business Address');
            $table->text('postal_code')->collation('utf8mb4_unicode_ci')->comment('Applicant Business postal code');
            $table->text('sub_district')->collation('utf8mb4_unicode_ci')->comment('Applicant Business sub district');
            $table->text('district')->collation('utf8mb4_unicode_ci')->comment('Applicant Business sub district');
            $table->text('other_provinces')->nullable()->comment('other provinces applicant not belog to list');
            $table->unsignedBigInteger('city_id')->nullable()->comment('Applicant Business city ');
            $table->text('other_city')->nullable()->comment('Applicant Business other city which not belong to city list ');
            $table->unsignedBigInteger('provinces_id')->nullable()->comment('provinces applicant');
            $table->unsignedBigInteger('country_id')->nullable()->comment('Applicant country ');
            $table->timestamps();
            $table->softDeletes(); // this will create deleted_at field for softdelete

            $table->foreign('applicant_id')->references('id')->on('loan_applicants')->onDelete('cascade');
            $table->foreign('applicant_business_id')->references('id')->on('loan_applicant_businesses')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_applicant_business_addresses');
    }
}
