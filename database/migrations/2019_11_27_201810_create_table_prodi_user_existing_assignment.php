<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProdiUserExistingAssignment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prodi_user_assignment', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('prodi_user_id')->unsigned();
            $table->integer('study_program_id')->unsigned();
            $table->foreign('prodi_user_id')->references('id')->on('prodi_user');
            $table->foreign('study_program_id')->references('id')->on('study_programs');
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
        Schema::dropIfExists('prodi_user_assignment');
    }
}
