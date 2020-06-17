<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Domain\Markets\Entities\Coin as CoinEntity;
use App\Domain\Markets\Entities\Profile;
use App\Domain\Markets\Models\Coin as CoinModel;

final class GetProfileInteractor
{
    public function execute(GetProfileRequest $request): GetProfileResponse
    {
        $coin = CoinModel::with(['profile', 'links'])->findOrFail($request->id);

        return new GetProfileResponse([
            'coin' => CoinEntity::fromModel($coin),
            'profile' => Profile::fromModel($coin)
        ]);
    }
}
