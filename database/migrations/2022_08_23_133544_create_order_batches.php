<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderBatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_batches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('airwaybill_id')->nullable(true);
            $table->unsignedBigInteger('supplier_address_id');
            $table->string('order_batch',215)->nullable(true);
            $table->json('order_item_ids')->nullable();
            $table->dateTime('order_pickup',  0)->nullable(true);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('airwaybill_id')->references('id')->on('airwaybill_number')->onDelete('cascade');
            $table->foreign('supplier_address_id')->references('id')->on('supplier_addresses')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('order_batches');
    }
}
