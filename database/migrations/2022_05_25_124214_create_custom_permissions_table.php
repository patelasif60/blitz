<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Custom permission name');
            $table->string('model_type')->comment('Model - Category or any other');
            $table->longText('value')->comment('Custom permission value (table id)');
            $table->string('guard_name')->comment('Custom permission guard name');
            $table->unsignedBigInteger('system_role_id')->comment('System role Id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('system_role_id')->references('id')->on('system_roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_permissions');
    }
}
