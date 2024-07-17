<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelatedPermissionPermissionGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions_groups', function (Blueprint $table) {
            $table->json('related_permissions')->nullable()->comment('Dependency permission json')->after('permissions');
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
            $table->dropColumn('related_permissions');
        });
    }
}
