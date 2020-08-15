<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->integer('type'); // enums

            $table->integer('student_id')->unsigned();
            $table->foreign('student_id')
                  ->references('id')->on('students');

            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('company_id')
                  ->references('id')->on('companies');

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
        Schema::dropIfExists('parents');
    }
}
