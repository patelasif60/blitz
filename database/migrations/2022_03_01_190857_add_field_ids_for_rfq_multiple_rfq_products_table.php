<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldIdsForRfqMultipleRfqProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfq_products', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->after('category');
            $table->unsignedBigInteger('sub_category_id')->after('sub_category')->nullable(true);
            $table->unsignedBigInteger('product_id')->after('product')->nullable(true);
            $table->string('rfq_product_item_number')->after('rfq_id')->nullable(true);
            /*$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rfq_products', function (Blueprint $table) {
          /*  $table->dropForeign(['category_id', 'sub_category_id', 'product_id']);*/
            $table->dropColumn('category_id');
            $table->dropColumn('sub_category_id');
            $table->dropColumn('product_id');
            $table->dropColumn('rfq_product_item_number');
        });
    }
}
