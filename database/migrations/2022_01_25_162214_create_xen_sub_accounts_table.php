<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXenSubAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xen_sub_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('supplier_id')->nullable(true);
            $table->string('xen_platform_id', 50);
            $table->string('type', 50);
            $table->string('status', 50);
            $table->string('country', 50);
            $table->string('email', 250);
            $table->string('business_name', 250)->nullable(true);
            $table->text('public_profile')->nullable(true);
            $table->timestamp('created')->nullable(true);
            $table->timestamp('updated')->nullable(true);
            $table->text('description')->nullable(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xen_sub_accounts');
    }
}
