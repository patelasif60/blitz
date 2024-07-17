<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuoteItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rfq_product_id')->nullable(true);
            $table->string('quote_item_number')->nullable(true);
            $table->unsignedBigInteger('quote_id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('product_id');
            $table->double('product_price_per_unit');
            $table->bigInteger('product_quantity');
            $table->double('price_unit');
            $table->double('product_amount');
            $table->bigInteger('min_delivery_days')->nullable(true);
            $table->bigInteger('max_delivery_days')->nullable(true);
            $table->double('supplier_final_amount');
            $table->double('supplier_tex_value');
            $table->boolean('logistic_check')->nullable(true);
            $table->boolean('logistic_provided')->nullable(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('rfq_product_id')->references('id')->on('rfq_products')->onDelete('cascade');
            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('quote_items');
    }
}
