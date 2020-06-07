<?php

declare(strict_types=1);

namespace App\Http\Users\Resources;

use App\Domain\Users\Entities\Session;
use App\Http\Users\Mappers\SessionMapper;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class SessionCollectionResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'sessions' => $this->map(fn ($session) => SessionMapper::map($session)),
        ];
    }
}
