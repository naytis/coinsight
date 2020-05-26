<?php

declare(strict_types=1);

namespace App\Domain\Markets\Entities;

use App\Domain\Markets\Models\Coin as CoinModel;
use Spatie\DataTransferObject\DataTransferObject;

final class CoinMarketData extends DataTransferObject
{
    public int $id;
    public string $name;
    public string $symbol;
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

    public static function fromModel(CoinModel $coinModel): self
    {
        return new static([
            'id' => $coinModel->id,
            'name' => $coinModel->name,
            'symbol' => $coinModel->symbol,
            'circulatingSupply' => $coinModel->marketData->circulating_supply,
            'maxSupply' => $coinModel->marketData->max_supply,
            'price' => $coinModel->marketData->price,
            'volume' => $coinModel->marketData->volume,
            'marketCap' => $coinModel->marketData->market_cap,
            'priceChange1h' => $coinModel->marketData->price_change_1h,
            'priceChange24h' => $coinModel->marketData->price_change_24h,
            'priceChange7d' => $coinModel->marketData->price_change_7d,
            'priceChange30d' => $coinModel->marketData->price_change_30d,
            'priceChange1y' => $coinModel->marketData->price_change_1y,
        ]);
    }
}
