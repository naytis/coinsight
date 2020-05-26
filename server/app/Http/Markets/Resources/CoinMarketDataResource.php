<?php

declare(strict_types=1);

namespace App\Http\Markets\Resources;

use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class CoinMarketDataResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'id' =>  $this->id,
            'name' =>  $this->name,
            'symbol' =>  $this->symbol,
            'circulating_supply' =>  $this->circulatingSupply,
            'max_supply' => round($this->maxSupply),
            'price' =>  $this->price,
            'volume' =>  $this->volume,
            'market_cap' =>  $this->marketCap,
            'price_change_1h' =>  $this->priceChange1h,
            'price_change_24h' =>  $this->priceChange24h,
            'price_change_7d' =>  $this->priceChange7d,
            'price_change_30d' =>  $this->priceChange30d,
            'price_change_1y' =>  $this->priceChange1y,
        ];
    }
}
