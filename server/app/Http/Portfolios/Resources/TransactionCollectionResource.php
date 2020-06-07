<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Resources;

use App\Http\Portfolios\Mappers\TransactionMapper;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class TransactionCollectionResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'transactions' => $this->map(
                fn($transaction) => TransactionMapper::map($transaction)
            ),
        ];
    }
}
