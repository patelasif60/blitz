<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('address_name',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->string('address_line_1',512)->collation('utf8_unicode_ci');
            $table->string('address_line_2',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->integer('pincode');
            $table->string('city',256)->collation('utf8_unicode_ci')->nullable(true);
            $table->string('state',256)->collation('utf8_unicode_ci')->nullable(true);
            $table->boolean('is_deleted')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_addresses');
    }
}
