<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->collation('utf8mb4_unicode_ci')->comment('Name of loan type');
            $table->text('description')->nullable(true)->collation('utf8mb4_unicode_ci')->comment('Loan Type Description');
            $table->timestamps();
            $table->softDeletes(); // this will create deleted_at field for soft rfqscontrolldelete

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_types');
    }
}
