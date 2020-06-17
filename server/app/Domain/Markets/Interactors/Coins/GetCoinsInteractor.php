<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Domain\Common\Enums\SortDirection;
use App\Domain\Common\Responses\PaginationMeta;
use App\Domain\Markets\Entities\Overview;
use App\Domain\Markets\Enums\SortBy;
use App\Domain\Markets\Models\Coin as CoinModel;

final class GetCoinsInteractor
{
    public function execute(GetCoinsRequest $request): GetCoinsResponse
    {
        $coinsPaginator = CoinModel::with([
            'marketData' => fn ($query) => $query->orderBy(SortBy::MARKET_CAP, SortDirection::DESC)
        ])
            ->has('marketData')
            ->paginate($request->perPage, ['*'], null, $request->page);

        $coins = $coinsPaginator->map(fn (CoinModel $coin) => Overview::fromModel($coin));

        return new GetCoinsResponse([
            'coins' => $coins,
            'meta' => PaginationMeta::fromPaginator($coinsPaginator),
        ]);
    }
}
