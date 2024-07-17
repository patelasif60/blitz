<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('model');
            $table->unsignedBigInteger('company_id')->nullable(true);
            $table->unsignedBigInteger('user_id')->nullable(true);
            $table->string('founders',512)->nullable(true);
            $table->string('name',512)->nullable(true);
            $table->string('headquarters',512)->nullable(true);
            $table->tinyInteger('sector')->nullable(true)->comment('1=Primary, 2=Secondary, 3=Tertiary');
            $table->tinyInteger('sector_type')->nullable(true)->comment('1=Public, 2=Private');
            $table->string('product_services',512)->nullable(true);
            $table->text('number_of_employee')->nullable(true);
            $table->text('net_income')->nullable(true);
            $table->text('annual_sales')->nullable(true);
            $table->text('financial_target')->nullable(true);
            $table->text('company_description')->nullable(true);
            $table->text('business_description')->nullable(true);
            $table->text('mission')->nullable(true);
            $table->text('vision')->nullable(true);
            $table->text('history_growth')->nullable(true);
            $table->text('industry_information')->nullable(true);
            $table->text('policies')->nullable(true);
            $table->text('public_relations')->nullable(true);
            $table->text('advertising')->nullable(true);
            $table->boolean('is_deleted')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
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
        Schema::dropIfExists('company_details');
    }
}
