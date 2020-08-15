<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFinance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 50);
            $table->string('email', 50);
            $table->integer('is_admin');
            $table->string('password', 255);
            $table->rememberToken();
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
        Schema::dropIfExists('finance_user');
    }
}
