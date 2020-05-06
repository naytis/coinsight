<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Services;

use App\Domain\Portfolios\Exceptions\TransactionNotFound;
use App\Domain\Portfolios\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class TransactionService
{
    public function getCollectionByPortfolioId(int $portfolioId, array $withRelations = []): Collection
    {
        return Transaction::with($withRelations)->wherePortfolioId($portfolioId)->get();
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
