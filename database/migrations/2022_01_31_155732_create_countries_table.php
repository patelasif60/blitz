<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('iso2', 3);
            $table->string('iso3', 4);
            $table->string('domain', 3)->nullable(true);
            $table->string('fips', 3)->nullable(true);
            $table->string('iso_numeric', 4)->nullable(true);
            $table->string('geo_name_id', 8)->nullable(true);
            $table->string('e164', 4)->nullable(true);
            $table->string('phone_code', 20);
            $table->string('continent', 100);
            $table->string('capital', 100)->nullable(true);
            $table->string('time_zone', 30)->default('');
            $table->string('currency', 30)->nullable(true);
            $table->string('language_codes', 90)->nullable(true);
            $table->string('languages', 490)->nullable(true);
            $table->string('area_km2', 30)->nullable(true);
            $table->text('image')->nullable(true);
            $table->text('description')->nullable(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
