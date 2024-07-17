<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfn_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rfn_id')->nullable()->comment('Rfn id');
            $table->unsignedBigInteger('rfn_response_id')->nullable()->comment('Rfn responses id');
            $table->text('item_number_prefix')->comment('Rfn item prefix BRFN');
            $table->text('item_number')->comment('Rfn item number');
            $table->unsignedBigInteger('category_id')->default(0)->comment('Category id');
            $table->text('category_name')->nullable()->comment('Category name');
            $table->unsignedBigInteger('subcategory_id')->default(0)->comment('Subcategory id');
            $table->text('subcategory_name')->nullable()->comment('Subcategory name');
            $table->unsignedBigInteger('product_id')->nullable()->comment('Product id');
            $table->text('product_name')->nullable()->comment('Product name');
            $table->unsignedBigInteger('unit_id')->comment('Product id');
            $table->text('quantity')->nullable()->comment('Item quantity');
            $table->text('item_description')->nullable()->comment('Product description');
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
        Schema::dropIfExists('rfn_items');
    }
}
