<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldSubCategoryIdSupplierDealWithCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_deal_with_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_category_id')->after('category_id')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_deal_with_categories', function (Blueprint $table) {
            Schema::dropIfExists('sub_category_id');
        });
    }
}
