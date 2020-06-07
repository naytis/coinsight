<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Mappers;

use App\Domain\Portfolios\Entities\Portfolio;

final class PortfolioMapper
{
    public static function map(Portfolio $portfolio): array
    {
        return [
            'id' => $portfolio->id,
            'name' => $portfolio->name,
        ];
    }
}
