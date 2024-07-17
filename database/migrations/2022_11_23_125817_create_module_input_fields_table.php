<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleInputFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_input_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('field_name',255)->nullable()->comment('Company profile field name');
            $table->string('field_ids',255)->nullable()->comment('Company profile feild id name');
            $table->string('columns_name',255)->nullable()->comment('Database table columns name');
            $table->string('table_name',255)->nullable()->comment('Effect on Table name');
            $table->string('getby_columnname',255)->nullable()->comment('Table wise gie column name');
            $table->Integer('priority')->nullable(true)->comment('Company Profile input field priority');
            $table->Integer('percentage')->nullable(true)->comment('Company Profile input field percentage');
            $table->string('display_name',255)->nullable()->comment('Company Profile tab field display name');
            $table->string('module_name',255)->nullable()->comment('module Name');
            $table->unsignedBigInteger('system_role_id')->nullable()->comment('System role table id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_input_fields');
    }
}
