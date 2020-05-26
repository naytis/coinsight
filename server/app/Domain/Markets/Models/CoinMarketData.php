<?php

declare(strict_types=1);

namespace App\Domain\Markets\Models;

use Illuminate\Database\Eloquent\Model;

final class CoinMarketData extends Model
{
    protected $fillable = [
        'price',
        'price_change_1h',
        'price_change_24h',
        'price_change_7d',
        'price_change_30d',
        'price_change_1y',
        'market_cap',
        'volume',
        'circulating_supply',
        'max_supply',
        'coin_id',
    ];

    protected $casts = [
        'price' => 'float',
        'price_change_1h' => 'float',
        'price_change_24h' => 'float',
        'price_change_7d' => 'float',
        'price_change_30d' => 'float',
        'price_change_1y' => 'float',
        'market_cap' => 'float',
        'volume' => 'float',
        'circulating_supply' => 'float',
        'max_supply' => 'float',
    ];
}
