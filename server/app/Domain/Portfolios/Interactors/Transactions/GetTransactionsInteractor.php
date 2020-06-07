<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Transactions;

use App\Domain\Common\Responses\PaginationMeta;
use App\Domain\Portfolios\Entities\Transaction as TransactionEntity;
use App\Domain\Portfolios\Models\Transaction;
use App\Domain\Portfolios\Services\FinanceCalculator;
use App\Domain\Portfolios\Services\PortfolioService;
use App\Domain\Portfolios\Services\TransactionService;

final class GetTransactionsInteractor
{
    private FinanceCalculator $calculator;
    private PortfolioService $portfolioService;
    private TransactionService $transactionService;

    public function __construct(
        FinanceCalculator $financeCalculator,
        PortfolioService $portfolioService,
        TransactionService $transactionService
    ) {
        $this->calculator = $financeCalculator;
        $this->portfolioService = $portfolioService;
        $this->transactionService = $transactionService;
    }

    public function execute(GetTransactionsRequest $request): GetTransactionsResponse
    {
        $this->portfolioService->getByIdAndUserId($request->portfolioId, $request->userId);

        $transactionsPaginator = $this->transactionService->paginateByPortfolioIdAndUserId(
            $request->portfolioId,
            $request->userId,
            $request->page,
            $request->perPage,
            $request->sort,
            $request->direction,
            ['coin', 'coin.marketData']
        );

        if ($transactionsPaginator->isEmpty()) {
            return new GetTransactionsResponse([
                'transactions' => collect(),
                'meta' => PaginationMeta::fromPaginator($transactionsPaginator),
            ]);
        }

        $transactionEntityCollection = $transactionsPaginator->map(
            function (Transaction $transaction) {
                $cost = $this->calculator->cost(
                    $transaction->quantity, $transaction->price_per_coin, $transaction->fee
                );
                $currentValue = $this->calculator->value(
                    $transaction->quantity, $transaction->coin->marketData->price
                );
                $valueChange = $this->calculator->valueChange($currentValue, $cost);

                $transactionEntity = TransactionEntity::fromModel($transaction);
                $transactionEntity->cost = $cost;
                $transactionEntity->currentValue = $currentValue;
                $transactionEntity->valueChange = $valueChange;

                return $transactionEntity;
            }
        );

        return new GetTransactionsResponse([
            'transactions' => $transactionEntityCollection,
            'meta' => PaginationMeta::fromPaginator($transactionsPaginator),
        ]);
    }
}
