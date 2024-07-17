<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneCodeToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('c_phone_code',6)->comment('company phone code')->after('company_email')->nullable(true)->default('');
            $table->string('a_phone_code',6)->after('alternative_email')->comment('alternative phone code')->nullable(true)->default('');
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
            $table->dropColumn('c_phone_code');
            $table->dropColumn('a_phone_code');
        });
    }
}
