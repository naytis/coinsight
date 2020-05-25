<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Transactions;

use App\Domain\Markets\Services\CoinService;
use App\Domain\Portfolios\Entities\Transaction as TransactionEntity;
use App\Domain\Portfolios\Models\Transaction;
use App\Domain\Portfolios\Services\PortfolioService;
use App\Domain\Portfolios\Services\FinanceCalculator;
use App\Domain\Portfolios\Services\TransactionService;

final class CreateTransactionInteractor
{
    private FinanceCalculator $calculator;
    private PortfolioService $portfolioService;
    private CoinService $coinService;
    private TransactionService $transactionService;

    public function __construct(
        FinanceCalculator $financeCalculator,
        PortfolioService $portfolioService,
        CoinService $coinService,
        TransactionService $transactionService
    ) {
        $this->calculator = $financeCalculator;
        $this->portfolioService = $portfolioService;
        $this->coinService = $coinService;
        $this->transactionService = $transactionService;
    }

    public function execute(CreateTransactionRequest $request): CreateTransactionResponse
    {
        $portfolio = $this->portfolioService->getByIdAndUserId($request->portfolioId, $request->userId);

        $coin = $this->coinService->getById($request->coinId, ['marketData']);

        $transaction = new Transaction();
        $transaction->type = $request->type->value;
        $transaction->price_per_coin = $request->pricePerCoin;
        $transaction->quantity = $request->quantity;
        $transaction->fee = $request->fee;
        $transaction->datetime = $request->datetime ?? now();
        $transaction->portfolio_id = $portfolio->id;
        $transaction->coin_id = $coin->id;

        $transaction = $this->transactionService->store($transaction);

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
