<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Spatie\DataTransferObject\DataTransferObjectCollection;

final class CoinMarketDataCollection extends DataTransferObjectCollection
{
    public function current(): CoinMarketData
    {
        return parent::current();
    }
}
