<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('quote_id');
            $table->string('order_number',215)->collation('utf8_unicode_ci')->nullable(true);
            $table->string('payment_amount',215)->collation('utf8mb4_unicode_ci');
            $table->boolean('payment_status')->default(0);
            $table->dateTime('payment_date',  0)->nullable(true);
            $table->date('min_delivery_date');
            $table->date('max_delivery_date');
            $table->string('address_name',512)->collation('utf8mb4_unicode_ci');
            $table->string('address_line_1',512)->collation('utf8mb4_unicode_ci');
            $table->string('address_line_2',512)->collation('utf8mb4_unicode_ci');
            $table->string('city',215)->collation('utf8mb4_unicode_ci');
            $table->string('pincode',215)->collation('utf8mb4_unicode_ci');
            $table->string('state',215)->collation('utf8mb4_unicode_ci');
            $table->unsignedBigInteger('order_status')->default(1);
            $table->string('otp_supplier',10)->collation('utf8mb4_unicode_ci');
            $table->boolean('is_deleted')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('cascade');
            $table->foreign('order_status')->references('id')->on('order_status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
