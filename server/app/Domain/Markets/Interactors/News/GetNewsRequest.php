<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\News;

use App\Domain\Common\Contracts\PaginationRequest;
use Spatie\DataTransferObject\DataTransferObject;

final class GetNewsRequest extends DataTransferObject implements PaginationRequest
{
    public int $page = self::DEFAULT_PAGE;
    public int $perPage = self::DEFAULT_PER_PAGE;
    public string $sort = self::DEFAULT_SORT;
    public string $direction = self::DEFAULT_DIRECTION;
}
