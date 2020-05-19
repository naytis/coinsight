<?php

declare(strict_types=1);

namespace App\Domain\Markets\Entities;

use App\Domain\Markets\Models\News;
use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

final class NewsArticle extends DataTransferObject
{
    public int $id;
    public string $title;
    public string $content;
    public Carbon $publishedAt;
    public string $author;

    public static function fromModel(News $newsArticle): self
    {
        return new static([
            'id' => $newsArticle->id,
            'title' => $newsArticle->title,
            'content' => $newsArticle->content,
            'publishedAt' => $newsArticle->published_at,
            'author' => $newsArticle->author,
        ]);
    }
}
