<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentProviderAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_provider_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payment_provider_id')->nullable()->comment('payment_providers table');
            $table->string('name',512)->comment('business or company name');
            $table->string('email',512)->comment('business or company email');
            $table->string('payment_provider_ac_id',100)->comment('payment provider virtual ac');
            $table->json('response_data')->comment('Api response data')->nullable(true);
            $table->text('description')->nullable(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
            $table->foreign('payment_provider_id')->nullable()->references('id')->on('payment_providers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_provider_accounts');
    }
}
