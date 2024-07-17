<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisbursementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disbursements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('batch_disbursement_id')->nullable(true);
            $table->unsignedBigInteger('order_id');
            $table->string('disbursement_id',50);
            $table->string('user_id',50);
            $table->string('external_id',50);
            $table->string('status',15);
            $table->double('amount')->default(0);
            $table->string('bank_reference',50)->nullable(true);
            $table->string('bank_code',50);
            $table->string('valid_name',200)->nullable(true);
            $table->string('bank_account_name',200);
            $table->string('bank_account_number',50)->nullable(true);
            $table->text('disbursement_description');
            $table->boolean('is_instant')->nullable(true);
            $table->string('failure_code',50)->nullable(true);
            $table->string('failure_message',250)->nullable(true);
            $table->string('email_to',250)->nullable(true);
            $table->text('email_cc')->nullable(true);
            $table->text('email_bcc')->nullable(true);
            $table->timestamp('created')->nullable(true);
            $table->timestamp('updated')->nullable(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('batch_disbursement_id')->references('id')->on('batch_disbursements')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disbursements');
    }
}
