<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribed_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname',512)->collation('utf8_unicode_ci');
            $table->string('lastname',512)->collation('utf8_unicode_ci');
            $table->string('company_name',512)->collation('utf8_unicode_ci');
            $table->string('email',512)->collation('utf8_unicode_ci');
            $table->boolean('is_buyer')->default(0);
            $table->boolean('is_supplier')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscribed_users');
    }
}
