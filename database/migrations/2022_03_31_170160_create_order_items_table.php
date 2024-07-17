<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_item_number',215)->nullable(true);
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('quote_item_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('rfq_product_id');
            $table->unsignedBigInteger('order_item_status_id')->nullable(true);
            $table->double('product_amount');
            $table->date('min_delivery_date');
            $table->date('max_delivery_date');
            $table->text('order_latter')->nullable(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('rfq_product_id')->references('id')->on('rfq_products')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('quote_item_id')->references('id')->on('quote_items')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('order_item_status_id')->references('id')->on('order_item_status')->onDelete('cascade');
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
        Schema::dropIfExists('order_items');
    }
}
