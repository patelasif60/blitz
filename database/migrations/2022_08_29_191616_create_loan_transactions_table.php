<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('loan_id')->nullable(true)->comment('loan_applies table');
            $table->unsignedBigInteger('order_id')->nullable(true)->comment('orders table');
            $table->unsignedBigInteger('applicant_id')->comment('loan_applicants table');
            $table->unsignedBigInteger('application_id')->comment('loan_applications table');
            $table->morphs('users');
            $table->unsignedBigInteger('company_id')->comment('companies table');
            $table->text('transaction_reference_id')->collation('utf8_unicode_ci')->nullable(true);
            $table->text('transaction_amount')->collation('utf8_unicode_ci')->nullable(true);
            $table->string('transaction_proof',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->unsignedBigInteger('transaction_status')->comment('loan_statuses table');
            $table->text('remarks')->nullable(true);
            $table->tinyInteger('transaction_ac_type')->collation('utf8_unicode_ci')->nullable(true)->comment('0 -> debit, 1- credit');
            $table->unsignedBigInteger('transaction_type_id')->comment('transactions_types table');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('loan_id')->references('id')->on('loan_applies')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('applicant_id')->references('id')->on('loan_applicants')->onDelete('cascade');
            $table->foreign('application_id')->references('id')->on('loan_applications')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('transaction_status')->references('id')->on('loan_statuses')->onDelete('cascade');
            $table->foreign('transaction_type_id')->references('id')->on('transactions_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_transactions');
    }
}
