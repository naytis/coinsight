<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Sessions;

use App\Domain\Common\Responses\PaginationMeta;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

final class GetUserActiveSessionsResponse extends DataTransferObject
{
    public Collection $sessions;
    public PaginationMeta $meta;
}
