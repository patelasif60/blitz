<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupSupplierDiscountOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_supplier_discount_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('group_supplier_id');
            $table->bigInteger('group_id');
            $table->double('min_quantity');
            $table->double('max_quantity');
            $table->bigInteger('unit_id');
            $table->double('discount');
            $table->double('discount_price');
            $table->bigInteger('added_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
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
        Schema::dropIfExists('group_supplier_discount_options');
    }
}
