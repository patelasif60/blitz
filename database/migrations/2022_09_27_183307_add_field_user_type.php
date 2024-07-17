<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldUserType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_us', function (Blueprint $table) {
            $table->text('user_type')->after('message')->nullable(true);
            $table->text('designation')->after('user_type')->nullable(true);
            $table->text('company_size')->after('designation')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_us', function (Blueprint $table) {
            $table->dropColumn('user_type');
            $table->dropColumn('designation');
            $table->dropColumn('company_size');
        });
    }
}
