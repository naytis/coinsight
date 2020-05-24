<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use App\Domain\Portfolios\Entities\Overview;
use App\Domain\Portfolios\Entities\Portfolio;
use Spatie\DataTransferObject\DataTransferObject;

final class GetPortfolioOverviewByIdResponse extends DataTransferObject
{
    public Overview $overview;
}
