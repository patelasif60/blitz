<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldSupplierAmountColumnQuotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->integer('supplier_final_amount')->after('logistic_provided')->nullable(true)->default(0);
            $table->integer('supplier_tex_value')->after('supplier_final_amount')->nullable(true)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('supplier_final_amount');
            $table->dropColumn('supplier_tex_value');
        });
    }
}
