<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesChargesWithAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes_charges_with_amounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('quote_id');
            $table->string('charge_name',512)->collation('utf8mb4_unicode_ci');
            $table->boolean('value_on')->default(0);
            $table->boolean('addition_substraction')->default(0);
            $table->boolean('type')->default(0);
            $table->double('charge_value');
            $table->double('charge_amount');
            $table->smallInteger('charge_type')->default(0);
            $table->boolean('is_deleted')->default(0);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotes_charges_with_amounts');
    }
}
