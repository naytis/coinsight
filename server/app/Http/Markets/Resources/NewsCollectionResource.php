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
            fn (NewsArticle $newsArticle) => [
                'id' => $newsArticle->id,
                'title' => $newsArticle->title,
                'content' => $newsArticle->content,
                'published_at' => $newsArticle->publishedAt->timestamp,
                'author' => $newsArticle->author,
            ],
        );


        return [
            'news' => $news,
        ];
    }
}
