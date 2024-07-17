<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes_meta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quote_id');
            $table->string('user_type',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('approval_process')->default(0)->comment('0=>Off,1=>On');
            $table->tinyInteger('approval_process_complete')->default(0)->comment('0=>in_progress,1=>completed');
            $table->softDeletes(); // this will create deleted_at field for softdelete
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('quote_id')->references('id')->on('quotes');
            $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotes_meta');
    }
}
