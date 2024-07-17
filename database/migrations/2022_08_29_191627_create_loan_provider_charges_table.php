<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanProviderChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_provider_charges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('loan_provider_id')->comment('loan_providers table');
            $table->unsignedBigInteger('charges_type_id')->comment('loan_provider_charges_types table');
            $table->tinyInteger('amount_type')->default(1)->comment('0=>%,1=>Flat');
            $table->tinyInteger('addition_substraction')->default(1)->comment('0=>minus(-),1=>plus(+)');
            $table->text('value')->collation('utf8_unicode_ci')->nullable(true);
            $table->text('period_in_days')->collation('utf8_unicode_ci')->nullable(true);
            $table->text('period_in_month')->collation('utf8_unicode_ci')->nullable(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
            $table->foreign('loan_provider_id')->references('id')->on('loan_providers')->onDelete('cascade');
            $table->foreign('charges_type_id')->references('id')->on('loan_provider_charges_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_provider_charges');
    }
}
