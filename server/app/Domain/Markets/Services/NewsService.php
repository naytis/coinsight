<?php

declare(strict_types=1);

namespace App\Domain\Markets\Services;

use App\Domain\Markets\Exceptions\NewsArticleNotFound;
use App\Domain\Markets\Models\News;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function getById(int $id): News
    {
        try {
            return News::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new NewsArticleNotFound();
        }
    }
}
