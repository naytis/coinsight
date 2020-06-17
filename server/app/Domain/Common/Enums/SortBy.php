<?php

declare(strict_types=1);

namespace App\Domain\Common\Enums;

use BenSampo\Enum\Enum;

abstract class SortBy extends Enum
{
    public const CREATED_AT = 'created_at';
}
