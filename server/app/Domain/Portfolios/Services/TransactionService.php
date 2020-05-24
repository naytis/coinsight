<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Services;

use App\Domain\Portfolios\Exceptions\TransactionNotFound;
use App\Domain\Portfolios\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class TransactionService
{
    public function paginateByPortfolioIdAndUserId(
        int $portfolioId,
        int $userId,
        int $page,
        int $perPage,
        string $sort,
        string $direction,
        array $withRelations = []
    ): LengthAwarePaginator {
        return Transaction::with($withRelations)
            ->orderBy($sort, $direction)
            ->wherePortfolioId($portfolioId)
            ->whereHas('portfolio', fn (Builder $query) => $query->whereUserId($userId))
            ->paginate($perPage, ['*'], null, $page);
    }

    public function getByIdAndUserId(int $transactionId, int $userId, array $withRelations = []): Transaction
    {
        try {
            return Transaction::with(['portfolio', ...$withRelations])
                ->whereId($transactionId)
                ->whereHas('portfolio', fn (Builder $query) => $query->whereUserId($userId))
                ->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            throw new TransactionNotFound();
        }
    }

    public function store(Transaction $transaction): Transaction
    {
        $transaction->save();
        return $transaction->fresh();
    }
}
