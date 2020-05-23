<?php

declare(strict_types=1);

namespace App\Domain\Markets\Entities;

use App\Domain\Markets\Entities\Coin as CoinEntity;
use App\Domain\Markets\Models\Coin as CoinModel;
use Spatie\DataTransferObject\DataTransferObject;

final class CoinOverview extends DataTransferObject
{
    public Coin $coin;
    public float $price;
    public ?float $priceChange24h;
    public float $marketCap;
    public float $volume;

    public static function fromModel(CoinModel $coinModel): self
    {
        return new static([
            'coin' => CoinEntity::fromModel($coinModel),
            'price' => $coinModel->marketData->price,
            'priceChange24h' => $coinModel->marketData->price_change_24h ?? null,
            'marketCap' => $coinModel->marketData->market_cap,
            'volume' => $coinModel->marketData->volume,
        ]);
    }
}
