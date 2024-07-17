<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_roles', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable()->comment('Custom role name');
            $table->json('permissions')->nullable()->comment('Role permissions');
            $table->text('guard')->nullable()->comment('Guard name');
            $table->morphs('model');
            $table->unsignedBigInteger('system_role_id')->nullable()->comment('System role table id');
            $table->unsignedBigInteger('company_id')->nullable()->comment('Company id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('system_role_id')->references('id')->on('system_roles')->onDelete('cascade');
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
        Schema::dropIfExists('custom_roles');
    }
}
