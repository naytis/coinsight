<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\News;

use App\Domain\Markets\Entities\NewsArticle;
use App\Domain\Markets\Services\NewsService;

final class GetNewsArticleByIdInteractor
{
    private NewsService $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function execute(GetNewsArticleByIdRequest $request): GetNewsArticleByIdResponse
    {
        $newsArticle = $this->newsService->getById($request->id);

        return new GetNewsArticleByIdResponse([
            'newsArticle' => NewsArticle::fromModel($newsArticle),
        ]);
    }
}
