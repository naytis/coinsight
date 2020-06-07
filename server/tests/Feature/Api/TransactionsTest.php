<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Domain\Markets\Models\Coin;
use App\Domain\Markets\Models\CoinMarketData;
use App\Domain\Portfolios\Enums\TransactionType;
use App\Domain\Portfolios\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\Feature\Coinfo\CoinfoDataProvider;

final class TransactionsTest extends ApiTestCase
{
    use RefreshDatabase, CoinfoDataProvider;

    private int $coinId;
    private int $portfolioId;
    private int $transactionId;

    public function setUp(): void
    {
        parent::setUp();
        $this->fakeCoinfo();
        $this->coinId = factory(Coin::class)
            ->create([
                'name' => $this->currencyName(),
                'symbol' => $this->currencySymbol(),
            ])
            ->id;
        factory(CoinMarketData::class)->create([
            'coin_id' => $this->coinId,
        ]);
        $this->portfolioId = factory(Portfolio::class)->create()->id;
        $this->transactionId = DB::table('transactions')->insertGetId([
            'type' => TransactionType::BUY,
            'price_per_coin' => 1,
            'quantity' => 1,
            'fee' => 1,
            'datetime' => now(),
            'portfolio_id' => $this->portfolioId,
            'coin_id' => $this->coinId,
        ]);
    }

    public function test_create_transaction()
    {
        $this
            ->apiPost("/portfolios/{$this->portfolioId}/transactions/", [
                'coin_id' => $this->coinId,
                'type' => TransactionType::BUY,
                'price_per_coin' => 1,
                'quantity' => 1,
                'fee' => 1,
                'datetime' => now(),
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'transaction' => $this->transactionStructure(),
                ],
            ]);
    }

    public function test_get_transactions()
    {
        $this
            ->apiGet("/portfolios/{$this->portfolioId}/transactions/")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'transactions' => [
                        '*' => $this->transactionStructure(),
                    ],
                ],
            ]);
    }

    public function test_update_transaction()
    {
        $this
            ->apiPut("/portfolios/{$this->portfolioId}/transactions/{$this->transactionId}", [
                'price_per_coin' => 2,
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'transaction' => $this->transactionStructure(),
                ],
            ]);
    }

    public function test_delete_transaction()
    {
        $this
            ->apiDelete("/portfolios/{$this->portfolioId}/transactions/{$this->transactionId}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                ],
            ]);
    }

    private function transactionStructure(): array
    {
        return [
            'id',
            'coin' => [
                'id',
                'name',
                'symbol',
                'icon',
            ],
            'type',
            'price_per_coin',
            'quantity',
            'fee',
            'cost',
            'current_value',
            'value_change',
            'datetime',
            'portfolio_id',
        ];
    }
}
