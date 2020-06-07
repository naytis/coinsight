<?php

declare(strict_types=1);

namespace App\Http\Markets\Mappers;

use App\Domain\Markets\Entities\Coin;

final class CoinMapper
{
    public static function map(Coin $coin): array
    {
        return [
            'id' => $coin->id,
            'name' => $coin->name,
            'symbol' => $coin->symbol,
            'icon' => url($coin->icon),
        ];
    }
}
