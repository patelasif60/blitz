<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 512);
            $table->string('group_number')->nullable();
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('subCategory_id')->nullable()->unsigned();
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->bigInteger('unit_id')->nullable()->unsigned();
            $table->text('description')->nullable();
            $table->date('end_date');
            $table->text('social_token');
            $table->double('reached_quantity');
            $table->integer('status')->default(0);
            $table->unsignedBigInteger('group_status')->default(1);
            $table->text('location_code')->nullable(true);
            $table->double('price');
            $table->double('min_order_quantity')->nullable(true);
            $table->double('max_order_quantity')->nullable(true);
            $table->double('group_margin')->default(0);
            $table->double('target_quantity')->nullable(true);
            $table->bigInteger('added_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('group_status')->references('id')->on('order_status')->onDelete('cascade');
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
        Schema::dropIfExists('groups');
    }
}
