<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToOrdersTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('items_manage_separately')->comment('order items manage separately')->after('order_status')->default(false);
            $table->unsignedBigInteger('rfq_id')->after('quote_id')->nullable(true);
            $table->unsignedBigInteger('supplier_id')->after('rfq_id')->nullable(true);
            $table->foreign('rfq_id')->references('id')->on('rfqs')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('items_manage_separately');
            $table->dropForeign(['rfq_id']);
            $table->dropColumn('rfq_id');
            $table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');
        });
    }
}
