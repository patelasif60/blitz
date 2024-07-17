<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangesColumnNullableUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname',255)->nullable(true)->change();
            $table->string('lastname',255)->nullable(true)->change();
            $table->string('email',255)->nullable(true)->change();
            $table->string('mobile',255)->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname',255)->nullable(false)->change();
            $table->string('lastname',255)->nullable(false)->change();
            $table->string('email',255)->nullable(false)->change();
            $table->string('mobile',255)->nullable(false)->change();
        });
    }
}
