<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToOrderTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('bulk_payment_id')->after('external_id')->nullable(true);
            $table->unsignedBigInteger('order_id')->nullable(true)->change();
            $table->foreign('bulk_payment_id')->references('id')->on('bulk_payments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_transactions', function (Blueprint $table) {
            $table->dropForeign(['bulk_payment_id']);
            $table->dropColumn('bulk_payment_id');
            $table->unsignedBigInteger('order_id')->nullable(false)->change();
        });
    }
}
