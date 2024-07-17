<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->comment('company id from company table');
            $table->unsignedBigInteger('application_id')->comment('Application id from loan application table');
            $table->string('status',255)->collation('utf8mb4_unicode_ci')->comment('satus for mail like Reject or Approved');
            $table->string('type',255)->collation('utf8mb4_unicode_ci')->comment('mail used for Credit or Loan');
            $table->timestamp('created_at')->useCurrent();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('application_id')->references('id')->on('loan_applications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_emails');
    }
}
