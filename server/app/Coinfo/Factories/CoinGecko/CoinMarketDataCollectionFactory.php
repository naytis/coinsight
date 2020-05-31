<?php

declare(strict_types=1);

namespace App\Coinfo\Factories\CoinGecko;

use App\Coinfo\Types\CoinMarketData;
use App\Coinfo\Types\CoinMarketDataCollection;

final class CoinMarketDataCollectionFactory
{
    public static function create(array $data): CoinMarketDataCollection
    {
        $arrayOfCoins = array_map(
            function($coin) {
                return new CoinMarketData([
                    'id' => $coin['id'],
                    'name' => $coin['name'],
                    'symbol' => $coin['symbol'],
                    'icon' => $coin['image'],
                    'price' => $coin['current_price'],
                    'priceChange1h' => $coin['price_change_percentage_1h_in_currency'] ?? null,
                    'priceChange24h' => $coin['price_change_percentage_24h'] ?? null,
                    'priceChange7d' => $coin['price_change_percentage_7d_in_currency'] ?? null,
                    'priceChange30d' => $coin['price_change_percentage_30d_in_currency'] ?? null,
                    'priceChange1y' => $coin['price_change_percentage_1y_in_currency'] ?? null,
                    'marketCap' => $coin['market_cap'],
                    'volume' => $coin['total_volume'],
                    'circulatingSupply' => $coin['circulating_supply'],
                    'maxSupply' => $coin['total_supply'],
                    'sparkline' => $coin['sparkline_in_7d']['price'] ?? null,
                ]);
            },
            $data
        );
        return new CoinMarketDataCollection($arrayOfCoins);
    }
}
