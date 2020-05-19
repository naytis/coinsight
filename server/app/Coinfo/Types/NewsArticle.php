<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

final class NewsArticle extends DataTransferObject
{
    public string $title;
    public string $content;
    public Carbon $publishedAt;
    public string $author;
}
