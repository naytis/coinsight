<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\Markets\Models\Coin;
use App\Domain\Markets\Models\CoinProfile;
use Illuminate\Support\Str;

$factory->define(CoinProfile::class, function () {
    $randomString = Str::random();

    return [
        'tagline' => $randomString,
        'description' => $randomString,
        'type' => $randomString,
        'genesis_date' => now(),
        'consensus_mechanism' => $randomString,
        'hashing_algorithm' => $randomString,
        'coin_id' => Coin::first()->id,
    ];
});
