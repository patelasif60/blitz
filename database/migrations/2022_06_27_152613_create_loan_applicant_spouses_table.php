<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanApplicantSpousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_applicant_spouses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('applicant_id')->comment('loan_apllicants id as foreign key');
            $table->string('first_name')->collation('utf8mb4_unicode_ci')->comment('Applicant first name');
            $table->string('last_name')->collation('utf8mb4_unicode_ci')->comment('Applicant last name');
            $table->tinyInteger('relationship_with_borrower')->default(1)->comment('1=PARENT,2=SIBLING,3=SPOUSE,4=COLLEAGUE,5=PROFESSIONAL,6=OTHER');
            $table->string('ktp_nik')->collation('utf8mb4_unicode_ci')->comment('Applicant spouse ktp number');
            $table->text('ktp_image')->collation('utf8mb4_unicode_ci')->comment('Applicant spouse  identityKtp image');
            $table->string('phone_code',6)->collation('utf8mb4_unicode_ci')->nullable()->comment('Applicant phone number');
            $table->string('phone_number')->collation('utf8mb4_unicode_ci')->nullable()->comment('Applicant phone number');
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
        Schema::dropIfExists('loan_applicant_spouses');
    }
}
