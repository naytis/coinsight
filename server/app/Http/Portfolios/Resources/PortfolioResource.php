<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Resources;

use App\Http\Portfolios\Mappers\PortfolioMapper;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class PortfolioResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'portfolio' => PortfolioMapper::map($this->resource),
        ];
    }
}
