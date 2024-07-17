<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('model');
            $table->unsignedBigInteger('company_id')->nullable(true);
            $table->unsignedBigInteger('user_id')->nullable(true);
            $table->unsignedBigInteger('company_user_type_id')->nullable(true);
            $table->string('salutation',255)->nullable(true);
            $table->string('firstname',512)->nullable(true);
            $table->string('lastname',512)->nullable(true);
            $table->string('email',512)->nullable(true);
            $table->string('country_phone_code',6)->nullable(true);
            $table->string('phone',512)->nullable(true);
            $table->string('designation',512)->nullable(true);
            $table->string('position',512)->nullable(true);
            $table->string('company_name',512)->nullable(true);
            $table->tinyInteger('sector')->nullable(true)->comment('1=Primary, 2=Secondary, 3=Tertiary');
            $table->string('registration_NIB',512)->nullable(true);
            $table->tinyInteger('portfolio_type')->nullable(true)->comment('1=Client, 2=Supplier');
            $table->text('quote')->nullable(true);
            $table->text('image')->nullable(true);
            $table->text('description')->nullable(true);
            $table->boolean('is_deleted')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('company_user_type_id')->references('id')->on('company_user_type');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_members');
    }
}
