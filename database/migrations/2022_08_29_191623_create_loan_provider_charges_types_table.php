<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanProviderChargesTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_provider_charges_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('loan_provider_id')->comment('loan_providers table');
            $table->string('name',512)->collation('utf8_unicode_ci');
            $table->text('description')->nullable(true);
            $table->tinyInteger('interest_rate_is_by_buyer')->default(0)->comment('0=>No,1=>Yes (amount will deduct on disbursement)');
            $table->boolean('status')->default(1)->comment('0=>Deactive,1=>Active');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
            $table->foreign('loan_provider_id')->references('id')->on('loan_providers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_provider_charges_types');
    }
}
