<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLtmTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('ltm_translations', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->integer('status')->default(0);
        //     $table->string('locale')->collation('utf8mb4_bin');
        //     $table->string('group')->collation('utf8mb4_bin');
        //     $table->text('key')->collation('utf8mb4_bin');
        //     $table->text('value')->collation('utf8mb4_bin')->nullable(true);
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('ltm_translations');
    }
}
