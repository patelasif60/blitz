<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldAddressesQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->string('address_line_1',512)->collation('utf8_unicode_ci');
            $table->string('address_line_2',512)->collation('utf8_unicode_ci');
            $table->string('district',256)->collation('utf8_unicode_ci');
            $table->string('sub_district',256)->collation('utf8_unicode_ci');
            $table->string('city',256)->collation('utf8_unicode_ci');
            $table->string('provinces',256)->collation('utf8_unicode_ci');
            $table->integer('pincode');
            $table->string('weights',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->string('dimensions',512)->collation('utf8_unicode_ci')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('address_line_1');
            $table->dropColumn('address_line_2');
            $table->dropColumn('district');
            $table->dropColumn('sub_district');
            $table->dropColumn('city');
            $table->dropColumn('provinces');
            $table->dropColumn('pincode');
            $table->dropColumn('weights');
            $table->dropColumn('dimensions');
        });
    }
}
