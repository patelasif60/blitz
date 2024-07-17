<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldSystemDefaultRoleColumnCustomRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_roles', function (Blueprint $table) {
            $table->boolean('system_default_role')->after('company_id')->comment('Admin Custom Role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_roles', function (Blueprint $table) {
            $table->dropColumn('system_default_role');
        });
    }
}
