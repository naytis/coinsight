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
    use CoinfoDataProvider;
    use RefreshDatabase;

    private int $portfolioId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeCoinfo();

        $this->portfolioId = factory(Portfolio::class)->create()->id;

        $coinId = factory(Coin::class)->create([
            'name' => $this->currencyName(),
            'symbol' => $this->currencySymbol(),
            'coin_gecko_id' => $this->currencyCoinGeckoId(),
        ])->id;

        factory(CoinMarketData::class)->create([
            'coin_id' => $coinId
        ]);

        factory(Transaction::class, 5)->create([
            'portfolio_id' => $this->portfolioId,
            'coin_id' => $coinId,
        ]);
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
        $this
            ->apiGet('/portfolios')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'portfolios' => [
                        '*' => $this->portfolioStructure(),
                    ],
                ],
                'meta' => $this->metaStructure(),
            ]);
    }

    public function test_get_overview()
    {
        $this
            ->apiGet("/portfolios/{$this->portfolioId}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'overview' => $this->overviewStructure(),
                ],
            ]);
    }

    public function test_get_chart()
    {
        $this
            ->apiGet("/portfolios/{$this->portfolioId}/chart")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'chart' => $this->chartStructure(),
                ],
            ]);

    }

    public function test_get_assets()
    {
        $this
            ->apiGet("/portfolios/{$this->portfolioId}/assets")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'assets' => $this->assetsStructure(),
                ],
                'meta' => $this->metaStructure(),
            ]);
    }

    public function test_update_portfolio()
    {
        $this
            ->apiPut("/portfolios/{$this->portfolioId}", [
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
        $this
            ->apiDelete("/portfolios/{$this->portfolioId}")
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

    private function overviewStructure(): array
    {
        return [
            'portfolio' => $this->portfolioStructure(),
            'total_value',
            'total_value_change',
        ];
    }

    private function chartStructure(): array
    {
        return [
            '*' => [
                'timestamp',
                'value',
            ],
        ];
    }

    private function assetsStructure(): array
    {
        return [
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
        ];
    }
}
