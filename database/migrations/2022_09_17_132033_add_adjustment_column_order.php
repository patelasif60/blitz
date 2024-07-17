<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdjustmentColumnOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('adjustment_amount')->nullable()->after('payment_amount')->comment('Adjusted amount after troubleshooting');
            $table->boolean('payment_status')->comment('0 Unpaid, 1 Online Paid, 2 Offline Paid, 3 Loan Paid')->change();
            $table->boolean('is_credit')->comment('0 Advance, 1 Supplier Credit, 2 Loan')->change();

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
            $table->dropColumn('adjustment_amount');

        });
    }
}
