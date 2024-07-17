<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFiledOtherInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_other_information', function (Blueprint $table) {
            $table->string('license_image_filename')->after('license_image')->nullable(true);
            $table->string('bank_statement_image_filename')->after('bank_statement_image')->nullable(true);
            $table->string('annual_financial_statement_image_filename')->after('annual_financial_statement_image')->nullable(true);
        });

        Schema::table('user_other_information', function (Blueprint $table) {
            $table->string('ktp_image_filename')->after('ktp_image')->nullable(true);
            $table->string('ktp_with_selfie_image_filename')->after('ktp_with_selfie_image')->nullable(true);
            $table->string('family_card_image_filename')->after('family_card_image')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_other_information', function (Blueprint $table) {
            $table->dropColumn('license_image_filename');
            $table->dropColumn('bank_statement_image_filename');
            $table->dropColumn('annual_financial_statement_image_filename');
        });

        Schema::table('user_other_information', function (Blueprint $table) {
            $table->dropColumn('ktp_image_filename');
            $table->dropColumn('ktp_with_selfie_image_filename');
            $table->dropColumn('family_card_image_filename');
        });
    }
}
