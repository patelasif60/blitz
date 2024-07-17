<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserColumnForCustomRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('default_company')->nullable()->comment('Buyer default company id')->after('is_active');
            $table->boolean('buyer_admin')->default(0)->comment('Buyer is admin of buyer side')->after('default_company');

            $table->foreign('default_company')->references('id')->on('companies')->onDelete('cascade');

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
            Schema::dropColumns('users',['default_company','buyer_admin']);
        });
    }
}
