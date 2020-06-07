<?php

declare(strict_types=1);

namespace App\Http\Markets\Controllers;

use App\Domain\Markets\Enums\ChartDays;
use App\Domain\Markets\Interactors\Coins\GetCoinsInteractor;
use App\Domain\Markets\Interactors\Coins\GetCoinsRequest;
use App\Domain\Markets\Interactors\Coins\GetHistoricalDataInteractor;
use App\Domain\Markets\Interactors\Coins\GetHistoricalDataRequest;
use App\Domain\Markets\Interactors\Coins\GetMarketDataInteractor;
use App\Domain\Markets\Interactors\Coins\GetMarketDataRequest;
use App\Domain\Markets\Interactors\Coins\GetProfileInteractor;
use App\Domain\Markets\Interactors\Coins\GetProfileRequest;
use App\Http\Markets\Requests\GetCoinHistoricalDataApiRequest;
use App\Http\Markets\Requests\GetCoinMarketDataApiRequest;
use App\Http\Markets\Requests\GetCoinProfileApiRequest;
use App\Http\Markets\Requests\GetCoinsApiRequest;
use App\Http\Markets\Resources\CoinHistoricalDataCollectionResource;
use App\Http\Markets\Resources\CoinMarketDataResource;
use App\Http\Markets\Resources\CoinOverviewCollectionResource;
use App\Http\Markets\Resources\CoinProfileResource;
use App\Http\Common\ApiResponse;

final class CoinController
{
    public function getCoins(
        GetCoinsApiRequest $request,
        GetCoinsInteractor $getCoinsInteractor
    ): ApiResponse {
        $coinsResponse = $getCoinsInteractor->execute(
            new GetCoinsRequest([
                'page' => $request->page(),
                'perPage' => $request->perPage(),
            ])
        );

        return ApiResponse::success(
            new CoinOverviewCollectionResource($coinsResponse->coins),
            [
                'total' => $coinsResponse->total,
                'page' => $coinsResponse->page,
                'per_page' => $coinsResponse->perPage,
                'last_page' => $coinsResponse->lastPage,
            ]
        );
    }

    public function getCoinProfile(
        GetCoinProfileApiRequest $request,
        GetProfileInteractor $profileInteractor
    ): ApiResponse {
        $profileResponse = $profileInteractor->execute(
            new GetProfileRequest([
                'id' => $request->id()
            ])
        );

        return ApiResponse::success(new CoinProfileResource($profileResponse));
    }

    public function getCoinMarketData(
        GetCoinMarketDataApiRequest $request,
        GetMarketDataInteractor $marketDataInteractor
    ): ApiResponse {
        $marketDataResponse = $marketDataInteractor->execute(
            new GetMarketDataRequest([
                'id' => $request->id()
            ])
        );

        return ApiResponse::success(new CoinMarketDataResource($marketDataResponse));
    }

    public function getCoinHistoricalData(
        GetCoinHistoricalDataApiRequest $request,
        GetHistoricalDataInteractor $historicalDataInteractor
    ): ApiResponse {
        $historicalDataResponse = $historicalDataInteractor
            ->execute(
                new GetHistoricalDataRequest([
                    'id' => $request->id(),
                    'days' => new ChartDays($request->period()),
                ])
            );

        return ApiResponse::success(new CoinHistoricalDataCollectionResource($historicalDataResponse));
    }
}
