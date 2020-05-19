<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\News;

use Spatie\DataTransferObject\DataTransferObject;

final class GetNewsArticleByIdRequest extends DataTransferObject
{
    public int $id;
}
