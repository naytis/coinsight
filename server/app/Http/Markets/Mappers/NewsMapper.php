<?php

declare(strict_types=1);

namespace App\Http\Markets\Mappers;

use App\Domain\Markets\Entities\NewsArticle;

final class NewsMapper
{
    public static function map(NewsArticle $newsArticle): array
    {
        return [
            'id' => $newsArticle->id,
            'title' => $newsArticle->title,
            'content' => $newsArticle->content,
            'published_at' => $newsArticle->publishedAt,
            'author' => $newsArticle->author,
        ];
    }
}
