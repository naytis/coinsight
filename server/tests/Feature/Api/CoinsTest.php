<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Domain\Markets\Models\Coin;
use App\Domain\Markets\Models\CoinMarketData;
use App\Domain\Markets\Models\CoinProfile;
use App\Domain\Markets\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Coinfo\CoinfoDataProvider;

final class CoinsTest extends ApiTestCase
{
    use RefreshDatabase, CoinfoDataProvider;

    public function setUp(): void
    {
        parent::setUp();
        $this->fakeCoinfo();
    }

    public function test_get_coins()
    {
        $coinId = DB::table('coins')->insertGetId([
            'name' => 'name1',
            'symbol' => 'symbol1',
            'icon' => 'icon1',
        ]);
        factory(CoinMarketData::class)->create([
            'coin_id' => $coinId,
        ]);

        $this
            ->apiGet('/coins', [
                'page' => 1,
                'per_page' => 5
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'coins' => [
                        '*' => [
                            'id',
                            'name',
                            'symbol',
                            'icon',
                            'rank',
                            'price',
                            'price_change_24h',
                            'market_cap',
                            'volume',
                        ]
                    ]
                ],
                'meta' => [
                    'total',
                    'page',
                    'per_page',
                ]
            ]);
    }

    public function test_get_profile()
    {
        $coinId = DB::table('coins')->insertGetId([
            'name' => 'currency name',
            'symbol' => 'symbol',
        ]);

        $this
            ->apiGet("/coins/{$coinId}/profile")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'symbol',
                    'icon',
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
                ],
                'meta' => []
            ]);
    }

    public function test_get_latest()
    {
        $coinId = DB::table('coins')->insertGetId([
            'name' => 'currency name',
            'symbol' => 'symbol',
        ]);
        factory(CoinMarketData::class)->create([
            'coin_id' => $coinId,
        ]);

        $this
            ->apiGet("/coins/{$coinId}/latest")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'symbol',
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
                ],
                'meta' => []
            ]);
    }

    public function test_get_historical()
    {
        $coinId = DB::table('coins')->insertGetId([
            'name' => 'currency name',
            'symbol' => 'symbol',
            'coin_gecko_id' => 'coin-gecko-id',
        ]);

        $response = $this->apiGet("/coins/{$coinId}/historical", [
            'period' => '1w'
        ]);

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'data' => [
                'historical_data' => [
                    '*' => [
                        'timestamp',
                        'price',
                        'market_cap',
                        'volume',
                    ],
                ],
            ],
            'meta' => []
        ]);
    }
}
