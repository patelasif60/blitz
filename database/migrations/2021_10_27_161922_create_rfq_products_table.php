<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfqProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfq_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rfq_id');
            $table->string('category',512)->collation('utf8_unicode_ci');
            $table->string('sub_category',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->string('product',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->string('brand_ids',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->string('other_preferred_brand',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->string('grade_ids',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->string('other_preferred_grade',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->string('product_description',512)->collation('utf8_unicode_ci');
            $table->float('quantity',0);
            $table->unsignedBigInteger('unit_id');
            $table->date('expected_date')->nullable(true);
            $table->text('comment')->collation('utf8_unicode_ci')->nullable(true);
            $table->boolean('is_deleted')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('rfq_id')->references('id')->on('rfqs')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rfq_products');
    }
}
