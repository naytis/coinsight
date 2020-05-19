<?php

declare(strict_types=1);

namespace App\Http\Markets\Resources;

use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class NewsArticleResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'published_at' => $this->publishedAt,
            'author' => $this->author,
        ];
    }
}
