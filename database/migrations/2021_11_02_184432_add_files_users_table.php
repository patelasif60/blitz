<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilesUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('language_id')->after('role_id')->nullable(true);
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            $table->unsignedBigInteger('currency_id')->after('language_id')->nullable(true);
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->unsignedBigInteger('designation')->after('remember_token')->nullable(true);
            $table->foreign('designation')->references('id')->on('designations')->onDelete('cascade');
            $table->unsignedBigInteger('department')->after('designation')->nullable(true);
            $table->foreign('department')->references('id')->on('departments')->onDelete('cascade');
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
            $table->dropForeign(['language_id']);
            $table->dropColumn('language_id');
            $table->dropForeign(['currency_id']);
            $table->dropColumn('currency_id');
            $table->dropForeign(['designation']);
            $table->dropColumn('designation');
            $table->dropForeign(['department']);
            $table->dropColumn('department');
        });
    }
}
