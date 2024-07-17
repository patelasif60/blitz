<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldLeaveGroupColumnsGroupMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_members', function (Blueprint $table) {
            $table->text('group_leave_reason')->collation('utf8_unicode_ci')->nullable(true)->after('rfq_id');
            $table->unsignedBigInteger('removed_by')->nullable()->after('group_leave_reason');
            $table->timestamp('group_leave_date')->useCurrentOnUpdate()->after('removed_by');

            $table->foreign('removed_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_members', function (Blueprint $table) {
            $table->dropForeign(['removed_by']);
            $table->dropColumn('removed_by');
        });
    }
}
