<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BeritaAcaraParticipant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berita_acara_participant', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('berita_acara_report_id')->unsigned();
            $table->integer('participant_id')->unsigned();
            $table->integer('participant_type');
            $table->integer('have_revision');
            $table->foreign('berita_acara_report_id')->references('id')->on('berita_acara_report');
            $table->foreign('participant_id')->references('id')->on('prodi_user');
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
        Schema::dropIfExists('berita_acara_participant');
    }
}
