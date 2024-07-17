<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnExpectedDateRfqIdStatusInRfnResponses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfn_responses', function (Blueprint $table) {
            $table->dateTime('expected_date')->nullable()->comment('Expected date for rfn response')->after('company_id');
            $table->unsignedBigInteger('rfq_id')->nullable()->comment('Rfq id')->after('rfn_id');
            $table->tinyInteger('status')->default(1)->comment('1 Pending, 2 Approved, 3 Canceled')->after('expected_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rfn_responses', function (Blueprint $table) {
            //
        });
    }
}
