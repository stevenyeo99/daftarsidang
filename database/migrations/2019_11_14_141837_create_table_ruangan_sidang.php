<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRuanganSidang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ruangan_sidang', function(Blueprint $table) {
            $table->increments('id');
            $table->string('gedung', 10);
            $table->string('ruangan', 255);
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')
                ->references('id')->on('users');
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
        Schema::dropIfExists('ruangan_sidang');
    }
}
