<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangesColumnNullableSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('email',512)->nullable(true)->change();
            $table->string('mobile',512)->nullable(true)->change();
            $table->text('address')->nullable(true)->change();
            $table->string('salutation', 20)->nullable(true)->change();
            $table->string('contact_person_name',512)->nullable(true)->change();
            $table->string('contact_person_email',512)->nullable(true)->change();
            $table->string('contact_person_last_name',512)->nullable(true)->change();
            $table->string('contact_person_phone',512)->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('email',512)->nullable(false)->change();
            $table->string('mobile',512)->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
            $table->string('salutation', 20)->nullable(false)->change();
            $table->string('contact_person_name',512)->nullable(false)->change();
            $table->string('contact_person_email',512)->nullable(false)->change();
            $table->string('contact_person_last_name',512)->nullable(false)->change();
            $table->string('contact_person_phone',512)->nullable(false)->change();
        });
    }
}
