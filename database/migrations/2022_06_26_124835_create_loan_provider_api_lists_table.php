<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanProviderApiListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_provider_api_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('loan_provider_id')->comment('foriegn key loan provider id');
            $table->string('name')->collation('utf8mb4_unicode_ci')->comment('Loan provider Name');
            $table->text('description')->nullable()->collation('utf8mb4_unicode_ci')->comment('Loan provider description');
            $table->string('method')->nullable()->collation('utf8mb4_unicode_ci')->comment('API acceptance method');
            $table->text('path')->nullable()->collation('utf8mb4_unicode_ci')->comment('Loan provider link');
            $table->softDeletes(); // this will create deleted_at field for softdelete
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
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
        Schema::dropIfExists('loan_provider_api_lists');
    }
}
