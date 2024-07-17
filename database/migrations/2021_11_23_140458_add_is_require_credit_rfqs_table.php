<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsRequireCreditRfqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfqs', function (Blueprint $table) {
            $table->boolean('is_require_credit')->after('unloading_services')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rfqs', function (Blueprint $table) {
            $table->dropColumn('is_require_credit');
        });
    }
}
