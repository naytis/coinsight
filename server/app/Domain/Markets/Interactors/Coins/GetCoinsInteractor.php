<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Domain\Common\Responses\PaginationMeta;
use App\Domain\Markets\Entities\Overview;
use App\Domain\Markets\Models\Coin as CoinModel;
use App\Domain\Markets\Services\CoinService;

final class GetCoinsInteractor
{
    private CoinService $coinService;

    public function __construct(CoinService $coinService)
    {
        $this->coinService = $coinService;
    }

    public function execute(GetCoinsRequest $request): GetCoinsResponse
    {
        $coinsPaginator = $this->coinService->paginate($request->page, $request->perPage);

        $coins = $coinsPaginator->map(fn (CoinModel $coin) => Overview::fromModel($coin));

        return new GetCoinsResponse([
            'coins' => $coins,
            'meta' => PaginationMeta::fromPaginator($coinsPaginator),
        ]);
    }
}
