<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\News;

use App\Domain\Markets\Entities\NewsArticle;
use Spatie\DataTransferObject\DataTransferObject;

final class GetNewsArticleByIdResponse extends DataTransferObject
{
    public NewsArticle $newsArticle;
}
