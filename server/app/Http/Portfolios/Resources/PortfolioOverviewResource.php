<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Resources;

use App\Http\Portfolios\Mappers\PortfolioMapper;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class PortfolioOverviewResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'overview' => [
                'portfolio' => PortfolioMapper::map($this->portfolio),
                'total_value' => $this->totalValue,
                'total_value_change' => $this->totalValueChange,
            ],
        ];
    }
}
