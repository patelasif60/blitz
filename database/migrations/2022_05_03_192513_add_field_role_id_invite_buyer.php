<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldRoleIdInviteBuyer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invite_buyer', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->after('user_id')->comment('2=>user,3=>supplier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invite_buyer', function (Blueprint $table) {
            //
        });
    }
}
