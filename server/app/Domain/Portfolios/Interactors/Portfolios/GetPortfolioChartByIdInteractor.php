<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use App\Coinfo\Client;
use App\Domain\Markets\Services\CoinService;
use App\Domain\Portfolios\Entities\ValueByTime;
use App\Domain\Portfolios\Enums\TransactionType;
use App\Domain\Portfolios\Models\Transaction;
use App\Domain\Portfolios\Services\PortfolioService;
use Carbon\Carbon;

final class GetPortfolioChartByIdInteractor
{
    private PortfolioService $portfolioService;
    private CoinService $coinService;
    private Client $client;

    public function __construct(
        PortfolioService $portfolioService,
        CoinService $coinService,
        Client $client
    ) {
        $this->portfolioService = $portfolioService;
        $this->coinService = $coinService;
        $this->client = $client;
    }

    public function execute(GetPortfolioChartByIdRequest $request): GetPortfolioChartByIdResponse
    {
        $portfolio = $this->portfolioService->getByIdAndUserId(
            $request->portfolioId, $request->userId, ['transactions']
        );

        if ($portfolio->transactions->isEmpty()) {
            return new GetPortfolioChartByIdResponse([
                'portfolioValueByTime' => collect($this->getEmptyPortfolioValueByTime()),
            ]);
        }

        $coinsIds = $portfolio->transactions
            ->map(fn(Transaction $transaction) => $transaction->coin_id)
            ->unique()
            ->toArray();

        $coins = $this->coinService->getCoinsByIds($coinsIds);

        $coinOverviewCollection = collect(
            $this->client->marketsForCoins($coins->pluck('name')->toArray())
        );

        $transactionsValuesByTime = [];
        foreach ($portfolio->transactions as $transaction) {
            $coin = $coins->find($transaction->coin_id);
            $coinMarketOverview = $coinOverviewCollection->firstWhere('name', $coin->name);

            if ($transaction->type === TransactionType::BUY) {
                $quantity = $transaction->quantity;
            } else {
                $quantity = $transaction->quantity * -1;
            }
            $transactionsValuesByTime[] = $this->getTransactionValueByTime(
                $quantity, $transaction->datetime, $coinMarketOverview->sparkline
            );
        }

        $portfolioValueByTime = $this->getPortfolioValueByTime($transactionsValuesByTime);

        return new GetPortfolioChartByIdResponse([
            'portfolioValueByTime' => collect($portfolioValueByTime),
        ]);
    }

    private function getTransactionValueByTime(
        float $quantity, Carbon $datetime, array $sparkline
    ): array {
        $priceByTime = [];
        $dateStart = now()->subDays(7);

        for ($i = 0; $i < count($sparkline); $i++) {
            $priceByTime[] = new ValueByTime([
                'datetime' => $dateStart->clone()->addHours($i),
                'value' => $sparkline[$i],
            ]);
        }

        $valueByTime = [];

        foreach ($priceByTime as $item) {
            $valueByTime[] = new ValueByTime([
                'datetime' => $item->datetime,
                'value' => $item->datetime < $datetime ? 0 : $quantity * $item->value,
            ]);
        }

        return $valueByTime;
    }

    private function getPortfolioValueByTime(array $transactionsValuesByTime): array
    {
        $portfolioValueByTime = [];

        for ($i = 0; $i < count($transactionsValuesByTime[0]); $i++) {
            $portfolioValue = 0;
            for ($j = 0; $j < count($transactionsValuesByTime); $j++) {
                $portfolioValue += $transactionsValuesByTime[$j][$i]->value;
            }

            $portfolioValueByTime[] = new ValueByTime([
                'datetime' => $transactionsValuesByTime[0][$i]->datetime,
                'value' => $portfolioValue,
            ]);
        }
        return $portfolioValueByTime;
    }

    private function getEmptyPortfolioValueByTime(): array
    {
        $days = 7;
        $hours = 24;
        $valuesCount = $days * $hours;
        $dateStart = now()->subDays($days);

        $valueByTime = [];
        for ($i = 0; $i < $valuesCount; $i++) {
            $valueByTime[] = new ValueByTime([
                'datetime' => $dateStart->clone()->addHours($i),
                'value' => 0,
            ]);
        }

        return $valueByTime;
    }
}
