<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Domain\Markets\Models\Coin;
use App\Domain\Markets\Models\CoinMarketData;
use App\Domain\Markets\Models\CoinProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\Feature\Coinfo\CoinfoDataProvider;

final class CoinsTest extends ApiTestCase
{
    use CoinfoDataProvider;
    use RefreshDatabase;

    private int $coinId;

    public function setUp(): void
    {
        parent::setUp();

        $this->fakeCoinfo();

        $this->coinId = factory(Coin::class)->create([
            'name' => $this->currencyName(),
            'symbol' => $this->currencySymbol(),
            'coin_gecko_id' => $this->currencyCoinGeckoId(),
        ])->id;

        factory(CoinMarketData::class)->create([
            'coin_id' => $this->coinId,
        ]);

        factory(CoinProfile::class)->create([
           'coin_id' => $this->coinId,
        ]);
    }

    public function test_get_coins()
    {
        $this
            ->apiGet('/coins', [
                'page' => 1,
                'per_page' => 5
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'coins' => [
                        '*' => $this->overviewStructure(),
                    ],
                ],
                'meta' => $this->metaStructure(),
            ]);
    }

    public function test_get_profile()
    {
        $this
            ->apiGet("/coins/{$this->coinId}/profile")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'coin' => $this->coinStructure(),
                    'profile' => $this->profileStructure(),
                ],
            ]);
    }

    public function test_get_latest()
    {
        $this
            ->apiGet("/coins/{$this->coinId}/latest")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'coin' => $this->coinStructure(),
                    'market_data' => $this->marketDataStructure(),
                ],
            ]);
    }

    public function test_get_historical()
    {
        $this
            ->apiGet("/coins/{$this->coinId}/historical", [
                'period' => '1w'
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'coin' => $this->coinStructure(),
                    'historical_data' => $this->historicalDataStructure(),
                ],
            ]);
    }

    private function coinStructure(): array
    {
        return [
            'id',
            'name',
            'symbol',
            'icon',
        ];
    }

    private function overviewStructure(): array
    {
        return [
            ...$this->coinStructure(),
            'rank',
            'price',
            'price_change_24h',
            'market_cap',
            'volume',
        ];
    }

    private function profileStructure(): array
    {
        return [
            'tagline',
            'description',
            'type',
            'genesis_date',
            'consensus_mechanism',
            'hashing_algorithm',
            'links' => [
                '*' => [
                    'type',
                    'link',
                ]
            ],
        ];
    }

    private function marketDataStructure(): array
    {
        return [
            'circulating_supply',
            'max_supply',
            'price',
            'volume',
            'market_cap',
            'price_change_1h',
            'price_change_24h',
            'price_change_7d',
            'price_change_30d',
            'price_change_1y',
        ];
    }

    private function historicalDataStructure(): array
    {
        return [
            '*' => [
                'timestamp',
                'price',
                'market_cap',
                'volume',
            ],
        ];
    }
}
