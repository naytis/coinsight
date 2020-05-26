<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\Markets\Models\Coin;
use App\Domain\Markets\Models\CoinMarketData;

$factory->define(CoinMarketData::class, function () {
    return [
        'price' => 1,
        'price_change_1h' => 1,
        'price_change_24h' => 1,
        'price_change_7d' => 1,
        'price_change_30d' => 1,
        'price_change_1y' => 1,
        'market_cap' => 1,
        'volume' => 1,
        'circulating_supply' => 1,
        'max_supply' => 1,
        'coin_id' => Coin::first()->id,
    ];
});
