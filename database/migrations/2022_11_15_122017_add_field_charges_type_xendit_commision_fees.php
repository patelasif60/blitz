<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldChargesTypeXenditCommisionFees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xendit_commision_fees', function (Blueprint $table) {
            $table->boolean('type')->default('0')->comment('0 %, 1 Rp')->after('company_id');
            $table->decimal('charges_value', 10, 0)->after('type');
            $table->smallInteger('charges_type')->default(0)->comment('2 Platform Charges')->after('charges_value');
            $table->boolean('addition_substraction')->default(0)->comment('0 Discount (-), 1 Charges (+)')->after('charges_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('xendit_commision_fees', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('charges_value');
            $table->dropColumn('charges_type');
            $table->dropColumn('addition_substraction');
        });
    }
}
