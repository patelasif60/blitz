<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldUserTypeInviteBuyer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invite_buyer', function (Blueprint $table) {
            $table->integer('user_type')->after('role_id')->comment('1=>admin,2=>user,3=>supplier');
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
