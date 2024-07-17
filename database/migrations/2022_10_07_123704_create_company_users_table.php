<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->comment('companies table');
            $table->unsignedBigInteger('designation')->nullable(true);
            $table->unsignedBigInteger('department')->nullable(true);
            $table->nullableMorphs('users');
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('designation')->references('id')->on('designations')->onDelete('cascade');
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
        Schema::dropIfExists('company_users');
    }
}
