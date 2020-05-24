<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use App\Domain\Markets\Services\CoinService;
use App\Domain\Portfolios\Entities\Overview;
use App\Domain\Portfolios\Entities\Portfolio;
use App\Domain\Portfolios\Models\Transaction;
use App\Domain\Portfolios\Services\FinanceCalculator;
use App\Domain\Portfolios\Services\PortfolioService;

final class GetPortfolioOverviewByIdInteractor
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

    public function execute(GetPortfolioOverviewByIdRequest $request): GetPortfolioOverviewByIdResponse
    {
        $portfolio = $this->portfolioService->getByIdAndUserId(
            $request->portfolioId, $request->userId, ['transactions']
        );

        if ($portfolio->transactions->isEmpty()) {
            return new GetPortfolioOverviewByIdResponse([
                'overview' =>  new Overview([
                    'portfolio' => Portfolio::fromModel($portfolio),
                    'totalValue' => 0,
                    'totalValueChange' => 0,
                ]),
            ]);
        }

        $coinsIds = $portfolio->transactions
            ->map(fn(Transaction $transaction) => $transaction->coin_id)
            ->unique()
            ->toArray();

        $coins = $this->coinService->getCoinsByIds($coinsIds);

        $portfolioTotalValue = 0;
        $portfolioTotalCost = 0;

        foreach ($portfolio->transactions as $transaction) {
            $coin = $coins->find($transaction->coin_id);

            $portfolioTotalValue += $this->calculator->value(
                $transaction->quantity_by_type, $coin->marketData->price
            );
            $portfolioTotalCost += $this->calculator->cost(
                $transaction->quantity_by_type, $transaction->price_per_coin, $transaction->fee
            );
        }

        if ($portfolioTotalCost === 0) {
            $portfolioValueChange = $this->calculator->valueChange($portfolioTotalValue, $portfolioTotalCost);
        } else {
            $portfolioValueChange = 0;
        }

        return new GetPortfolioOverviewByIdResponse([
            'overview' => new Overview([
                'portfolio' => Portfolio::fromModel($portfolio),
                'totalValue' => $portfolioTotalValue,
                'totalValueChange' => $portfolioValueChange,
            ]),
        ]);
    }
}
