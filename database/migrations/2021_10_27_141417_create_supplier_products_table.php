<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('supplier_id');
            $table->string('description',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->decimal('price', 10, 0);
            $table->integer('min_quantity');
            $table->unsignedBigInteger('quantity_unit_id')->nullable(true);
            $table->boolean('status')->default(1);
            $table->string('discount',215)->collation('utf8_unicode_ci')->nullable(true);
            $table->string('discounted_price',215)->collation('utf8_unicode_ci')->nullable(true);
            $table->string('product_ref',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->boolean('is_deleted')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('quantity_unit_id')->references('id')->on('units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_products');
    }
}
