<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

final class GetPortfolioChartByIdResponse extends DataTransferObject
{
    public Collection $portfolioValueByTime;
}
