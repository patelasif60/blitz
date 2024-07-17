<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPaymentProviderAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_provider_accounts', function (Blueprint $table) {
            $table->integer('environment_type')->default(1)->comment('1 Local, 2 Dev, 3 Beta, 4 Production')->after('payment_provider_ac_id');
            $table->integer('account_type')->default(1)->comment('1 Debit, 2 Credit')->after('environment_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_provider_accounts', function (Blueprint $table) {
            $table->dropColumn(['environment_type','account_type']);
        });
    }
}
