<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\News;

use App\Domain\Common\Responses\PaginationMeta;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

final class GetNewsResponse extends DataTransferObject
{
    public Collection $news;
    public PaginationMeta $meta;
}
