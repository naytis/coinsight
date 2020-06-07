<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Domain\Markets\Entities\Coin;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

final class GetHistoricalDataResponse extends DataTransferObject
{
    public Coin $coin;
    public Collection $historicalData;
}
