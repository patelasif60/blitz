<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions_groups', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable()->comment('Permission Group name');
            $table->text('display_name')->nullable()->comment('Permission Group display name');
            $table->text('class_name')->nullable()->comment('Applied CSS Class name');
            $table->boolean('is_main')->default(0)->comment('If Group is main group 1 or 0');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('Permission parent group id');
            $table->text('level')->nullable()->comment('Group level 1/2/3/4');
            $table->text('sort')->nullable()->comment('Group sorting by level');
            $table->json('permissions')->nullable()->comment('Assigned Permissions ids json - Permission table');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parent_id')->references('id')->on('permissions_groups')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions_groups');
    }
}
