<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\News;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

final class GetNewsResponse extends DataTransferObject
{
    public Collection $news;
    public int $total;
    public int $page;
    public int $perPage;
    public int $lastPage;
}
