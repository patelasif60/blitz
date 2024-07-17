<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyerNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->default(null);
            $table->string('user_activity',512);
            $table->string('translation_key',512);
            $table->boolean('is_show')->default(0)->comment('0=>Not Show,1=>Show');
            $table->boolean('is_multiple_show')->default(0)->comment('0=>Not Show,1=>Show');
            $table->boolean('side_count_show')->default(0)->comment('0=>Not Show,1=>Show');
            $table->string('notification_type',512);
            $table->unsignedBigInteger('notification_type_id');
            $table->text('common_data')->nullable(true)->default(null);
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buyer_notifications');
    }
}
