<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlitznetCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blitznet_commissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_id')->nullable(true);
            $table->unsignedBigInteger('order_id')->nullable(true);
            $table->unsignedBigInteger('supplier_id')->nullable(true);
            $table->unsignedBigInteger('disbursement_id')->nullable(true);
            $table->unsignedBigInteger('xen_balance_transfer_id')->nullable(true)->comment('Xen Balance Transfer Id');
            $table->unsignedInteger('commission_type_id');
            $table->boolean('payment_type')->default(0)->comment('0=>%,1=>Flat');
            $table->double('commission_per')->nullable(true)->comment('Commission Percentage');
            $table->date('paid_date')->comment('Commission Date');
            $table->double('paid_amount')->comment('Commission Amount');
            $table->text('description')->nullable(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('disbursement_id')->references('id')->on('disbursements')->onDelete('cascade');
            $table->foreign('xen_balance_transfer_id')->references('id')->on('xen_balance_transfers')->onDelete('cascade');
            $table->foreign('commission_type_id')->references('id')->on('commission_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blitznet_commissions');
    }
}
