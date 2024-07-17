<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('registrantion_NIB',512)->after('logo');
            $table->string('web_site',512)->nullable(true)->after('registrantion_NIB');
            $table->string('company_email',512)->after('web_site');
            $table->string('company_phone',512)->after('company_email');
            $table->string('alternative_email',512)->nullable(true)->after('company_phone');
            $table->string('alternative_phone',512)->nullable(true)->after('alternative_email');
            $table->text('address')->after('alternative_phone');
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
            $table->dropColumn('registrantion_NIB');
            $table->dropColumn('web_site');
            $table->dropColumn('company_email');
            $table->dropColumn('company_phone');
            $table->dropColumn('alternative_email');
            $table->dropColumn('alternative_phone');
            $table->dropColumn('address');
        });
    }
}
