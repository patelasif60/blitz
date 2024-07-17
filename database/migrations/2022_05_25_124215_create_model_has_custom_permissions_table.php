<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelHasCustomPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_has_custom_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custom_permission_id')->comment('Custom permission id');;
            $table->string('model_type')->comment('Model - User or any other');;
            $table->string('model_id')->comment('User Id by Model');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('custom_permission_id')->references('id')->on('custom_permissions')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_custom_permissions');
    }
}
