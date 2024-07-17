<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeightDimensionQuoteItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quote_items', function (Blueprint $table) {
            $table->text('weights')->after('certificate')->nullable(true);
            $table->text('dimensions')->after('weights')->nullable(true);
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
            $table->dropColumn('weights');
            $table->dropColumn('dimensions');
        });
    }
}
