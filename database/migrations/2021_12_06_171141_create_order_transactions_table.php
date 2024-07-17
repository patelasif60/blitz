<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_transactions', function (Blueprint $table) {
            $table->collation = 'utf8mb4_bin';
            $table->bigIncrements('id');
            $table->string('invoice_id',50);
            $table->unsignedBigInteger('order_id');
            $table->string('external_id',512);
            $table->string('user_id',50);
            $table->string('status',15);
            $table->string('merchant_name',512)->nullable(true);
            $table->text('merchant_profile_picture_url')->nullable(true);
            $table->double('amount');
            $table->string('payer_email',254);
            $table->timestamp('expiry_date')->nullable(true);
            $table->text('invoice_url');
            $table->boolean('should_send_email')->nullable(true);
            $table->text('success_redirect_url')->nullable(true);
            $table->text('failure_redirect_url')->nullable(true);
            $table->timestamp('created')->nullable(true);
            $table->timestamp('updated')->nullable(true);
            $table->string('currency',25);
            $table->mediumText('items')->nullable(true);
            $table->mediumText('customer')->nullable(true);
            $table->string('payment_destination',50)->nullable(true);
            $table->string('bank_code',25)->nullable(true);
            $table->double('paid_amount')->nullable(0);
            $table->double('initial_amount')->nullable(0);
            $table->double('fees_paid_amount')->nullable(0);
            $table->double('adjusted_received_amount')->nullable(0);
            $table->string('payment_method',50)->nullable(true);
            $table->string('payment_channel',50)->nullable(true);
            $table->timestamp('paid_at')->nullable(true);
            $table->string('credit_card_charge_id',50)->nullable(true);
            $table->text('description')->nullable(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_transactions');
    }
}
