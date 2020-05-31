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
        $coinMarketDataCollection = collect($client->markets());

        $coinModelCollection = Coin::whereIn(
            'name', $coinMarketDataCollection->pluck('name')->toArray()
        )->get();

        foreach ($coinMarketDataCollection as $coinMarketData) {
            $coinModel = $coinModelCollection->firstWhere('name', $coinMarketData->name);

            if (!$coinModel) {
                continue;
            }

            CoinMarketData::updateOrCreate(
                [
                    'coin_id' => $coinModel->id,
                ],
                [
                    'price' => $coinMarketData->price,
                    'price_change_1h' => $coinMarketData->priceChange1h,
                    'price_change_24h' => $coinMarketData->priceChange24h,
                    'price_change_7d' => $coinMarketData->priceChange7d,
                    'price_change_30d' => $coinMarketData->priceChange30d,
                    'price_change_1y' => $coinMarketData->priceChange1y,
                    'market_cap' => $coinMarketData->marketCap,
                    'volume' => $coinMarketData->volume,
                    'circulating_supply' => $coinMarketData->circulatingSupply,
                    'max_supply' => $coinMarketData->maxSupply,
                ],
            );
        }
    }
}
