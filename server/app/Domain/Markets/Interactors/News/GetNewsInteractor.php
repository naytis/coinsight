<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\News;

use App\Domain\Common\Responses\PaginationMeta;
use App\Domain\Markets\Entities\NewsArticle;
use App\Domain\Markets\Models\News;

final class GetNewsInteractor
{
    public function execute(GetNewsRequest $request): GetNewsResponse
    {
        $newsPaginator = News::orderBy($request->sort, $request->direction)
            ->paginate($request->perPage, ['*'], null, $request->page);

        $news = $newsPaginator->map(
            fn (News $newsArticle) => NewsArticle::fromModel($newsArticle)
        );

        return new GetNewsResponse([
            'news' => $news,
            'meta' => PaginationMeta::fromPaginator($newsPaginator),
        ]);
    }
}
