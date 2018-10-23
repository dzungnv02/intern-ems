<?php

use Faker\Generator as Faker;

$factory->define(App\Examination::class, function (Faker $faker) {
    return [
        'name'=> "hoc ky 10",
        'start_day'=> "2018-08-30 00:00:00",
        'duration'=> 100,
        'note'=> "no",
        'class_id' => 2,
    ];
});
