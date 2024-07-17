<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldCompanyIdToBuyerBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buyer_banks', function (Blueprint $table) {
            $table->json('is_user_primary')->nullable()->comment('User id of set primary bank.')->after('is_primary');
            $table->string('user_type')->nullable()->comment('Model - User or any other.')->after('user_id');
            $table->unsignedBigInteger('company_id')->after('user_id')->nullable()->comment('Companies table id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buyer_banks', function (Blueprint $table) {
            Schema::dropColumns('buyer_banks',['company_id','user_type','is_user_primary']);
        });
    }
}
