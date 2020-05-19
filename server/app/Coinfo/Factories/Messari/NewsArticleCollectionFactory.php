<?php

declare(strict_types=1);

namespace App\Coinfo\Factories\Messari;

use App\Coinfo\Types\NewsArticle;
use App\Coinfo\Types\NewsArticleCollection;
use Carbon\Carbon;

final class NewsArticleCollectionFactory
{
    public static function create(array $data): NewsArticleCollection
    {
        $news = array_map(
            fn ($newsArticle) => new NewsArticle([
                'title' => $newsArticle['title'],
                'content' => $newsArticle['content'],
                'publishedAt' => Carbon::parse($newsArticle['published_at']),
                'author' => $newsArticle['author']['name'],
            ]),
            $data['data']
        );
        return new NewsArticleCollection($news);
    }
}
