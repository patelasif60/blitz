<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInviteBuyerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invite_buyer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('supplier_id')->nullable(true);
            $table->unsignedBigInteger('user_id')->nullable(true);
            $table->string('user_email',512)->collation('utf8_unicode_ci');
            $table->enum('status',['0', '1', '2'])->default(0)->comment('0=>pending,1=>active,2=>link expired');
            $table->unsignedBigInteger('added_by')->default(1);
            $table->integer('resend_count')->default(0);
            $table->string('token',100)->collation('utf8mb4_unicode_ci')->nullable(true);
            $table->timestamp('date')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->boolean('is_deleted')->default(0);
            //$table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invite_buyer');
    }
}
