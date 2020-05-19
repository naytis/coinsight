<?php

declare(strict_types=1);

namespace App\Coinfo\Aggregators;

use App\Coinfo\Enums\Interval;
use App\Coinfo\Factories\Messari\CoinOHLCVCollectionFactory;
use App\Coinfo\Factories\Messari\CoinProfileFactory;
use App\Coinfo\Factories\Messari\NewsArticleCollectionFactory;
use App\Coinfo\Types\CoinOHLCVCollection;
use App\Coinfo\Types\CoinProfile;
use App\Coinfo\Types\NewsArticleCollection;
use Carbon\Carbon;

final class Messari extends Aggregator
{
    public const BASE_URL = 'https://data.messari.io/api/v1/';

    public function assetProfile(string $sluggedName): CoinProfile
    {
        $data = $this->request("assets/{$sluggedName}/profile");

        return CoinProfileFactory::create($data);
    }

    public function assetTimeseries(
        string $asset,
        ?Carbon $start = null,
        ?Carbon $end = null,
        ?Interval $interval = null
    ): CoinOHLCVCollection {
        $end ??= $now = Carbon::now();
        $start ??= $now->subDay();
        $interval ??= Interval::FIVE_MINUTES;

        $data = $this->request("assets/{$asset}/metrics/price/time-series", [
            'start' => $start,
            'end' => $end,
            'interval' => $interval,
        ]);

        return CoinOHLCVCollectionFactory::create($data);
    }

    public function news(int $page = 1): NewsArticleCollection
    {
        $data = $this->request("news", [
           'page' => $page,
        ]);
        return NewsArticleCollectionFactory::create($data);
    }
}
