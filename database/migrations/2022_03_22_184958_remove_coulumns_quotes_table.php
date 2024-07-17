<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCoulumnsQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            /*$table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');*/
            // $table->dropForeign(['product_id']);
            // $table->dropColumn('product_id');
            // $table->dropColumn('product_price_per_unit');
            // $table->dropColumn('product_quantity');
            // $table->dropColumn('price_unit');
            // $table->dropColumn('min_delivery_days');
            // $table->dropColumn('max_delivery_days');
            // $table->dropColumn('product_amount');
            // $table->dropColumn('logistic_check');
            // $table->dropColumn('logistic_provided');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            //
        });
    }
}
