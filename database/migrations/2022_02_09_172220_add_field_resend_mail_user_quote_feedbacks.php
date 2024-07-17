<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldResendMailUserQuoteFeedbacks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_quote_feedbacks', function (Blueprint $table) {
            $table->boolean('resend_mail')->after('feedback')->default(0)->comment('0=>FirstTime, 1=>Resend');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_quote_feedbacks', function (Blueprint $table) {
            $table->dropColumn('resend_mail');
        });
    }
}
