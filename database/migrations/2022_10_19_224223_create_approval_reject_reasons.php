<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalRejectReasons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_reject_reasons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rfq_id');
            $table->unsignedBigInteger('quote_id');
            $table->unsignedBigInteger('approval_person_id');
            $table->unsignedBigInteger('user_quote_feedback_id');
            $table->string('reason_key',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->longText('reason_text')->collation('utf8_unicode_ci')->nullable(true);
            $table->softDeletes(); // this will create deleted_at field for softdelete
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('rfq_id')->references('id')->on('rfqs');
            $table->foreign('quote_id')->references('id')->on('quotes');
            $table->foreign('approval_person_id')->references('id')->on('users');
            $table->foreign('user_quote_feedback_id')->references('id')->on('user_quote_feedbacks');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approval_reject_reasons');
    }
}
