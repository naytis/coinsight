<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Domain\Markets\Entities\Coin as CoinEntity;
use App\Domain\Markets\Entities\CoinMarketData;
use App\Domain\Markets\Models\Coin as CoinModel;

final class GetMarketDataInteractor
{
    public function execute(GetMarketDataRequest $request): GetMarketDataResponse
    {
        $coin = CoinModel::with('marketData')->findOrFail($request->id);

        return new GetMarketDataResponse([
            'coin' => CoinEntity::fromModel($coin),
            'marketData' => CoinMarketData::fromModel($coin->marketData)
        ]);
    }
}
