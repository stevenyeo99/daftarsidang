<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('session');
            $table->integer('type'); // enums
            $table->string('title', 255);
            $table->integer('status'); // enums
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->string('repeat_reason', 255)->nullable();
            $table->string('reject_reason', 255)->nullable();
            $table->string('mentor_name', 50);

            $table->integer('student_id')->unsigned();
            $table->foreign('student_id')
                  ->references('id')->on('students');

            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('requests');
    }
}
