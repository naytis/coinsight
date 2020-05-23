<?php

declare(strict_types=1);

namespace App\Domain\Markets\Models;

use Illuminate\Database\Eloquent\Model;

final class CoinMarketData extends Model
{
    protected $fillable = [
        'price',
        'price_change_24h',
        'market_cap',
        'volume',
        'circulating_supply',
        'coin_id',
    ];

    protected $casts = [
        'price' => 'float',
        'price_change_24h' => 'float',
        'market_cap' => 'float',
        'volume' => 'float',
        'circulating_supply' => 'float',
    ];
}
