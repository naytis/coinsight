<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Resources;

use App\Http\Portfolios\Mappers\TransactionMapper;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class TransactionResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'transaction' => TransactionMapper::map($this->resource),
        ];
    }
}
