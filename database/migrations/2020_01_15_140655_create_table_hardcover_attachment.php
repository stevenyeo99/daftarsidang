<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHardcoverAttachment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('hardcover_mahasiswa', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('type');
            $table->string('nama_mahasiswa', 255);
            $table->string('npm', 10);
            $table->string('prodi', 255);
            $table->string('nama_pembimbing', 255);
            $table->datetime('tanggal_submit')->nullable();
            $table->datetime('tanggal_validasi')->nullable();
            $table->string('status', 10);
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
        Schema::dropIfExists('hardcover_mahasiswa');
    }
}
