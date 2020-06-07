<?php

declare(strict_types=1);

namespace App\Domain\Markets\Entities;

use App\Domain\Markets\Models\Coin as CoinModel;
use App\Domain\Markets\Models\CoinLink as CoinLinkModel;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;

final class Profile extends DataTransferObject
{
    public ?string $tagline;
    public ?string $description;
    public ?string $type;
    public ?Carbon $genesisDate;
    public ?string $consensusMechanism;
    public ?string $hashingAlgorithm;
    public Collection $links;

    public static function fromModel(CoinModel $coinModel): self
    {
        return new static([
            'tagline' => $coinModel->profile->tagline,
            'description' => $coinModel->profile->description,
            'type' => $coinModel->profile->type,
            'genesisDate' => $coinModel->profile->genesis_date,
            'consensusMechanism' => $coinModel->profile->consensus_mechanism,
            'hashingAlgorithm' => $coinModel->profile->hashing_algorithm,
            'links' => $coinModel->links->map(
                fn (CoinLinkModel $link) => Link::fromModel($link)
            ),
        ]);
    }
}
