<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_repayments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('applicant_id')->comment('loan_applicants tabel');
            $table->unsignedBigInteger('application_id')->comment('loan_applications tabel');
            $table->unsignedBigInteger('loan_apply_id')->comment('loan_applies tabel');
            $table->morphs('users');
            $table->unsignedBigInteger('company_id')->comment('companies tabel');
            $table->unsignedBigInteger('order_id')->comment('orders tabel');
            $table->text('payment_amount')->collation('utf8_unicode_ci')->nullable(true);
            $table->text('interest_paid')->collation('utf8_unicode_ci')->nullable(true);
            $table->text('principal_amount')->collation('utf8_unicode_ci')->nullable(true);
            $table->text('other_charges_amount')->collation('utf8_unicode_ci')->nullable(true);
            $table->text('remaining_amount')->collation('utf8_unicode_ci')->nullable(true);
            $table->unsignedBigInteger('paid_by')->nullable(true)->comment('users tabel');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('applicant_id')->references('id')->on('loan_applicants')->onDelete('cascade');
            $table->foreign('application_id')->references('id')->on('loan_applications')->onDelete('cascade');
            $table->foreign('loan_apply_id')->references('id')->on('loan_applies')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('paid_by')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_repayments');
    }
}
