<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFirstnameAndLastnameFromSubscribedUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscribed_users', function (Blueprint $table) {
            $table->dropColumn('firstname');
            $table->dropColumn('lastname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscribed_users', function (Blueprint $table) {
            //
        });
    }
}
