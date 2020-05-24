<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Resources;

use App\Domain\Portfolios\Entities\ValueByTime;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class PortfolioChartResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'chart' => $this->map(fn (ValueByTime $valueByTime) => [
                'timestamp' => $valueByTime->datetime->getPreciseTimestamp(3),
                'value' => $valueByTime->value,
            ]),
        ];
    }
}
