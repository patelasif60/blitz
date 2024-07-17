<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable()->comment('State name');
            $table->text('iso2')->nullable()->comment('State iso2');
            $table->unsignedBigInteger('country_id')->nullable()->comment('Country id');
            $table->text('type')->nullable()->comment('State type');
            $table->text('latitude')->nullable()->comment('State latitude');
            $table->text('longitude')->nullable()->comment('State longitude');
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
        Schema::dropIfExists('states');
    }
}
