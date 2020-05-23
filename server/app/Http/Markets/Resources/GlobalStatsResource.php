<?php

declare(strict_types=1);

namespace App\Http\Markets\Resources;

use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class GlobalStatsResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'market_cap' => $this->marketCap,
            'volume' => $this->volume,
            'bitcoin_dominance' => $this->bitcoinDominance,
        ];
    }
}
