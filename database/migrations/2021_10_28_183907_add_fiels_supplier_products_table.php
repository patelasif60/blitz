<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFielsSupplierProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_products', function (Blueprint $table) {
            $table->unsignedBigInteger('added_by')->after('is_deleted')->default(1);
            $table->unsignedBigInteger('updated_by')->after('added_by')->default(1);
            $table->unsignedBigInteger('deleted_by')->after('updated_by')->nullable(true);
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
        Schema::table('supplier_products', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropColumn('added_by');
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');
            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
       });
    }
}
