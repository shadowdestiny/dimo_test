<?php

use Faker\Generator as Faker;
use Ramsey\Uuid\Uuid;

$factory->define(App\Option::class, function (Faker $faker) {
    return [
        'uuid'          => Uuid::uuid4(),
        'text'          => $faker->text(),
    ];
});
