<?php

use App\Models\User;
use Faker\Generator as Faker;

$factory->define(App\Models\Faculty::class, function (Faker $faker) {
	$users = User::pluck('id')->toArray();

    return [
        'name' => $faker->name,
        'created_by' =>  $faker->randomElement($users),
    ];
});
