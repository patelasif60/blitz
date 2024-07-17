<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFieldsCompanyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_details', function (Blueprint $table) {
            $table->string('net_income_currency',512)->after('number_of_employee')->default('Rp')->nullable(true);
            $table->string('annual_sales_currency',512)->after('net_income')->default('Rp')->nullable(true);
            $table->string('financial_target_currency',512)->after('annual_sales')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_details', function (Blueprint $table) {
            $table->dropColumn('net_income_currency');
            $table->dropColumn('annual_sales_currency');
            $table->dropColumn('financial_target_currency');
        });
    }
}
