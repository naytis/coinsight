<?php

declare(strict_types=1);

namespace App\Domain\Markets\Entities;

use App\Domain\Markets\Models\Coin as CoinModel;
use App\Domain\Markets\Models\CoinMarketData as CoinMarketDataModel;
use Spatie\DataTransferObject\DataTransferObject;

final class CoinMarketData extends DataTransferObject
{
    public float $circulatingSupply;
    public ?float $maxSupply;
    public float $price;
    public float $volume;
    public float $marketCap;
    public float $priceChange1h;
    public float $priceChange24h;
    public float $priceChange7d;
    public float $priceChange30d;
    public float $priceChange1y;

    public static function fromModel(CoinMarketDataModel $marketDataModel): self
    {
        return new static([
            'circulatingSupply' => $marketDataModel->circulating_supply,
            'maxSupply' => $marketDataModel->max_supply,
            'price' => $marketDataModel->price,
            'volume' => $marketDataModel->volume,
            'marketCap' => $marketDataModel->market_cap,
            'priceChange1h' => $marketDataModel->price_change_1h,
            'priceChange24h' => $marketDataModel->price_change_24h,
            'priceChange7d' => $marketDataModel->price_change_7d,
            'priceChange30d' => $marketDataModel->price_change_30d,
            'priceChange1y' => $marketDataModel->price_change_1y,
        ]);
    }
}
