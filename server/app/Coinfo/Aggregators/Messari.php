<?php

declare(strict_types=1);

namespace App\Coinfo\Aggregators;

use App\Coinfo\Factories\Messari\CoinProfileFactory;
use App\Coinfo\Factories\Messari\NewsArticleCollectionFactory;
use App\Coinfo\Types\CoinProfile;
use App\Coinfo\Types\NewsArticleCollection;

final class Messari extends Aggregator
{
    public const BASE_URL = 'https://data.messari.io/api/v1/';

    public function assetProfile(string $sluggedName): CoinProfile
    {
        $data = $this->request("assets/{$sluggedName}/profile");

        return CoinProfileFactory::create($data);
    }

    public function news(int $page = 1): NewsArticleCollection
    {
        $data = $this->request("news", [
           'page' => $page,
        ]);
        return NewsArticleCollectionFactory::create($data);
    }
}
