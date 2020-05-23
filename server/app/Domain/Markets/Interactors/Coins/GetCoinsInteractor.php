<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Domain\Markets\Entities\CoinOverview as CoinOverviewEntity;
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
        $coinOverviewPaginator = $this->coinService->paginate($request->page, $request->perPage);

        $coinOverviewPaginator->setCollection(
            $coinOverviewPaginator->toBase()
                ->map(fn (CoinModel $coin) => CoinOverviewEntity::fromModel($coin))
        );

        return new GetCoinsResponse([
            'coins' => $coinOverviewPaginator->getCollection(),
            'total' => $coinOverviewPaginator->total(),
            'page' => $coinOverviewPaginator->currentPage(),
            'perPage' => $coinOverviewPaginator->perPage(),
            'lastPage' => $coinOverviewPaginator->lastPage(),
        ]);
    }
}
