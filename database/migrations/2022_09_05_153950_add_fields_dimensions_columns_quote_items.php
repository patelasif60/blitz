<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsDimensionsColumnsQuoteItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quote_items', function (Blueprint $table) {
            $table->integer('length')->default(0)->after('dimensions')->nullable(true);
            $table->integer('width')->default(0)->after('length')->nullable(true);
            $table->integer('height')->default(0)->after('width')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_items', function (Blueprint $table) {
            $table->dropColumn('length');
            $table->dropColumn('width');
            $table->dropColumn('height');
        });
    }
}
