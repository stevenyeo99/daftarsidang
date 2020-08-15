<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBeritaAcaraNoteRevisi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berita_acara_note_revisi', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('berita_acara_participant_id')->unsigned();
            $table->text('note_revisi');
            $table->foreign('berita_acara_participant_id')->references('id')->on('berita_acara_participant');
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
        Schema::dropIfExists('berita_acara_note_revisi');
    }
}
