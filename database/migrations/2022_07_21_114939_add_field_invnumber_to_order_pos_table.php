<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldInvnumberToOrderPosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_pos', function (Blueprint $table) {
            $table->string('inv_number',512)->collation('utf8mb4_unicode_ci')->nullable(true)->after('po_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_pos', function (Blueprint $table) {
            $table->dropColumn('inv_number');
        });
    }
}
