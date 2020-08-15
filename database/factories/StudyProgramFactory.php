<?php

use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(StudyProgram::class, function (Faker $faker) {
	$users = User::pluck('id')->toArray();
	$faculties = Faculty::pluck('id')->toArray();

    return [
        'code' => str_random(3),
        'name' => $faker->name,
        'faculty_id' =>  $faker->randomElement($faculties),
        'created_by' =>  $faker->randomElement($users),
    ];
});
