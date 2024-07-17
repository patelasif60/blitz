<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilesToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('tax_receipt')->after('otp_supplier')->nullable(true);
            $table->text('order_latter')->after('tax_receipt')->nullable(true);
            $table->text('invoice')->after('order_latter')->nullable(true);
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
            $table->dropColumn('tax_receipt');
            $table->dropColumn('order_latter');
            $table->dropColumn('invoice');
        });
    }
}
