<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_applies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('loan_provider_id')->comment('loan_providers table');
            $table->string('loan_number',512)->collation('utf8_unicode_ci');
            $table->unsignedBigInteger('user_id')->comment('users table');
            $table->text('provider_user_id')->collation('utf8_unicode_ci')->nullable(true);
            $table->unsignedBigInteger('applicant_id')->comment('loan_applicants table');
            $table->unsignedBigInteger('application_id')->comment('loan_applications table');
            $table->unsignedBigInteger('company_id')->comment('companies table');
            $table->unsignedBigInteger('order_id')->comment('orders table')->nullable(true);
            $table->unsignedBigInteger('quote_id')->comment('quotes table');
            $table->text('provider_loan_id')->collation('utf8_unicode_ci')->nullable(true);
            $table->text('loan_amount')->collation('utf8_unicode_ci')->nullable(true);
            $table->text('loan_confirm_amount')->collation('utf8_unicode_ci')->nullable(true);
            $table->text('loan_repay_amount')->collation('utf8_unicode_ci')->nullable(true);
            $table->text('interest')->collation('utf8_unicode_ci')->nullable(true);
            $table->text('additional_amount')->collation('utf8_unicode_ci')->nullable(true);
            $table->text('paid_amount')->collation('utf8_unicode_ci')->nullable(true);
            $table->tinyInteger('disbursed_to_supplier')->default(0)->comment('0=>no,1=>yes');
            $table->tinyInteger('disbursed_to_koinworks')->default(0)->comment('0=>no,1=>yes');
            $table->dateTime('disbursement_date',  0)->nullable(true)->comment('Loan disbursed by koinworks date');
            $table->dateTime('due_date',  0)->nullable(true);
            $table->integer('tenure_days')->comment('Tenure days');
            $table->text('description')->nullable(true);
            $table->unsignedBigInteger('status_id')->nullable(true)->comment('loan_statuses table');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('loan_provider_id')->references('id')->on('loan_providers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('applicant_id')->references('id')->on('loan_applicants')->onDelete('cascade');
            $table->foreign('application_id')->references('id')->on('loan_applications')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('loan_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_applies');
    }
}
