<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvailableBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('available_banks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',250);
            $table->string('code',60);
            $table->string('logo',512)->nullable(true);
            $table->tinyInteger('can_disburse');
            $table->tinyInteger('can_name_validate');
            $table->unsignedBigInteger('added_by')->default(1);
            $table->unsignedBigInteger('updated_by')->default(1);
            $table->unsignedBigInteger('deleted_by')->nullable(true);
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('available_banks');
        Schema::enableForeignKeyConstraints();
    }
}
