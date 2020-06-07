<?php

declare(strict_types=1);

namespace App\Coinfo\Aggregators;

use App\Coinfo\Factories\Messari\CoinProfileFactory;
use App\Coinfo\Factories\Messari\NewsArticleCollectionFactory;
use App\Coinfo\Types\CoinProfile;
use App\Coinfo\Types\NewsArticleCollection;

final class Messari extends Aggregator
{
    public const BASE_URL = 'https://data.messari.io/api/%ver%/';

    private string $apiVersion = 'v2';

    public function assetProfile(string $sluggedName): CoinProfile
    {
        $this->apiVersion = 'v2';

        $data = $this->request("assets/{$sluggedName}/profile");

        return CoinProfileFactory::create($data);
    }

    public function news(int $page = 1): NewsArticleCollection
    {
        $this->apiVersion = 'v1';

        $data = $this->request("news", [
           'page' => $page,
        ]);
        return NewsArticleCollectionFactory::create($data);
    }

    public function apiVersion(): string
    {
        return $this->apiVersion;
    }
}
