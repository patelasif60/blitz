<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('licence',512)->after('xen_platform_id')->nullable(true);
            $table->string('fax',512)->after('licence')->nullable(true);
            $table->text('facebook')->after('fax')->nullable(true);
            $table->text('twitter')->after('facebook')->nullable(true);
            $table->text('linkedin')->after('twitter')->nullable(true);
            $table->text('youtube')->after('linkedin')->nullable(true);
            $table->text('instagram')->after('youtube')->nullable(true);
            $table->text('established_date')->after('instagram')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('licence');
            $table->dropColumn('fax');
           $table->dropColumn('facebook');
            $table->dropColumn('twitter');
            $table->dropColumn('linkedin');
            $table->dropColumn('youtube');
            $table->dropColumn('instagram');
            $table->dropColumn('established_date');
        });
    }
}
