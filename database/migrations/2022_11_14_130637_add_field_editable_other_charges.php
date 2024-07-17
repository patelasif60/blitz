<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldEditableOtherCharges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('other_charges', function (Blueprint $table) {
            $table->tinyInteger('editable')->default(0)->comment('1 true, 0 false')->after('addition_substraction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('other_charges', function (Blueprint $table) {
            $table->dropColumn('editable');
        });
    }
}
