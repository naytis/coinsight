<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use App\Domain\Markets\Models\CoinMarketData;
use App\Domain\Portfolios\Entities\Overview;
use App\Domain\Portfolios\Entities\Portfolio;
use App\Domain\Portfolios\Models\Portfolio as PortfolioModel;
use App\Domain\Portfolios\Models\Transaction;
use App\Domain\Portfolios\Services\FinanceCalculator;

final class GetPortfolioOverviewByIdInteractor
{
    private FinanceCalculator $calculator;

    public function __construct(FinanceCalculator $financeCalculator)
    {
        $this->calculator = $financeCalculator;
    }

    public function execute(GetPortfolioOverviewByIdRequest $request): GetPortfolioOverviewByIdResponse
    {
        $portfolio = PortfolioModel::with('transactions')
            ->whereId($request->portfolioId)
            ->whereUserId($request->userId)
            ->firstOrFail();

        if ($portfolio->transactions->isEmpty()) {
            return new GetPortfolioOverviewByIdResponse([
                'overview' =>  new Overview([
                    'portfolio' => Portfolio::fromModel($portfolio),
                    'totalValue' => null,
                    'totalValueChange' => null,
                ]),
            ]);
        }

        $coinsIds = $portfolio->transactions
            ->map(fn(Transaction $transaction) => $transaction->coin_id)
            ->unique()
            ->toArray();

        $coinMarketDataCollection = CoinMarketData::whereIn('coin_id', $coinsIds)
            ->get(['price', 'coin_id']);

        $portfolioTotalValue = 0;
        $portfolioTotalCost = 0;

        foreach ($portfolio->transactions as $transaction) {
            $marketData = $coinMarketDataCollection->firstWhere('coin_id', $transaction->coin_id);

            $portfolioTotalValue += $this->calculator->value(
                $transaction->quantity_by_type, $marketData->price
            );
            $portfolioTotalCost += $this->calculator->cost(
                $transaction->quantity_by_type, $transaction->price_per_coin, $transaction->fee
            );
        }

        $portfolioValueChange = $this->calculator->valueChange(
            $portfolioTotalValue, $portfolioTotalCost
        );

        return new GetPortfolioOverviewByIdResponse([
            'overview' => new Overview([
                'portfolio' => Portfolio::fromModel($portfolio),
                'totalValue' => $portfolioTotalValue,
                'totalValueChange' => $portfolioValueChange,
            ]),
        ]);
    }
}
