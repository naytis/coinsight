<?php

declare(strict_types=1);

namespace App\Domain\Markets\Exceptions;

use App\Domain\Common\Exceptions\ModelNotFound;

final class NewsArticleNotFound extends ModelNotFound
{
    protected $message = "News article not found.";
}
