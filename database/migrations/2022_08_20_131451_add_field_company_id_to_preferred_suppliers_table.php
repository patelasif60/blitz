<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldCompanyIdToPreferredSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preferred_suppliers', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->after('user_id')->nullable()->comment('Companies table id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('preferred_suppliers', function (Blueprint $table) {
            Schema::dropColumns('preferred_suppliers',['company_id']);
        });
    }
}
