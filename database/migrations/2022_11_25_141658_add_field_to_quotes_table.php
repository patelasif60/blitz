<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->tinyInteger('payment_type')->after('is_deleted')->default(0)->comment('0=>cash,1=>credit,2=>koinworks,3=>lc/skbdn');
            $table->bigInteger('credit_days')->unsigned()->index()->nullable(true)->after('payment_type');
            $table->bigInteger('full_quote_by')->after('credit_days')->default(null)->nullable(true);
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
            $table->dropColumn('payment_type');
            $table->dropColumn('credit_days');
        });
    }
}
