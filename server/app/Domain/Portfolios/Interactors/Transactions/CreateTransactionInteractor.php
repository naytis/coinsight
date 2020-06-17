<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Transactions;

use App\Domain\Markets\Models\Coin;
use App\Domain\Portfolios\Entities\Transaction as TransactionEntity;
use App\Domain\Portfolios\Models\Portfolio;
use App\Domain\Portfolios\Models\Transaction;
use App\Domain\Portfolios\Services\FinanceCalculator;

final class CreateTransactionInteractor
{
    private FinanceCalculator $calculator;

    public function __construct(FinanceCalculator $financeCalculator)
    {
        $this->calculator = $financeCalculator;
    }

    public function execute(CreateTransactionRequest $request): CreateTransactionResponse
    {
        $portfolio = Portfolio::whereId($request->portfolioId)
            ->whereUserId($request->userId)
            ->firstOrFail();

        $coin = Coin::with('marketData:id,coin_id,price')
            ->findOrFail($request->coinId, ['id']);

        $transaction = new Transaction();
        $transaction->type = $request->type->value;
        $transaction->price_per_coin = $request->pricePerCoin;
        $transaction->quantity = $request->quantity;
        $transaction->fee = $request->fee;
        $transaction->datetime = $request->datetime ?? now();
        $transaction->portfolio_id = $portfolio->id;
        $transaction->coin_id = $coin->id;

        $transaction->save();
        $transaction->refresh();

        $cost = $this->calculator->cost($request->quantity, $request->pricePerCoin, $request->fee);
        $currentValue = $this->calculator->value($request->quantity, $coin->marketData->price);
        $valueChange = $this->calculator->valueChange($currentValue, $cost);

        $transactionEntity = TransactionEntity::fromModel($transaction);

        $transactionEntity->cost = $cost;
        $transactionEntity->currentValue = $currentValue;
        $transactionEntity->valueChange = $valueChange;

        return new CreateTransactionResponse([
            'transaction' => $transactionEntity,
        ]);
    }
}
