<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Domain\Markets\Models\Coin;
use App\Domain\Markets\Models\CoinMarketData;
use App\Domain\Portfolios\Models\Portfolio;
use App\Domain\Portfolios\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\Feature\Coinfo\CoinfoDataProvider;

final class PortfoliosTest extends ApiTestCase
{
    use RefreshDatabase, CoinfoDataProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fakeCoinfo();
    }

    public function test_create_portfolio()
    {
        $this
            ->apiPost('/portfolios', [
                'name' => 'portfolio name',
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'portfolio' => $this->portfolioStructure(),
                ],
            ]);
    }

    public function test_get_portfolios()
    {
        factory(Portfolio::class)->create();

        $this
            ->apiGet('/portfolios')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'portfolios' => [
                        '*' => $this->portfolioStructure(),
                    ],
                ],
                'meta' => [
                    'total',
                    'page',
                    'per_page',
                    'last_page',
                ],
            ]);
    }

    public function test_get_portfolios_when_no_portfolios()
    {
        $this
            ->apiGet('/portfolios')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [],
                'meta' => [],
            ]);
    }

    public function test_get_overview()
    {
        $portfolioId = factory(Portfolio::class)->create()->id;
        $coinId = factory(Coin::class)->create([
            'name' => $this->currencyName(),
            'symbol' => $this->currencySymbol(),
        ])->id;
        factory(Transaction::class)->create([
            'portfolio_id' => $portfolioId,
            'coin_id' => $coinId,
        ]);
        factory(CoinMarketData::class)->create([
            'coin_id' => $coinId
        ]);

        $this
            ->apiGet("/portfolios/{$portfolioId}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'overview' => [
                        'portfolio' => $this->portfolioStructure(),
                        'total_value',
                        'total_value_change',
                    ],
                ],
            ]);
    }

    public function test_get_chart()
    {
        $portfolioId = factory(Portfolio::class)->create()->id;
        $coinId = factory(Coin::class)->create([
            'name' => $this->currencyName(),
            'symbol' => $this->currencySymbol(),
        ])->id;
        factory(Transaction::class)->create([
            'portfolio_id' => $portfolioId,
            'coin_id' => $coinId,
        ]);

        $this
            ->apiGet("/portfolios/{$portfolioId}/chart")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'chart' => [
                        '*' => [
                            'timestamp',
                            'value',
                        ],
                    ],
                ],
            ]);

    }

    public function test_get_assets()
    {
        $portfolioId = factory(Portfolio::class)->create()->id;
        $coinId = factory(Coin::class)->create([
            'name' => $this->currencyName(),
            'symbol' => $this->currencySymbol(),
        ])->id;
        factory(Transaction::class)->create([
            'portfolio_id' => $portfolioId,
            'coin_id' => $coinId,
        ]);
        factory(CoinMarketData::class)->create([
            'coin_id' => $coinId
        ]);

        $this
            ->apiGet("/portfolios/{$portfolioId}/assets")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'assets' => [
                        '*' => [
                            'coin',
                            'price',
                            'price_change_24h',
                            'holdings',
                            'market_value',
                            'net_cost',
                            'net_profit',
                            'percent_change',
                            'share',
                        ],
                    ],
                ],
            ]);
    }

    public function test_update_portfolio()
    {
        $portfolioId = factory(Portfolio::class)->create()->id;
        $this
            ->apiPut("/portfolios/{$portfolioId}", [
                'name' => 'updated name'
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'portfolio' => $this->portfolioStructure(),
                ],
            ]);
    }

    public function test_delete_portfolio()
    {
        $portfolioId = factory(Portfolio::class)->create()->id;
        $this
            ->apiDelete("/portfolios/{$portfolioId}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                ],
            ]);
    }

    private function portfolioStructure(): array
    {
        return [
            'id',
            'name',
        ];
    }
}
