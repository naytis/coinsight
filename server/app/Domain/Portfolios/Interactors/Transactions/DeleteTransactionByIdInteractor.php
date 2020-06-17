<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Transactions;

use App\Domain\Portfolios\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;

final class DeleteTransactionByIdInteractor
{
    public function execute(DeleteTransactionByIdRequest $request): DeleteTransactionByIdResponse
    {
        $transaction = Transaction::whereId($request->transactionId)
            ->whereHas('portfolio', fn (Builder $query) => $query->whereUserId($request->userId))
            ->firstOrFail();

        $transaction->delete();

        return new DeleteTransactionByIdResponse([
            'id' => $transaction->id,
        ]);
    }
}
