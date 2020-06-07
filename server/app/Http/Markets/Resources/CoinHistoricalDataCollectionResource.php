<?php

declare(strict_types=1);

namespace App\Http\Markets\Resources;

use App\Domain\Markets\Entities\HistoricalData;
use App\Http\Markets\Mappers\CoinMapper;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class CoinHistoricalDataCollectionResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'coin' => CoinMapper::map($this->coin),
            'historical_data' => $this->historicalData->map(
                fn (HistoricalData $historical) => [
                    'timestamp' => $historical->timestamp->getPreciseTimestamp(3),
                    'price' => $historical->price,
                    'market_cap' => $historical->marketCap,
                    'volume' => $historical->volume,
                ]
            ),
        ];
    }
}
