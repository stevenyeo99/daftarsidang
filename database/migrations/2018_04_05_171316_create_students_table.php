<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('npm', 10)->unique();
            $table->string('password', 255);
            $table->string('name', 50);
            $table->integer('sex'); // enums
            $table->string('NIK', 20)->nullable();
            $table->string('toeic_grade', 5)->nullable();
            $table->string('birth_place', 45)->nullable();
            $table->dateTime('birthdate')->nullable();
            $table->string('religion', 45)->nullable();
            $table->string('email', 45)->unique()->nullable();
            $table->string('phone_number', 20)->unique()->nullable();
            $table->string('address', 255)->nullable();
            $table->integer('work_status')->nullable(); // enums
            $table->integer('toga_size')->nullable(); // enums
            $table->integer('consumption_type')->nullable(); // enums
            $table->string('existing_degree', 45)->nullable();
            $table->string('certification_degree', 45)->nullable();
            $table->boolean('profile_filled')->default(false);
            $table->boolean('is_profile_accurate')->default(true);
            $table->boolean('must_fill_attachment')->default(false);

            $table->integer('semester_id')->unsigned()->nullable();
            $table->foreign('semester_id')
                  ->references('id')->on('semesters');

            $table->integer('study_program_id')->unsigned()->nullable();
            $table->foreign('study_program_id')
                  ->references('id')->on('study_programs');

            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('company_id')
                  ->references('id')->on('companies');

            $table->rememberToken();
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
        Schema::dropIfExists('students');
    }
}
