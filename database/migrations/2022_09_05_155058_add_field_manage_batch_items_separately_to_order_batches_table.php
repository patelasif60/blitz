<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldManageBatchItemsSeparatelyToOrderBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_batches', function (Blueprint $table) {
            $table->boolean('batch_item_manage_separately')->comment('batch item manage separately')->after('order_item_ids')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_batches', function (Blueprint $table) {
            $table->dropColumn('batch_item_manage_separately');
        });
    }
}
