<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStatusDescriptionColumnQuincusOrderTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quincus_order_tracking', function (Blueprint $table) {
            $table->text('quincus_status_description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quincus_order_tracking', function (Blueprint $table) {
            $table->dropColumn('quincus_status_description');
        });
    }
}
