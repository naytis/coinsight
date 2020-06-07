<?php

declare(strict_types=1);

namespace App\Http\Markets\Resources;

use App\Http\Markets\Mappers\NewsMapper;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class NewsArticleResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'article' => NewsMapper::map($this->resource),
        ];
    }
}
