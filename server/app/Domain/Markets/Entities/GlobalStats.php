<?php

declare(strict_types=1);

namespace App\Domain\Markets\Entities;

use Spatie\DataTransferObject\DataTransferObject;

final class GlobalStats extends DataTransferObject
{
    public int $marketCap;
    public int $volume;
    public float $bitcoinDominance;
}
