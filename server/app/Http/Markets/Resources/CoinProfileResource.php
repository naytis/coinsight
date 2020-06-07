<?php

declare(strict_types=1);

namespace App\Http\Markets\Resources;

use App\Domain\Markets\Entities\Link;
use App\Http\Markets\Mappers\CoinMapper;
use App\Support\Contracts\Response;
use Illuminate\Http\Resources\Json\JsonResource;

final class CoinProfileResource extends JsonResource implements Response
{
    public function toArray($request): array
    {
        return [
            'coin' => CoinMapper::map($this->coin),
            'profile' => [
                'tagline' => $this->profile->tagline,
                'description' => $this->profile->description,
                'type' => $this->profile->type,
                'genesis_date' => $this->profile->genesisDate,
                'consensus_mechanism' => $this->profile->consensusMechanism,
                'hashing_algorithm' => $this->profile->hashingAlgorithm,
                'links' => $this->profile->links->map(fn (Link $link) => [
                    'type' => $link->type,
                    'link' => $link->link,
                ]),
            ],
        ];
    }
}
