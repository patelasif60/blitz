<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldCountryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_addresses', function (Blueprint $table) {
            $table->integer('country_id')->after('country_one_id')->nullable(true);
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->integer('country_id')->after('state_id')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_addresses', function (Blueprint $table) {
            $table->dropColumn('country_id');
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('country_id');
        });
    }
}
