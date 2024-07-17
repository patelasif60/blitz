<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldCompanyIdApprovalPersonIdColumnsUserQuoteFeedbacks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_quote_feedbacks', function (Blueprint $table) {
            $table->unsignedBigInteger('approval_person_id')->after('security_code')->nullable()->comment('id from users - get approval permission');
            $table->unsignedBigInteger('company_id')->after('approval_person_id')->nullable()->comment('Companies table id');

            $table->foreign('approval_person_id')->references('id')->on('users');
            $table->foreign('company_id')->references('id')->on('companies');
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
            Schema::dropColumns('user_quote_feedbacks',['approval_person_id','company_id']);
        });
    }
}
