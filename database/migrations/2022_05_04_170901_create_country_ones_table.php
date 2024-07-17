<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryOnesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_ones', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable()->comment('Country name');
            $table->text('iso2')->nullable()->comment('Country ISO2 name');
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
        Schema::dropIfExists('country_ones');
    }
}
