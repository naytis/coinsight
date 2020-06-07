<?php

declare(strict_types=1);

namespace App\Domain\Common\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\DataTransferObject\DataTransferObject;

final class PaginationMeta extends DataTransferObject
{
    public int $total;
    public int $page;
    public int $perPage;
    public int $lastPage;

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        return new static([
            'total' => $paginator->total(),
            'page' => $paginator->currentPage(),
            'perPage' => $paginator->perPage(),
            'lastPage' => $paginator->lastPage(),
        ]);
    }
}
