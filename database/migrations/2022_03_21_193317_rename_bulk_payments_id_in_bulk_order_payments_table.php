<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameBulkPaymentsIdInBulkOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bulk_order_payments', function (Blueprint $table) {
            $table->renameColumn('bulk_payments_id', 'bulk_payment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bulk_order_payments', function (Blueprint $table) {
            $table->renameColumn('bulk_payment_id', 'bulk_payments_id');
        });
    }
}
