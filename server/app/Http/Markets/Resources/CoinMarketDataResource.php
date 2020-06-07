<?php

declare(strict_types=1);

namespace App\Http\Markets\Resources;

use App\Http\Markets\Mappers\CoinMapper;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class CoinMarketDataResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'coin' => CoinMapper::map($this->coin),
            'market_data' => [
                'circulating_supply' =>  $this->marketData->circulatingSupply,
                'max_supply' => round($this->marketData->maxSupply),
                'price' =>  $this->marketData->price,
                'volume' =>  $this->marketData->volume,
                'market_cap' =>  $this->marketData->marketCap,
                'price_change_1h' =>  $this->marketData->priceChange1h,
                'price_change_24h' =>  $this->marketData->priceChange24h,
                'price_change_7d' =>  $this->marketData->priceChange7d,
                'price_change_30d' =>  $this->marketData->priceChange30d,
                'price_change_1y' =>  $this->marketData->priceChange1y,
            ],
        ];
    }
}
