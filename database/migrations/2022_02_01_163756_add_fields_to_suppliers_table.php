<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('c_phone_code',6)->comment('company phone code')->after('email')->nullable(true)->default('');
            $table->string('alternate_email',255)->after('contact_person_phone')->nullable(true)->default('');
            $table->string('nib',20)->after('logo')->nullable(true)->default('');
            $table->string('nib_file',512)->after('nib')->nullable(true)->default('');
            $table->string('npwp',25)->after('nib_file')->nullable(true)->default('');
            $table->string('npwp_file',512)->after('npwp')->nullable(true)->default('');
            $table->string('cp_phone_code',6)->after('contact_person_email')->comment('contact person phone code')->nullable(true)->default('');
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
            $table->dropColumn('c_phone_code');
            $table->dropColumn('alternate_email');
            $table->dropColumn('nib');
            $table->dropColumn('nib_file');
            $table->dropColumn('npwp');
            $table->dropColumn('npwp_file');
            $table->dropColumn('cp_phone_code');
        });
    }
}
