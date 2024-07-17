<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupMembersDiscounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_members_discounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_member_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('rfq_id');
            $table->unsignedBigInteger('quote_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('group_id');
            $table->decimal('avail_discount', 10, 0);
            $table->decimal('achieved_discount', 10, 0);
            $table->decimal('prospect_discount', 10, 0);
            
            $table->boolean('is_deleted')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('group_member_id')->references('id')->on('group_members')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('rfq_id')->references('id')->on('rfqs')->onDelete('cascade');
            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_members_discounts');
    }
}
