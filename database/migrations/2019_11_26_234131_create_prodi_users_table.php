<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdiUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prodi_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 50);
            $table->string('email', 50);
            $table->integer('is_admin');
            $table->string('password', 255);
            $table->rememberToken();
            $table->integer('study_programs_id')->unsigned();
            $table->foreign('study_programs_id')->references('id')->on('study_programs');
            $table->string('initial_name', 255);
            // $table->integer('status_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prodi_user');
    }
}
