<?php

use Faker\Generator as Faker;

$factory->define(App\Student::class, function (Faker $faker) {
    return [
        'name' => 'anh',
        'email' => 'asds@gmail.com',
        'address' => 'nam dinh',
        'mobile' => '01546523156',
        'birthday' => '2000-10-08',
        'gender' => 1,
        'created_at' => '2018-27-08',
        'updated_at' => '2018-27-08',
    ];
});
