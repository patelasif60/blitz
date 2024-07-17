<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsDatatypeQuincusOrderTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quincus_order_tracking', function (Blueprint $table) {
            $table->text('process_status')->change();
            $table->text('quincus_status_description')->change();
            $table->text('quincus_status_stage')->change();
            $table->text('process_location')->change();
            $table->text('process_signature')->change();
            $table->text('process_photo')->change();
            $table->text('process_maps_location')->change();
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
            $table->dropColumn('process_status');
            $table->dropColumn('quincus_status_description');
            $table->dropColumn('quincus_status_stage');
            $table->dropColumn('process_location');
            $table->dropColumn('process_signature');
            $table->dropColumn('process_photo');
            $table->dropColumn('process_maps_location');
        });
    }
}
