<?php

declare(strict_types=1);

namespace App\Http\Users\Resources;

use App\Http\Users\Mappers\SessionMapper;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class SessionResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'session' => SessionMapper::map($this->resource),
        ];
    }
}
