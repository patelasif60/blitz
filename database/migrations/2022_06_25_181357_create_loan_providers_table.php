<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_providers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->collation('utf8mb4_unicode_ci')->comment('Name of loan provider');
            $table->text('production_base_path')->nullable(true)->comment('Prefix path for production server');
            $table->text('staging_base_path')->nullable(true)->comment('Prefix path for staging server');
            $table->text('description')->nullable(true)->comment('Loan provider description');
            $table->softDeletes(); // this will create deleted_at field for softdelete
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
        Schema::dropIfExists('loan_providers');
    }
}
