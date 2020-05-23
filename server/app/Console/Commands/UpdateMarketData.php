<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Coinfo\Client;
use App\Domain\Markets\Models\Coin;
use App\Domain\Markets\Models\CoinMarketData;
use Illuminate\Console\Command;

final class UpdateMarketData extends Command
{
    protected $signature = 'coinsight:update-market-data';

    protected $description = 'Update coins market data.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(Client $client)
    {
        $coinOverviewCollection = collect(
            $client->markets()
        );

        $coinModelCollection = Coin::whereIn(
            'name', $coinOverviewCollection->pluck('name')->toArray()
        )->get();

        foreach ($coinOverviewCollection as $coinOverview) {
            $coinModel = $coinModelCollection->firstWhere('name', $coinOverview->name);

            if (!$coinModel) {
                continue;
            }

            CoinMarketData::updateOrCreate(
                [
                    'coin_id' => $coinModel->id,
                ],
                [
                    'price' => $coinOverview->price,
                    'price_change_24h' => $coinOverview->priceChange24h,
                    'market_cap' => $coinOverview->marketCap,
                    'volume' => $coinOverview->volume,
                    'circulating_supply' => $coinOverview->circulatingSupply,
                ],
            );
        }
    }
}
