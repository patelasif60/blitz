<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',512)->collation('utf8_unicode_ci');
            $table->string('email',512)->collation('utf8_unicode_ci');
            $table->string('mobile',512)->collation('utf8_unicode_ci');
            $table->string('website',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->string('logo',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->text('description')->nullable(true);
            $table->text('address')->collation('utf8_unicode_ci');
            $table->string('contact_person_name',512)->collation('utf8_unicode_ci');
            $table->string('contact_person_email',512)->collation('utf8_unicode_ci');
            $table->string('contact_person_phone',512)->collation('utf8_unicode_ci');
            $table->string('catalog',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->string('pricing',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->string('product',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->string('commercialCondition',512)->collation('utf8_unicode_ci')->nullable(true);
            $table->boolean('accepted_terms')->default(1);
            $table->boolean('status')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->unsignedBigInteger('added_by')->default(1);
            $table->unsignedBigInteger('updated_by')->default(1);
            $table->unsignedBigInteger('deleted_by')->nullable(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('suppliers');
        Schema::enableForeignKeyConstraints();
    }
}
