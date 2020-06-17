<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Coinfo\Client;
use App\Domain\Markets\Entities\Coin as CoinEntity;
use App\Domain\Markets\Entities\HistoricalData;
use App\Domain\Markets\Enums\ChartDays;
use App\Domain\Markets\Models\Coin as CoinModel;

final class GetHistoricalDataInteractor
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function execute(GetHistoricalDataRequest $request): GetHistoricalDataResponse
    {
        $coin = CoinModel::findOrFail($request->id);

        if ($request->days->is(ChartDays::MAX)) {
            $historicalDataResponse = $this->client->coinHistoricalDataAllTime($coin->coin_gecko_id);
        } else {
            $historicalDataResponse = $this->client->coinHistoricalData($coin->coin_gecko_id, $request->days->value);
        }

        $historicalData = collect($historicalDataResponse)->map(
            fn ($historicalDataItem) => new HistoricalData($historicalDataItem->toArray())
        );

        return new GetHistoricalDataResponse([
            'coin' => CoinEntity::fromModel($coin),
            'historicalData' => $historicalData,
        ]);
    }
}
