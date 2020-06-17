<?php

declare(strict_types=1);

namespace App\Domain\Markets\Enums;

use BenSampo\Enum\Enum;

final class ChartDays extends Enum
{
    public const ONE_DAY = 1;
    public const ONE_WEEK = 7;
    public const ONE_MONTH = 30;
    public const SIX_MONTH = 180;
    public const ONE_YEAR = 365;
    public const MAX = -1;
}
