<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToSupplierTransactionChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_transaction_charges', function (Blueprint $table) {
            $table->unsignedBigInteger('xen_transfer_id')->nullable(true)->after('disbursement_id');
            $table->foreign('xen_transfer_id')->references('id')->on('xen_balance_transfers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_transaction_charges', function (Blueprint $table) {
            $table->dropForeign(['xen_transfer_id']);
            $table->dropColumn('xen_transfer_id');
        });
    }
}
