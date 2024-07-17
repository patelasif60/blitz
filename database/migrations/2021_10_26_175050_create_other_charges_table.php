<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_charges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',512)->collation('utf8mb4_unicode_ci');
            $table->text('description')->nullable(true);
            $table->boolean('type')->default('0');
            $table->decimal('charges_value', 10, 0);
            $table->boolean('value_on')->default(0);
            $table->boolean('charges_type')->default(0);
            $table->boolean('status')->default(1);
            $table->boolean('addition_substraction')->default(0);
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
        Schema::dropIfExists('other_charges');
    }
}
