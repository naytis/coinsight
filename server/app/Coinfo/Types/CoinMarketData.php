<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Spatie\DataTransferObject\DataTransferObject;

final class CoinMarketData extends DataTransferObject
{
    public string $name;
    public string $symbol;
    public string $icon;
    public ?float $price;
    public ?float $priceChange1h;
    public ?float $priceChange24h;
    public ?float $priceChange7d;
    public ?float $priceChange30d;
    public ?float $priceChange1y;
    public float $marketCap;
    public float $volume;
    public float $circulatingSupply;
    public ?float $maxSupply;
    public ?array $sparkline;
}
