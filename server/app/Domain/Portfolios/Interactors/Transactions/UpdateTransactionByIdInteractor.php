<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Transactions;

use App\Domain\Markets\Models\Coin;
use App\Domain\Portfolios\Entities\Transaction as TransactionEntity;
use App\Domain\Portfolios\Models\Portfolio;
use App\Domain\Portfolios\Models\Transaction;
use App\Domain\Portfolios\Services\FinanceCalculator;
use Illuminate\Database\Eloquent\Builder;

final class UpdateTransactionByIdInteractor
{
    private FinanceCalculator $calculator;

    public function __construct(FinanceCalculator $financeCalculator)
    {
        $this->calculator = $financeCalculator;
    }

    public function execute(UpdateTransactionByIdRequest $request): UpdateTransactionByIdResponse
    {
        $transaction = Transaction::with(['portfolio', 'coin', 'coin.marketData'])
            ->whereId($request->transactionId)
            ->whereHas('portfolio', fn (Builder $query) => $query->whereUserId($request->userId))
            ->firstOrFail();

        if ($request->portfolioId) {
            $portfolio = Portfolio::whereId($request->portfolioId)
                ->whereUserId($request->userId)
                ->firstOrFail();
            $transaction->portfolio_id = $portfolio->id;
        }

        if ($request->coinId) {
            $coin = Coin::findOrFail($request->coinId, ['id']);
            $transaction->coin_id = $coin->id;
        }

        if ($request->type) {
            $transaction->type = $request->type->value;
        }

        if ($request->pricePerCoin) {
            $transaction->price_per_coin = $request->pricePerCoin;
        }

        if ($request->quantity) {
            $transaction->quantity = $request->quantity;
        }

        if ($request->fee) {
            $transaction->fee = $request->fee;
        }

        if ($request->datetime) {
            $transaction->datetime = $request->datetime;
        }

        $transaction->save();

        $cost = $this->calculator->cost($transaction->quantity, $transaction->price_per_coin, $transaction->fee);
        $currentValue = $this->calculator->value($transaction->quantity, $transaction->coin->marketData->price);
        $valueChange = $this->calculator->valueChange($currentValue, $cost);

        $transactionEntity = TransactionEntity::fromModel($transaction);

        $transactionEntity->cost = $cost;
        $transactionEntity->currentValue = $currentValue;
        $transactionEntity->valueChange = $valueChange;

        return new UpdateTransactionByIdResponse([
            'transaction' => $transactionEntity,
        ]);
    }
}
