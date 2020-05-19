<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\News;

use App\Domain\Markets\Entities\NewsArticle;
use App\Domain\Markets\Models\News;
use App\Domain\Markets\Services\NewsService;

final class GetNewsInteractor
{
    private NewsService $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function execute(GetNewsRequest $request): GetNewsResponse
    {
        $newsPaginator = $this->newsService->paginate(
            $request->page, $request->perPage, $request->sort, $request->direction
        );

        $newsPaginator->setCollection(
            $newsPaginator->toBase()
                ->map(fn (News $newsArticle) => NewsArticle::fromModel($newsArticle))
        );

        return new GetNewsResponse([
            'news' => $newsPaginator->getCollection(),
            'total' => $newsPaginator->total(),
            'page' => $newsPaginator->currentPage(),
            'perPage' => $newsPaginator->perPage(),
            'lastPage' => $newsPaginator->lastPage(),
        ]);
    }
}
