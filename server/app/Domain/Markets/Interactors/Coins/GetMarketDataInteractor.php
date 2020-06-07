<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Domain\Markets\Entities\Coin;
use App\Domain\Markets\Entities\CoinMarketData;
use App\Domain\Markets\Services\CoinService;

final class GetMarketDataInteractor
{
    private CoinService $coinService;

    public function __construct(CoinService $coinService)
    {
        $this->coinService = $coinService;
    }

    public function execute(GetMarketDataRequest $request): GetMarketDataResponse
    {
        $coin = $this->coinService->getById($request->id, ['marketData', 'profile']);

        return new GetMarketDataResponse([
            'coin' => Coin::fromModel($coin),
            'marketData' => CoinMarketData::fromModel($coin->marketData)
        ]);
    }
}
