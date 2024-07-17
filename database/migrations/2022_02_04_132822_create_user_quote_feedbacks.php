<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserQuoteFeedbacks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_quote_feedbacks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('rfq_id');
            $table->unsignedBigInteger('quote_id');
            $table->string('security_code',10)->collation('utf8mb4_unicode_ci')->nullable();
            $table->tinyInteger('feedback')->default(0)->comment('0=>Pending, 1=>Accepted, 2=>Rejected');
            $table->boolean('is_deleted')->default(0);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('rfq_id')->references('id')->on('rfqs')->onDelete('cascade');
            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('user_quote_feedbacks', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['rfq_id']);
            $table->dropForeign(['quote_id']);
        });
        Schema::dropIfExists('user_quote_feedbacks');
        
    }
}
