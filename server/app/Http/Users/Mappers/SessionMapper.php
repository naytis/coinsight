<?php

declare(strict_types=1);

namespace App\Http\Users\Mappers;

use App\Domain\Users\Entities\Session;

final class SessionMapper
{
    public static function map(Session $session): array
    {
        return [
            'id' => $session->id,
            'user_id' => $session->userId,
            'created_at' => $session->createdAt,
            'last_used_at' => $session->lastUsedAt,
        ];
    }
}
