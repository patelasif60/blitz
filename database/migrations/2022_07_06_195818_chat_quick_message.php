<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChatQuickMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('chat_quick_message', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('message')->nullable()->comment('Quickmessage content');
            $table->unsignedBigInteger('role_id')->comment('User Role id');
            $table->tinyInteger('status')->default(1)->comment('Quickmessage status');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
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
        Schema::dropIfExists('chat_quick_message');
    }
}
