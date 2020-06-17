<?php

declare(strict_types=1);

namespace App\Domain\Common\Enums;

use BenSampo\Enum\Enum;

abstract class SortDirection extends Enum
{
    public const DESC = 'desc';
    public const ASC = 'asc';
}
