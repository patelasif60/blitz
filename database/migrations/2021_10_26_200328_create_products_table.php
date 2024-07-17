<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subcategory_id');

            $table->string('name',512)->collation('utf8_unicode_ci');
            $table->text('description')->nullable(true);
            $table->boolean('status')->default(1);
            $table->boolean('is_verify')->default(1);
            $table->boolean('is_deleted')->default(0);
            $table->unsignedBigInteger('added_by')->default(1);
            $table->unsignedBigInteger('updated_by')->default(1);
            $table->unsignedBigInteger('deleted_by')->nullable(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('subcategory_id')->references('id')->on('sub_categories')->onDelete('cascade');

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
        Schema::dropIfExists('products');
    }
}
