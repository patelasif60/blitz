<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfqs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname',256)->collation('utf8_unicode_ci');
            $table->string('lastname',256)->collation('utf8_unicode_ci');
            $table->string('mobile',256)->collation('utf8_unicode_ci');
            $table->string('email',256)->collation('utf8_unicode_ci');
            $table->string('billing_tax_option',256)->collation('utf8_unicode_ci')->nullable(true);
            $table->integer('pincode');
            $table->unsignedBigInteger('status_id')->default(1);
            $table->string('reference_number',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->boolean('rental_forklift')->default(0);
            $table->boolean('unloading_services')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('status_id')->references('id')->on('rfq_status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rfqs');
    }
}
