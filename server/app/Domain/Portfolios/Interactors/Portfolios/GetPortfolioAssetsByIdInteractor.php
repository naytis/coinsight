<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use App\Domain\Common\Responses\PaginationMeta;
use App\Domain\Markets\Entities\Coin;
use App\Domain\Markets\Models\Coin as CoinModel;
use App\Domain\Portfolios\Entities\Asset;
use App\Domain\Portfolios\Models\Portfolio;
use App\Domain\Portfolios\Services\FinanceCalculator;

final class GetPortfolioAssetsByIdInteractor
{
    private FinanceCalculator $calculator;

    public function __construct(FinanceCalculator $financeCalculator)
    {
        $this->calculator = $financeCalculator;
    }

    public function execute(GetPortfolioAssetsByIdRequest $request): GetPortfolioAssetsByIdResponse
    {
        $portfolio = Portfolio::whereId($request->portfolioId)
            ->whereUserId($request->userId)
            ->firstOrFail();

        $coinsPaginator = CoinModel::with([
            'marketData',
            'transactions' => fn ($query) => $query->where('portfolio_id', $portfolio->id)
        ])
            ->whereHas('transactions', fn ($query) => $query->where('portfolio_id', $portfolio->id))
            ->paginate($request->perPage, ['*'], null, $request->page);

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
