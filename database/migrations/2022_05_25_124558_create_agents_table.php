<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('User id');
            $table->string('first_name')->nullable()->comment('Agent first name');
            $table->string('last_name')->nullable()->comment('Agent last name');
            $table->string('email')->nullable()->comment('Agent email');
            $table->string('phone_code')->nullable()->comment('Agent phone code');
            $table->string('mobile')->nullable()->comment('Agent mobile');
            $table->string('profile_pic')->nullable()->comment('Agent profile pic');
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
        Schema::dropIfExists('agents');
    }
}
