<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfns', function (Blueprint $table) {
            $table->id();
            $table->morphs('user');
            $table->unsignedBigInteger('company_id')->comment('Company table id');
            $table->unsignedBigInteger('rfq_id')->nullable()->comment(' 1 rfn to global rfn, 2 global rfn to rfn');
            $table->text('prefix')->comment('Reference number prefix BRFN');
            $table->text('rfn_number')->comment('Reference number');
            $table->tinyInteger('type')->default(1)->comment('1 RFN, 2 Group RFN');
            $table->tinyInteger('is_converted')->nullable()->comment('1 RFN to Global RFN');
            $table->dateTime('expected_date')->nullable()->comment('Rfn expected date');
            $table->dateTime('start_date')->nullable()->comment('Rfn start date');
            $table->dateTime('end_date')->nullable()->comment('Rfn end date');
            $table->tinyInteger('status')->default(1)->comment('1 Pending, 2 Approved, 3 Canceled');
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
        Schema::dropIfExists('rfns');
    }
}
