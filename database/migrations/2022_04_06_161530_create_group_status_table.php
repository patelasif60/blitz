<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',512)->collation('utf8_unicode_ci');
            $table->string('description',512)->collation('utf8_unicode_ci');
            $table->boolean('status')->default(1);
            $table->Integer('show_order_id')->nullable(true);
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
        Schema::dropIfExists('group_status');
    }
}
