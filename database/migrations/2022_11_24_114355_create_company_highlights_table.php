<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyHighlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_highlights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('model');
            $table->unsignedBigInteger('company_id')->nullable(true);
            $table->unsignedBigInteger('user_id')->nullable(true);
            $table->tinyInteger('category')->comment('1=Award, 2=Certificate, 3=Media Recognition');
            $table->string('name',512)->nullable(true);
            $table->string('number',512)->nullable(true);
            $table->text('image')->nullable(true);
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
        Schema::dropIfExists('company_highlights');
    }
}
