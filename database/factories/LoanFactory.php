<?php

use App\Client;
use App\LevelAmount;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(App\Loan::class, function (Faker $faker) {
    return [
        'status'            => 1,
        'client_uuid'       => Client::first()->uuid,
        'level_amount_uuid' => LevelAmount::first()->uuid,
        'comment'           => null,
        'wallet_uuid'       => Str::uuid(),
    ];
});
