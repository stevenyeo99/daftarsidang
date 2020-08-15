<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRescheduleHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('old_penjadwalan_sidang_history', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('penjadwalan_sidang_id')->unsigned();
            $table->integer('dosen_pembimbing_or_backup_id')->unsigned();
            $table->foreign('dosen_pembimbing_or_backup_id', 'dospem_id')->references('id')->on('prodi_user_assignment');
            $table->integer('dosen_penguji_id')->unsigned();
            $table->foreign('dosen_penguji_id')->references('id')->on('prodi_user_assignment');
            $table->integer('ruangan_sidang_id');
            $table->datetime('tanggal_waktu_sidang');
            $table->foreign('penjadwalan_sidang_id')->references('id')->on('penjadwalan_sidang');
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
        Schema::dropIfExists('old_penjadwalan_sidang_history');
    }
}
