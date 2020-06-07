<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use App\Domain\Common\Responses\PaginationMeta;
use App\Domain\Markets\Entities\Coin;
use App\Domain\Markets\Services\CoinService;
use App\Domain\Portfolios\Entities\Asset;
use App\Domain\Portfolios\Services\FinanceCalculator;
use App\Domain\Portfolios\Services\PortfolioService;

final class GetPortfolioAssetsByIdInteractor
{
    private PortfolioService $portfolioService;
    private CoinService $coinService;
    private FinanceCalculator $calculator;

    public function __construct(
        PortfolioService $portfolioService,
        CoinService $coinService,
        FinanceCalculator $financeCalculator
    ) {
        $this->portfolioService = $portfolioService;
        $this->coinService = $coinService;
        $this->calculator = $financeCalculator;
    }

    public function execute(GetPortfolioAssetsByIdRequest $request): GetPortfolioAssetsByIdResponse
    {
        $portfolio = $this->portfolioService->getByIdAndUserId(
            $request->portfolioId, $request->userId
        );

        $coinsPaginator = $this->coinService->paginateByPortfolioId(
            $portfolio->id, $request->page, $request->perPage, ['transactions', 'marketData']
        );

        if ($coinsPaginator->isEmpty()) {
            return new GetPortfolioAssetsByIdResponse([
                'assets' => collect(),
                'meta' => PaginationMeta::fromPaginator($coinsPaginator),
            ]);
        }

        $assets = [];
        $portfolioTotalValue = 0;

        foreach ($coinsPaginator as $coin) {
            $assetMarketValue = 0;
            $assetNetCost = 0;
            $assetHoldings = 0;

            foreach ($coin->transactions as $transaction) {
                $assetMarketValue += $this->calculator->value(
                    $transaction->quantity_by_type, $coin->marketData->price
                );
                $assetNetCost += $this->calculator->cost(
                    $transaction->quantity_by_type, $transaction->price_per_coin, $transaction->fee
                );
                $assetHoldings += $transaction->quantity_by_type;
            }

            $assetNetProfit = $this->calculator->netProfit($assetMarketValue, $assetNetCost);
            $assetValueChange = $this->calculator->valueChange($assetMarketValue, $assetNetCost);

            $portfolioTotalValue += $assetMarketValue;

            $assets[] = new Asset([
                'coin' => Coin::fromModel($coin),
                'price' => $coin->marketData->price,
                'priceChange24h' => $coin->marketData->price_change_24h,
                'holdings' => $assetHoldings,
                'marketValue' => $assetMarketValue,
                'netCost' => $assetNetCost,
                'netProfit' => $assetNetProfit,
                'valueChange' => $assetValueChange
            ]);
        }

        foreach ($assets as $asset) {
            $asset->share = $asset->holdings > 0
                ? $this->calculator->share($asset->marketValue, $portfolioTotalValue)
                : 0;
        }

        $assets = collect($assets)->sortByDesc('share')->values();

        return new GetPortfolioAssetsByIdResponse([
            'assets' => $assets,
            'meta' => PaginationMeta::fromPaginator($coinsPaginator),
        ]);
    }
}
