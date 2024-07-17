<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyTypeAndPkpFileToSuppliersTable extends Migration
{
    /**
     * Run the migrations for add new column company_type and pkp_file in supplier table.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->integer('company_type')->after('npwp_file')->nullable(true)->comment('1 => pkp, 2 => non-pkp');
            $table->string('pkp_file',512)->after('company_type')->nullable(true);
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
            $table->dropColumn('company_type');
            $table->dropColumn('pkp_file');
        });
    }
}
