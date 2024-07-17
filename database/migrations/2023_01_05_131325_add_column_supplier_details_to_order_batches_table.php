<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSupplierDetailsToOrderBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_batches', function (Blueprint $table) {
            $table->string('receiver_pic_phone')->nullable()->after('order_pickup');
            $table->string('receiver_email_address')->nullable()->after('order_pickup');
            $table->string('receiver_company_name')->nullable()->after('order_pickup');
            $table->string('receiver_name')->nullable()->after('order_pickup');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_batches', function (Blueprint $table) {
            $table->dropColumn('receiver_name');
            $table->dropColumn('receiver_company_name');
            $table->dropColumn('receiver_email_address');
            $table->dropColumn('receiver_pic');
            $table->dropColumn('receiver_pic_phone');
        });
    }
}
