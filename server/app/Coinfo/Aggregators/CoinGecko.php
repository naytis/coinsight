<?php

declare(strict_types=1);

namespace App\Coinfo\Aggregators;

use App\Coinfo\Factories\CoinGecko\CoinMarketDataCollectionFactory;
use App\Coinfo\Factories\CoinGecko\CoinHistoricalDataCollectionFactory;
use App\Coinfo\Types\CoinMarketDataCollection;
use App\Coinfo\Types\CoinHistoricalDataCollection;

final class CoinGecko extends Aggregator
{
    public const BASE_URL = 'https://api.coingecko.com/api/%ver%/';

    private string $apiVersion = 'v3';

    public function coinsMarkets(
        int $page = 1,
        int $perPage = 100,
        array $ids = [],
        bool $sparkline = false,
        bool $priceChangePercentage = false
    ): CoinMarketDataCollection {
        $data = $this->request('coins/markets', [
            'vs_currency' => 'usd',
            'page' => $page,
            'per_page' => $perPage,
            'ids' => implode(",", $ids),
            'sparkline' => $sparkline ? "true" : "false",
            'price_change_percentage' => $priceChangePercentage ? '1h,7d,30d,1y' : '',
        ]);

        return CoinMarketDataCollectionFactory::create($data);
    }

    public function coinMarketChart(string $id, $days): CoinHistoricalDataCollection
    {
        $data = $this->request("/coins/{$id}/market_chart", [
            'vs_currency' => 'usd',
            'days' => $days,
        ]);

        return CoinHistoricalDataCollectionFactory::create($data);
    }

    public function apiVersion(): string
    {
        return $this->apiVersion;
    }
}
