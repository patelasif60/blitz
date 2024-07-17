<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldIntoOrdersTable extends Migration
{
       /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('upload_order_doc')->after('credit_days')->nullable(true);
            $table->string('upload_order_doc_filename')->nullable(true)->after('upload_order_doc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('upload_order_doc');
            $table->dropColumn('upload_order_doc_filename');
        });
    }
}
