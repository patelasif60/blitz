<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogisticsProviders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_providers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->collation('utf8mb4_unicode_ci')->comment('Name of logistics provider');
            $table->text('production_token')->nullable(true)->comment('Token for production server');
            $table->text('production_base_path')->nullable(true)->comment('Prefix path for production server');
            $table->text('staging_token')->nullable(true)->comment('Token for staging server');
            $table->text('staging_base_path')->nullable(true)->comment('Prefix path for staging server');
            $table->text('description')->nullable(true)->comment('Logistics provider description');
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
        Schema::dropIfExists('logistics_providers');
    }
}
