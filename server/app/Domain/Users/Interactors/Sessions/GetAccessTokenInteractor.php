<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Sessions;

use App\Domain\Users\Models\Session;
use App\Domain\Users\Services\TokenService;

final class GetAccessTokenInteractor
{
    private TokenService $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function execute(GetAccessTokenRequest $request): GetAccessTokenResponse
    {
        $session = Session::findOrFail($request->id);

        $session->last_used_at = now();
        $session->save();

        $accessToken = $this->tokenService->generateAccessToken($session->id);

        return new GetAccessTokenResponse([
            'accessToken' => $accessToken,
        ]);
    }
}
