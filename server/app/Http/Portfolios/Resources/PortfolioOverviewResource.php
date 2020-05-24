<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Resources;

use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class PortfolioOverviewResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'portfolio' => new PortfolioResource($this->portfolio),
            'total_value' => $this->totalValue,
            'total_value_change' => $this->totalValueChange,
        ];
    }
}
