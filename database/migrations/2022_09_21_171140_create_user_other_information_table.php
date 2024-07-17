<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOtherInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_other_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->tinyInteger('religion')->default(1);
            $table->tinyInteger('marital_status')->default(1)->comment('1=KAWIN,2=BELUM KAWIN,3=CERAI MATI,4=CERAI HIDUP');
            $table->date('date_of_birth')->nullable(true);
            $table->string('place_of_birth',512)->nullable(true);
            $table->text('ktp_image')->nullable(true);
            $table->string('ktp_nik',255);
            $table->text('ktp_with_selfie_image')->nullable(true);
            $table->text('family_card_image')->nullable(true);
            $table->tinyInteger('gender')->default(1)->comment('1=Male,2=Female');        
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_other_information');
    }
}
