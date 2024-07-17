<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentProviderTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_provider_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableMorphs('users');
            $table->unsignedBigInteger('company_id')->nullable()->comment('companies tabel');
            $table->unsignedBigInteger('payment_provider_id')->nullable()->comment('payment_providers tabel');
            $table->tinyInteger('transaction_status')->nullable(true)->default(1)->comment('1=>Pending,2=>Failed,3=>Completed');
            $table->unsignedBigInteger('transaction_type_id')->nullable()->comment('transactions_types tabel');
            $table->text('credit_ac_id')->nullable()->comment('transactions_types tabel')->collation('utf8_unicode_ci');
            $table->text('credit_ac_type')->nullable()->collation('utf8_unicode_ci');
            $table->text('debit_ac_id')->nullable()->collation('utf8_unicode_ci');
            $table->text('debit_ac_type')->nullable()->collation('utf8_unicode_ci');
            $table->text('transfer_id')->nullable()->collation('utf8_unicode_ci');
            $table->text('source_id')->nullable()->collation('utf8_unicode_ci');
            $table->text('destination_id')->nullable()->collation('utf8_unicode_ci');
            $table->nullableMorphs('related');
            $table->text('amount')->nullable()->collation('utf8_unicode_ci');
            $table->json('response_by_provider')->nullable()->comment('Api response data');
            $table->nullableMorphs('created');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('company_id')->nullable()->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('payment_provider_id')->nullable()->references('id')->on('payment_providers')->onDelete('cascade');
            $table->foreign('transaction_type_id')->nullable()->references('id')->on('transactions_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_provider_transactions');
    }
}
