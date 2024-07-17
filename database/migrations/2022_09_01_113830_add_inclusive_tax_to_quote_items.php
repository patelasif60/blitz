<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInclusiveTaxToQuoteItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quote_items', function (Blueprint $table) {
            $table->boolean('inclusive_tax_other')->after('logistic_provided')->default(0);
            $table->boolean('inclusive_tax_logistic')->after('inclusive_tax_other')->default(0);
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
            $table->dropColumn('inclusive_tax_other');
            $table->dropColumn('inclusive_tax_logistic');
        });
    }
}
