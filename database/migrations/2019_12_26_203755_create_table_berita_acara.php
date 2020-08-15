<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBeritaAcara extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berita_acara_report', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('request_id')->unsigned(); // nama, npm, judul
            $table->integer('penjadwalan_sidang_id')->unsigned();
            $table->decimal('nilai_score', 5, 2)->nullable();
            $table->string('nilai_index', 10)->nullable();
            $table->decimal('nilai_ip', 5, 2)->nullable();
            $table->integer('status')->nullable(); // 0 new, 1 ongoing
            $table->integer('permission_by')->nullable();
            $table->datetime('permission_at')->nullable();
            $table->datetime('scheduled_at')->nullable();
            $table->datetime('expired_at')->nullable();
            $table->datetime('pembimbing_submit_at')->nullable();
            $table->datetime('penguji_submit_at')->nullable();
            $table->datetime('score_submit_at')->nullable();
            $table->integer('scored_by')->nullable();
            $table->foreign('request_id')->references('id')->on('requests');
            $table->foreign('penjadwalan_sidang_id')->references('id')->on('penjadwalan_sidang');
            $table->integer('penguji_user_id')->nullable()->unsigned();
            $table->foreign('penguji_user_id')->references('id')->on('prodi_user');
            $table->integer('ketua_penguji_user_id')->nullable()->unsigned();
            $table->foreign('ketua_penguji_user_id')->references('id')->on('prodi_user');
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
        Schema::dropIfExists('berita_acara_report');
    }
}
