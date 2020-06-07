<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use App\Domain\Common\Responses\PaginationMeta;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

final class GetPortfoliosResponse extends DataTransferObject
{
    public Collection $portfolios;
    public PaginationMeta $meta;
}
