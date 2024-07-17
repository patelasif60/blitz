<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname',255)->collation('utf8mb4_unicode_ci');
            $table->string('lastname',255)->collation('utf8mb4_unicode_ci');
            $table->string('email',255)->collation('utf8mb4_unicode_ci')->unique();
            $table->timestamp('email_verified_at', $precision = 0)->nullable(true);
            $table->string('mobile',255)->collation('utf8mb4_unicode_ci');
            $table->string('password',255)->collation('utf8mb4_unicode_ci');
            $table->string('profile_pic',512)->collation('utf8mb4_unicode_ci')->nullable(true);
            $table->unsignedBigInteger('role_id');
            $table->boolean('is_active')->default(0);
            $table->string('remember_token',100)->collation('utf8mb4_unicode_ci')->nullable(true);
            $table->boolean('is_delete')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
