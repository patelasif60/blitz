<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('supplier_id');
            $table->string('address_name',512)->collation('utf8_unicode_ci');
            $table->string('address_line_1',512)->collation('utf8_unicode_ci');
            $table->string('address_line_2',512)->collation('utf8_unicode_ci');
            $table->integer('pincode');
            $table->string('city',256)->collation('utf8_unicode_ci');
            $table->string('state',256)->collation('utf8_unicode_ci');
            $table->string('sub_district',256)->collation('utf8_unicode_ci');
            $table->string('district',256)->collation('utf8_unicode_ci');
            $table->boolean('default_address')->default(0)->comment('0=>No,1=>Yes');
            $table->boolean('is_deleted')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_addresses');
    }
}
