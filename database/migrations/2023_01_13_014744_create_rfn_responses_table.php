<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfnResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfn_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rfn_id')->comment('RFN table id');
            $table->morphs('user');
            $table->unsignedBigInteger('company_id')->comment('Company table id');
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
        Schema::dropIfExists('rfn_responses');
    }
}
