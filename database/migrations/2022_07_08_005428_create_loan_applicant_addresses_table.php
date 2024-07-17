<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanApplicantAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_applicant_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_id')->comment('loan_apllicants id as foreign kry');
            $table->text('name')->comment('Applicant Address Name');
            $table->text('address_line1')->collation('utf8mb4_unicode_ci')->comment('Applicant Address');
            $table->text('address_line2')->collation('utf8mb4_unicode_ci')->comment('Applicant Address');
            $table->text('postal_code',10)->collation('utf8mb4_unicode_ci')->comment('Applicant postal code');
            $table->text('sub_district',255)->collation('utf8mb4_unicode_ci')->comment('Applicant sub district');
            $table->text('district')->collation('utf8mb4_unicode_ci')->comment('Applicant sub district');
            $table->text('other_provinces')->nullable(true)->comment('other provinces applicant not belog to list');
            $table->unsignedBigInteger('city_id')->nullable(true)->comment('Applicant city ');
            $table->text('other_city')->nullable(true)->comment('Applicant other city which not belong to city list ');
            $table->unsignedBigInteger('provinces_id')->nullable(true)->comment('provinces applicant');
            $table->unsignedBigInteger('country_id')->nullable(true)->comment('Applicant country');
            $table->boolean('has_live_here')->default(1)->comment('1=yes,2=no');
            $table->integer('home_ownership_status')->default(1)->comment('1=FAMILY/KELUARGA,2=PARENT/ORANG TUA,3=RENTAL/KOS,4=OWNED/MILIK SENDIRI,5=OFFICE RESIDENCE/RUMAH DINAS');
            $table->text('duration_of_stay')->nullable(true)->comment('Applicant house statyong durtion on address');
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
        Schema::dropIfExists('loan_applicant_addresses');
    }
}
