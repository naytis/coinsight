<?php

declare(strict_types=1);

namespace App\Domain\Markets\Services;

use App\Domain\Markets\Models\News;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class NewsService
{
    public function paginate(
        int $page,
        int $perPage,
        string $sort,
        string $direction
    ): LengthAwarePaginator {
        return News::orderBy($sort, $direction)->paginate($perPage, ['*'], null, $page);
    }
}
