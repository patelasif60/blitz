<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldRefundDiscountColumnGroupMembersDiscounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_members_discounts', function (Blueprint $table) {
            $table->decimal('refund_discount', 10, 0)->default(0)->after('prospect_discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_members_discounts', function (Blueprint $table) {
            $table->dropColumn('refund_discount');
        });
    }
}
