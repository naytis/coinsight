<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Transactions;

use App\Domain\Common\Responses\PaginationMeta;
use App\Domain\Portfolios\Entities\Transaction as TransactionEntity;
use App\Domain\Portfolios\Models\Portfolio;
use App\Domain\Portfolios\Models\Transaction;
use App\Domain\Portfolios\Services\FinanceCalculator;
use Illuminate\Database\Eloquent\Builder;

final class GetTransactionsInteractor
{
    private FinanceCalculator $calculator;

    public function __construct(FinanceCalculator $financeCalculator)
    {
        $this->calculator = $financeCalculator;
    }

    public function execute(GetTransactionsRequest $request): GetTransactionsResponse
    {
        Portfolio::whereId($request->portfolioId)
            ->whereUserId($request->userId)
            ->firstOrFail();

        $transactionsPaginator = Transaction::with(['coin', 'coin.marketData'])
            ->orderBy($request->sort, $request->direction)
            ->wherePortfolioId($request->portfolioId)
            ->whereHas('portfolio', fn (Builder $query) => $query->whereUserId($request->userId))
            ->paginate($request->perPage, ['*'], null, $request->page);

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
