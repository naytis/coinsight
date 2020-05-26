<?php

declare(strict_types=1);

namespace App\Coinfo;

use App\Coinfo\Aggregators\CoinGecko;
use App\Coinfo\Aggregators\Messari;
use App\Coinfo\Enums\Interval;
use App\Coinfo\Types\CoinHistoricalData;
use App\Coinfo\Types\CoinMarketDataCollection;
use App\Coinfo\Types\CoinProfile;
use App\Coinfo\Types\CoinOHLCVCollection;
use App\Coinfo\Types\CoinHistoricalDataCollection;
use App\Coinfo\Types\NewsArticleCollection;
use Carbon\Carbon;
use Illuminate\Support\Str;

final class Client
{
    private CoinGecko $coinGecko;
    private Messari $messari;

    public function __construct(
        CoinGecko $coinGecko,
        Messari $messari
    ) {
        $this->coinGecko = $coinGecko;
        $this->messari = $messari;
    }

    public function markets(int $page = 1, int $perPage = 100): CoinMarketDataCollection
    {
        return $this->coinGecko->coinsMarkets($page, $perPage, [], false, true);
    }

    public function marketsForCoins(array $currenciesNames): CoinMarketDataCollection
    {
        $slugged = array_map(
            fn(string $currencyName) => Str::slug($currencyName),
            $currenciesNames
        );
        return $this->coinGecko->coinsMarkets(0, 0, $slugged, true);
    }

    public function coinProfile(string $currencyName): CoinProfile
    {
        return $this->messari->assetProfile(Str::slug($currencyName));
    }

    public function coinHistoricalData(string $currencyName, int $days): CoinHistoricalDataCollection {
        return $this->coinGecko->coinMarketChart(Str::slug($currencyName), $days);
    }

    public function coinHistoricalDataAllTime(string $currencyName): CoinHistoricalDataCollection
    {
        return $this->coinGecko->coinMarketChart(Str::slug($currencyName), 'max');
    }

    public function coinOHLCV(
        string $currencyName,
        ?Carbon $start = null,
        ?Carbon $end = null,
        ?Interval $interval = null
    ): CoinOHLCVCollection {
        return $this->messari->assetTimeseries(
            Str::slug($currencyName),
            $start,
            $end,
            $interval
        );
    }

    public function news(int $page = 1): NewsArticleCollection
    {
        return $this->messari->news($page);
    }
}
