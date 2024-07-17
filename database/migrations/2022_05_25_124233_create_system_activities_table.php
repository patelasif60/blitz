<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('system_logable_id')->comment('For record id of affected model');
            $table->string('system_logable_type')->comment('For model name App/Models/User');
            $table->bigInteger('user_id')->nullable()->comment('Current user id');
            $table->string('guard_name')->comment('Guard name');
            $table->string('module_name')->comment('Module name');
            $table->string('action')->comment('Action:- Create/Update/Delete/View');
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->string('ip_address')->comment('User IP address');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_activities');
    }
}
