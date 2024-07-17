<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyerBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer_banks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('User id of buyer');
            $table->unsignedBigInteger('bank_id')->nullable()->comment('Bank id');
            $table->text('account_holder_name')->nullable()->comment('Account holder name');
            $table->text('account_number')->nullable()->comment('Account number');
            $table->longText('description')->nullable()->comment('Account description');
            $table->boolean('is_primary')->nullable()->comment('Is account primary');
            $table->unsignedBigInteger('created_by')->nullable()->comment('Created by user id');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Updated by user id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buyer_banks');
    }
}
