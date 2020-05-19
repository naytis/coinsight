<?php

declare(strict_types=1);

namespace App\Coinfo\Types;

use Spatie\DataTransferObject\DataTransferObjectCollection;

final class NewsArticleCollection extends DataTransferObjectCollection
{
    public function current(): NewsArticle
    {
        return parent::current();
    }
}
