<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfnAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfn_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rfn_id')->comment('RFN table id');
            $table->morphs('user');
            $table->unsignedBigInteger('company_id')->comment('Company table id');
            $table->unsignedBigInteger('address_id')->comment('RFN address id');
            $table->text('name')->nullable()->comment('Address name');
            $table->text('first_line')->nullable()->comment('Address line 1');
            $table->text('second_line')->nullable()->comment('Address line 2');
            $table->text('sub_district')->nullable()->comment('Address sub district');
            $table->text('district')->nullable()->comment('Address district');
            $table->text('state')->nullable()->comment('Address state');
            $table->text('country')->nullable()->comment('Address country');
            $table->text('country_id')->nullable()->comment('Address country one table id');
            $table->text('state_id')->nullable()->comment('Address states table id');
            $table->text('city_id')->nullable()->comment('Address city table id');
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
        Schema::dropIfExists('rfn_addresses');
    }
}
