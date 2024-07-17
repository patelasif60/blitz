<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldOwnerUserToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->after('approval_process')->nullable()->comment('User table id - who create the entry');
            $table->unsignedBigInteger('updated_by')->after('approval_process')->nullable()->comment('User table id - who update the entry');
            $table->unsignedBigInteger('owner_user')->after('approval_process')->nullable()->comment('User table id - who owned the company');            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('owner_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            Schema::dropColumns('companies',['created_by', 'updated_by', 'owner_user']);
        });
    }
}
