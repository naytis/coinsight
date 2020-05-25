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
use Tests\Feature\Coinfo\CoinfoDataProvider;

final class TransactionsTest extends ApiTestCase
{
    use RefreshDatabase, CoinfoDataProvider;

    private int $coinId;
    private int $portfolioId;

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
    }

    public function test_create_transaction()
    {
        $this
            ->apiPost('/transactions', [
                'portfolio_id' => $this->portfolioId,
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
                ],
            ]);
    }

    public function test_get_transactions()
    {
        DB::table('transactions')->insert([
            'type' => TransactionType::BUY,
            'price_per_coin' => 1,
            'quantity' => 1,
            'fee' => 1,
            'datetime' => now(),
            'portfolio_id' => $this->portfolioId,
            'coin_id' => $this->coinId,
        ]);

        $this
            ->apiGet('/transactions', [
                'portfolio_id' => $this->portfolioId,
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'transactions' => [
                        '*' => [
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
                        ],
                    ],
                ],
            ]);
    }

    public function test_update_transaction()
    {
        $transactionId = DB::table('transactions')->insertGetId([
            'type' => TransactionType::BUY,
            'price_per_coin' => 1,
            'quantity' => 1,
            'fee' => 1,
            'datetime' => now(),
            'portfolio_id' => $this->portfolioId,
            'coin_id' => $this->coinId,
        ]);

        $this
            ->apiPut("/transactions/{$transactionId}", [
                'price_per_coin' => 2,
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
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
                ],
            ]);
    }

    public function test_delete_transaction()
    {
        $transactionId = DB::table('transactions')->insertGetId([
            'type' => TransactionType::BUY,
            'price_per_coin' => 1,
            'quantity' => 1,
            'fee' => 1,
            'datetime' => now(),
            'portfolio_id' => $this->portfolioId,
            'coin_id' => $this->coinId,
        ]);

        $this
            ->apiDelete("/transactions/{$transactionId}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                ],
            ]);
    }
}
