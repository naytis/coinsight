<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\News;

use App\Domain\Markets\Entities\NewsArticle;
use App\Domain\Markets\Models\News;

final class GetNewsArticleByIdInteractor
{
    public function execute(GetNewsArticleByIdRequest $request): GetNewsArticleByIdResponse
    {
        $newsArticle = News::findOrFail($request->id);

        return new GetNewsArticleByIdResponse([
            'newsArticle' => NewsArticle::fromModel($newsArticle),
        ]);
    }
}
