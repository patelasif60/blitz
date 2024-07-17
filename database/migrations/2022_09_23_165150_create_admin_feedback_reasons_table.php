<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminFeedbackReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_feedback_reasons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('reasons')->nullable(true);
            $table->unsignedBigInteger('reasons_type')->collation('utf8_unicode_ci')->comment('1=>Rfq, 2=>Quote, 3=>Order, 4=>GroupRfq')->nullable(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_feedback_reasons');
    }
}
