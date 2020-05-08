<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Entities;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

final class ValueByTime extends DataTransferObject
{
    public Carbon $datetime;
    public float $value;
}
