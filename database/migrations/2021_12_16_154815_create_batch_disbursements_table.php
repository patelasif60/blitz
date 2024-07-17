<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchDisbursementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_disbursements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('batch_dis_id',50);
            $table->string('user_id',50);
            $table->string('status',15);
            $table->double('total_uploaded_amount')->default(0);
            $table->integer('total_uploaded_count')->default(0);
            $table->string('approver_id',50);
            $table->timestamp('approved_at')->nullable(true);
            $table->double('total_disbursed_amount')->default(0);
            $table->integer('total_disbursed_count')->default(0);
            $table->double('total_error_amount')->default(0);
            $table->integer('total_error_count')->default(0);
            $table->timestamp('created')->nullable(true);
            $table->timestamp('updated')->nullable(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batch_disbursements');
    }
}
