<?php

use Faker\Generator as Faker;

$factory->define(App\Courses::class, function (Faker $faker) {
    return [
       'name'=>'Lap trinh',
       'code'=>'LR002',
       'duration'=>rand(10,100),
       'fee'=>rand(1000000,5000000),
       'curriculum'=>str_random(50),
       'level'=>rand(1,3)
    ];
});
