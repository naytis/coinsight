<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Domain\Markets\Entities\Coin;
use App\Domain\Markets\Entities\CoinMarketData;
use Spatie\DataTransferObject\DataTransferObject;

final class GetMarketDataResponse extends DataTransferObject
{
    public Coin $coin;
    public CoinMarketData $marketData;
}
