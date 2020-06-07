<?php

declare(strict_types=1);

namespace App\Http\Markets\Resources;

use App\Domain\Markets\Entities\NewsArticle;
use App\Http\Markets\Mappers\NewsMapper;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class NewsCollectionResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'news' => $this->map(
                fn ($newsArticle) => NewsMapper::map($newsArticle),
            ),
        ];
    }
}
