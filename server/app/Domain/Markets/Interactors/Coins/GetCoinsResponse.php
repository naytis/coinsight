<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Domain\Common\Responses\PaginationMeta;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

final class GetCoinsResponse extends DataTransferObject
{
    public Collection $coins;
    public PaginationMeta $meta;
}
