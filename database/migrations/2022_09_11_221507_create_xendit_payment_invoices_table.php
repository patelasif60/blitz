<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXenditPaymentInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xendit_payment_invoices', function (Blueprint $table) {
            $table->id();
            $table->morphs('related_to');
            $table->text('xendit_id')->nullable()->comment('Xendit usr id');
            $table->tinyInteger('invoice_type')->default(1)->comment('Xendit invoice type - 1 Single, 2 Recurring Payments, 3 Payouts');
            $table->text('payment_link')->comment('Xendit payment link');
            $table->timestamp('expiry_date')->comment('Payment link expiry date');
            $table->tinyInteger('status')->default(0)->comment('0 Pending, 1 Paid, 2 Fail, 3 Expired');
            $table->json('response')->nullable()->comment('Xendit response');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xendit_payment_invoices');
    }
}
