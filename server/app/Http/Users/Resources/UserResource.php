<?php

declare(strict_types=1);

namespace App\Http\Users\Resources;

use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'user' => [
                'id' => $this->id,
                'username' => $this->username,
                'email' => $this->email,
            ],
        ];
    }
}
