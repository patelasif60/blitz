<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('loan_provider_id')->comment('loan_providers tabel');
            $table->string('status_code',512)->nullable(true)->collation('utf8_unicode_ci');
            $table->string('status_name',512)->collation('utf8_unicode_ci');
            $table->string('status_display_name',512)->collation('utf8_unicode_ci');
            $table->text('status_description')->nullable(true);
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
        Schema::dropIfExists('loan_statuses');
    }
}
