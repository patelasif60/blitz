<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdateFieldsSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->renameColumn('fax', 'company_alternative_phone_code');
            $table->string('company_alternative_phone',512)->after('fax')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->renameColumn('company_alternative_phone_code', 'fax');
            $table->dropColumn('company_alternative_phone');
        });
    }

}
