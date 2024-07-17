<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsCompanyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_users', function (Blueprint $table) {
            $table->unsignedBigInteger('user_type_id')->nullable(true)->after('users_id');
            $table->string('other_designation',512)->nullable(true)->after('user_type_id');
            $table->tinyInteger('portfolio_type')->nullable(true)->comment('1=Client, 2=Supplier')->after('other_designation');
            $table->tinyInteger('sector_type')->nullable(true)->comment('1=Primary, 2=Secondary, 3=Tertiary')->after('portfolio_type');
            $table->string('name',512)->nullable(true)->after('sector_type');
            $table->string('registration_NIB',512)->nullable(true)->after('name');
            $table->text('description')->nullable(true)->after('registration_NIB');
            $table->string('phone',50)->nullable(true)->after('description');
            $table->string('email',255)->nullable(true)->after('phone');
            $table->unsignedBigInteger('position')->default(0)->after('email');
            $table->string('image',512)->nullable(true)->after('position');

            $table->foreign('user_type_id')->references('id')->on('company_user_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_users', function (Blueprint $table) {
            $table->dropForeign(['user_type_id']);
            $table->dropColumn('other_designation');
            $table->dropColumn('portfolio_type');
            $table->dropColumn('sector_type');
            $table->dropColumn('name');
            $table->dropColumn('registration_NIB');
            $table->dropColumn('description');
            $table->dropColumn('phone');
            $table->dropColumn('email');
            $table->dropColumn('position');
            $table->dropColumn('photo');
        });
    }
}
