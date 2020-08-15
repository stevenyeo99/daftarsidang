<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStudentsTableChangePhoneNumberLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('phone_number');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->string('phone_number', 50)->unique()->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('phone_number');
        });
        
        Schema::table('students', function (Blueprint $table) {
            $table->string('phone_number', 20)->unique()->nullable()->after('email');
        });
    }
}
