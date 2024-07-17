<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldGoogleIdUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id',512)->collation('utf8mb4_unicode_ci')->nullable(true);
            $table->string('fb_id',512)->collation('utf8mb4_unicode_ci')->nullable(true);
            $table->string('linkedin_id',512)->collation('utf8mb4_unicode_ci')->nullable(true);
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
            //
        });
    }
}
