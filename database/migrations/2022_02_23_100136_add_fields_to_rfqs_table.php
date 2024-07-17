<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToRfqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfqs', function (Blueprint $table) {
            $table->string('address_name',512)->after('billing_tax_option')->nullable(true)->default('');
            $table->string('address_line_1',512)->after('address_name')->nullable(true)->default('');
            $table->string('address_line_2',512)->after('address_line_1')->nullable(true)->default('');
            $table->string('city',256)->after('address_line_2')->nullable(true)->default('');
            $table->string('sub_district',256)->after('city')->nullable(true)->default('');
            $table->string('district',256)->after('sub_district')->nullable(true)->default('');
            $table->string('state',256)->after('district')->nullable(true)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rfqs', function (Blueprint $table) {
            $table->dropColumn(['address_name','address_line_1','address_line_2','city','sub_district','district','state']);
        });
    }
}
