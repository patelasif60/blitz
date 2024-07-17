<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('bank_details');
        Schema::create('bank_details', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name',512)->collation('utf8_unicode_ci');
            $table->string('ac_name',512)->collation('utf8_unicode_ci');
            $table->string('ac_no',50)->collation('utf8_unicode_ci');
            $table->string('bank_code',50)->collation('utf8_unicode_ci');
            $table->text('description')->nullable(true);
            $table->tinyInteger('status')->default(1);
            $table->boolean('is_deleted')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_detalis');
    }
}
