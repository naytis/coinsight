<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\Coins;

use App\Coinfo\Client;
use App\Domain\Markets\Entities\Coin;
use App\Domain\Markets\Entities\Profile;
use App\Domain\Markets\Services\CoinService;

final class GetProfileInteractor
{
    private Client $client;
    private CoinService $coinService;

    public function __construct(Client $client, CoinService $coinService)
    {
        $this->client = $client;
        $this->coinService = $coinService;
    }

    public function execute(GetProfileRequest $request): GetProfileResponse
    {
        $coin = $this->coinService->getById($request->id, ['profile', 'links']);

        return new GetProfileResponse([
            'coin' => Coin::fromModel($coin),
            'profile' => Profile::fromModel($coin)
        ]);
    }
}
