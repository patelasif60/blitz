<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable()->comment('City name');
            $table->unsignedBigInteger('country_id')->nullable()->comment('Country id');
            $table->unsignedBigInteger('state_id')->nullable('State id')->comment('State id');
            $table->unsignedBigInteger('created_by')->nullable()->comment('Created by user id');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Updated by user id');
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
        Schema::dropIfExists('cities');
    }
}
