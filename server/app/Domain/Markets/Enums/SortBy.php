<?php

declare(strict_types=1);

namespace App\Domain\Markets\Enums;

use App\Domain\Common\Enums\SortBy as SortByCommon;

abstract class SortBy extends SortByCommon
{
    public const MARKET_CAP = 'market_cap';
}
