<?php

declare(strict_types=1);

namespace App\Domain\Markets\Services;

use App\Domain\Markets\Exceptions\CoinNotFound;
use App\Domain\Markets\Models\Coin;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class CoinService
{
    public function getById(int $id, array $withRelations = []): Coin
    {
        try {
            return Coin::with($withRelations)->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new CoinNotFound();
        }
    }

    public function paginate(int $page, int $perPage): LengthAwarePaginator
    {
        return Coin::with(['marketData' => fn ($query) =>
            $query->orderBy('market_cap', 'desc')
        ])
            ->has('marketData')
            ->paginate($perPage, ['*'], null, $page);
    }
}
