<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderCreditDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_credit_days', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->unsigned()->index()->nullable(true);
            $table->bigInteger('credit_days_id')->unsigned()->index()->nullable(true);
            $table->integer('request_days')->default(0);
            $table->integer('approved_days')->default(0);
            $table->text('notes')->collation('utf8_unicode_ci')->nullable(true);
            $table->tinyInteger('status')->default(0)->comment('0=>pending,1=>approved,2=>rejected');
            $table->boolean('is_deleted')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('credit_days_id')->references('id')->on('credit_days')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_credit_days');
    }
}
