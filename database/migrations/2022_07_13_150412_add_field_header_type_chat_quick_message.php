<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldHeaderTypeChatQuickMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_quick_message', function (Blueprint $table) {
            $table->string('header_type',255)->after('status')->comment('Quickmessage header type')->collation('utf8mb4_unicode_ci');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_quick_message', function (Blueprint $table) {
            //
        });
    }
}
