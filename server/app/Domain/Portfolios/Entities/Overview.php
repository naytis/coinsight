<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Entities;

use Spatie\DataTransferObject\DataTransferObject;

final class Overview extends DataTransferObject
{
    public Portfolio $portfolio;
    public ?float $totalValue;
    public ?float $totalValueChange;
}
