<?php

declare(strict_types=1);

namespace App\Http\Markets\Resources;

use App\Domain\Markets\Entities\NewsArticle;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class NewsCollectionResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        $news = $this->map(
            fn (NewsArticle $newsArticle) => new NewsArticleResource($newsArticle),
        );

        return [
            'news' => $news,
        ];
    }
}
