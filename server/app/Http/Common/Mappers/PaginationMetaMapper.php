<?php

declare(strict_types=1);

namespace App\Http\Common\Mappers;

use App\Domain\Common\Responses\PaginationMeta;

final class PaginationMetaMapper
{
    public static function map(PaginationMeta $paginationMeta): array
    {
        return [
            'total' => $paginationMeta->total,
            'page' => $paginationMeta->page,
            'per_page' => $paginationMeta->perPage,
            'last_page' => $paginationMeta->lastPage,
        ];
    }
}
