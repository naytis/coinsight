<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Domain\Markets\Models\Coin;
use App\Domain\Markets\Models\CoinMarketData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

final class GlobalStatsTest extends ApiTestCase
{
    use RefreshDatabase;

    public function test_global_stats()
    {
        $coinId = factory(Coin::class)->create([
            'name' => 'Bitcoin',
        ])->id;
        factory(CoinMarketData::class)->create([
            'coin_id' => $coinId,
        ]);
        $this
            ->apiGet('/global')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'market_cap',
                    'volume',
                    'bitcoin_dominance',
                ],
                'meta' => [],
            ]);
    }
}
