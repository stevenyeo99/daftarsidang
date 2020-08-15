<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenjadwalanSidangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjadwalan_sidang', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('tanggal_sidang')->nullable();
            $table->integer('dosen_pembimbing_id')->unsigned();
            $table->foreign('dosen_pembimbing_id')->references('id')->on('prodi_user_assignment');
            $table->integer('dosen_penguji_id')->nullable()->unsigned();
            $table->foreign('dosen_penguji_id')->references('id')->on('prodi_user_assignment');
            $table->integer('penjadwalan_by');
            $table->integer('ruangan_sidang_id')->nullable()->unsigned();
            $table->foreign('ruangan_sidang_id')->references('id')->on('ruangan_sidang');
            $table->integer('status_pengiriman')->nullable();
            $table->integer('penempatan_by')->nullable();
            $table->integer('sidang_type');
            $table->integer('request_id')->unsigned();
            $table->foreign('request_id')->references('id')->on('requests');
            $table->integer('dosen_pembimbing_backup')->nullable();
            $table->datetime('tanggal_revisi_sidang')->nullable();
            $table->datetime('tanggal_expired_revisi_sidang')->nullable();
            $table->string('status_penjadwalan', 255)->nullable();
            $table->datetime('tanggal_penjadwalan_expired')->nullable();
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
        Schema::dropIfExists('penjadwalan_sidang');
    }
}
