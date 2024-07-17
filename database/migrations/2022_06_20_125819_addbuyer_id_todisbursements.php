<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddbuyerIdTodisbursements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disbursements', function (Blueprint $table) {
            $table->unsignedBigInteger('buyer_user_id')->after('order_id')->nullable(true)->comment('buyer user id');
            $table->foreign('buyer_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('disbursements', function (Blueprint $table) {
            $table->dropForeign(['buyer_user_id']);
            $table->dropColumn('buyer_user_id');
        });
    }
}
