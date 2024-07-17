<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('quote_number',512)->collation('utf8mb4_unicode_ci');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('rfq_id');
            $table->unsignedBigInteger('product_id');
            $table->double('product_price_per_unit');
            $table->integer('product_quantity');
            $table->decimal('price_unit', 10,0);
            $table->integer('min_delivery_days');
            $table->integer('max_delivery_days');
            $table->date('valid_till');
            $table->double('product_amount');
            $table->double('final_amount');
            $table->text('tax')->collation('utf8mb4_unicode_ci')->nullable(true);
            $table->double('tax_value');
            $table->text('note')->collation('utf8mb4_unicode_ci')->nullable(true);
            $table->string('certificate',512)->collation('utf8mb4_unicode_ci')->nullable(true);
            $table->text('comment')->collation('utf8mb4_unicode_ci')->nullable(true);
            $table->boolean('is_deleted')->default(0);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();


            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('rfq_id')->references('id')->on('rfqs')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotes');
    }
}
