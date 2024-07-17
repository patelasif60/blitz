<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldIsActiveToPermissionsGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions_groups', function (Blueprint $table) {
            $table->boolean('is_active')->default(1)->nullable()->after('permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions_groups', function (Blueprint $table) {
            Schema::dropColumns('permissions_groups',['is_active']);
        });
    }
}
