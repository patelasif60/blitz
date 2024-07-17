<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelHasCustomPermissionsColumnForCustomRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('model_has_custom_permissions', function (Blueprint $table) {
            $table->json('custom_permissions')->nullable()->comment('Buyer any custom permissions by any modal')->after('model_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('model_has_custom_permissions', function (Blueprint $table) {
            Schema::dropColumns('model_has_custom_permissions',['custom_permissions']);
        });
    }
}
