<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOtherCompanyDetailsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_other_information', function (Blueprint $table) {
            $table->integer('type')->nullable()->change();
            $table->text('number_of_employee')->nullable()->change();
            $table->text('average_sales')->nullable()->change();
            $table->unsignedBigInteger('category')->nullable()->change();
            $table->text('ownership_percentage')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_other_information', function (Blueprint $table) {
            //
        });
    }
}
