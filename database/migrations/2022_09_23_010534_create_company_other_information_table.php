<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyOtherInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_other_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->integer('type')->default(1)->comment('1=individual,2=pt,3=cv');
            $table->text('number_of_employee');
            $table->text('average_sales');
            $table->text('annual_sales');
            $table->text('financial_target');
            $table->unsignedBigInteger('category')->default(1);
            $table->longText('description')->nullable(true);
            $table->text('ownership_percentage');
            $table->text('license_image')->nullable(true);
            $table->text('bank_statement_image')->nullable(true);
            $table->date('bank_image_updated_at')->nullable(true);
            $table->text('annual_financial_statement_image')->nullable(true);
            $table->date('annual_image_updated_at')->nullable(true);
            $table->text('siup_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_other_information');
    }
}
