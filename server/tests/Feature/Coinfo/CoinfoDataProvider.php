<?php

declare(strict_types=1);

namespace Tests\Feature\Coinfo;

use App\Coinfo\Aggregators\CoinGecko;
use App\Coinfo\Aggregators\Messari;
use Illuminate\Support\Facades\Http;

trait CoinfoDataProvider
{
    public function currencyCoinGeckoId(): string
    {
        return 'coin-gecko-id';
    }

    public function currencyName(): string
    {
        return 'Currency Name';
    }

    public function currencySymbol(): string
    {
        return 'SYMBOL';
    }

    public function fakeCoinfo(): void
    {
        $requestsMap = [];
        foreach ($this->methodMapper() as $coinfoMethod) {
            $requestsMap[$coinfoMethod['url']] = Http::response($coinfoMethod['response']);
        }
        Http::fake($requestsMap);
    }

    private function methodMapper(): array
    {
        return [
            'markets' => [
                'url' => $this->getEndpointUrlWithWildcard(
                    CoinGecko::BASE_URL,
                    'coins/markets',
                ),
                'response' => $this->fakeCoinGeckoMarketsResponse(),
            ],
            'coinProfile' => [
                'url' => $this->getEndpointUrlWithWildcard(
                    Messari::BASE_URL,
                    'assets/currency-name/profile',
                ),
                'response' => $this->fakeCoinProfileResponse(),
            ],
            'coinHistoricalData' => [
                'url' => $this->getEndpointUrlWithWildcard(
                    CoinGecko::BASE_URL,
                    '/coins/coin-gecko-id/market_chart',
                ),
                'response' => $this->fakeCoinHistoricalDataResponse(),
            ],
            'news' => [
                'url' => $this->getEndpointUrlWithWildcard(
                    Messari::BASE_URL,
                    'news',
                ),
                'response' => $this->fakeNewsResponse(),
            ],
            '*' => [
                'url' => '*',
                'response' => ['fallback'],
            ]
        ];
    }

    public function fakeCoinGeckoMarketsResponse(): array
    {
        return [
            [
                'id' => 'id',
                'image' => 'icon1',
                'name' => 'name1',
                'symbol' => 'symbol1',
                'market_cap_rank' => 1,
                'current_price' => 1234.567,
                'total_volume' => 1234.567,
                'market_cap' => 1234.567,
                'price_change_percentage_24h' => -12.34,
                'circulating_supply' => 1,
                'total_supply' => 1,
                'sparkline_in_7d' => [
                    'price' => [
                        1,
                    ],
                ],
            ],
            [
                'id' => 'id',
                'image' => 'icon2',
                'name' => 'name2',
                'symbol' => 'symbol2',
                'market_cap_rank' => 2,
                'current_price' => 123.45,
                'total_volume' => 123.45,
                'market_cap' => 123.45,
                'price_change_percentage_24h' => -12.34,
                'circulating_supply' => 1,
                'total_supply' => 1,
                'sparkline_in_7d' => [
                    'price' => [
                        1,
                    ],
                ],
            ],
            [
                'id' => 'id',
                'image' => 'icon3',
                'name' => 'Currency Name',
                'symbol' => 'SYMBOL',
                'market_cap_rank' => 3,
                'current_price' => 123.45,
                'total_volume' => 123.45,
                'market_cap' => 123.45,
                'price_change_percentage_24h' => -12.34,
                'circulating_supply' => 1,
                'total_supply' => 1,
                'sparkline_in_7d' => [
                    'price' => [
                        1,
                    ],
                ],
            ],
        ];
    }

    public function fakeCoinProfileResponse(): array
    {
        return [
            'data' => [
                'name' => 'name',
                'symbol' => null,
                'profile' => [
                    'general' => [
                        'overview' => [
                            'tagline' => 'tagline',
                            'project_details' => 'project_details',
                            'official_links' => [
                                [
                                    'name' => 'name1',
                                    'link' => null,
                                ],
                                [
                                    'name' => 'name2',
                                    'link' => 'link',
                                ]
                            ]
                        ],
                    ],
                    'economics' => [
                        'token' => [
                            'token_type' => 'token_type',
                            'block_explorers' => null,
                        ],
                        'launch' => [
                            'initial_distribution' => [
                                'genesis_block_date' => '2009-01-03T09:00:00Z'
                            ]
                        ],
                        'consensus_and_emission' => [
                            'consensus' => [
                                'general_consensus_mechanism' => 'general_consensus_mechanism',
                                'mining_algorithm' => 'mining_algorithm'
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public function fakeCoinHistoricalDataResponse(): array
    {
        return [
            'prices' => [
                [
                    1586901867594,
                    123.45,
                ],
                [
                    1586901867594,
                    123.45,
                ],
                [
                    1586901867594,
                    123.45,
                ],
            ],
            'market_caps' => [
                [
                    1586901867594,
                    12345,
                ],
                [
                    1586901867594,
                    12345,
                ],
                [
                    1586901867594,
                    12345,
                ],
            ],
            'total_volumes' => [
                [
                    1586901867594,
                    12345,
                ],
                [
                    1586901867594,
                    12345,
                ],
                [
                    1586901867594,
                    12345,
                ],
            ],
        ];
    }

    public function fakeNewsResponse(): array
    {
        return [
            'data' => [
                [
                    'title' => 'title',
                    'content' => 'content',
                    'published_at' => '2020-01-01T12:00:00Z',
                    'author' => [
                        'name' => 'name1',
                    ],
                ],
                [
                    'title' => 'title2',
                    'content' => 'content2',
                    'published_at' => '2020-01-01T12:00:00Z',
                    'author' => [
                        'name' => 'name2',
                    ],
                ],
            ],
        ];
    }
}
