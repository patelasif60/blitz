<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangesColumnNullableCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('registrantion_NIB',512)->nullable(true)->change();
            $table->string('company_email',512)->nullable(true)->change();
            $table->string('company_phone',512)->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('registrantion_NIB',512)->nullable(false)->change();
            $table->string('company_email',512)->nullable(false)->change();
            $table->string('company_phone',512)->nullable(false)->change();
        });
    }
}
