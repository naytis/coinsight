<?php

declare(strict_types=1);

namespace App\Http\Users\Controllers;

use App\Domain\Users\Interactors\Sessions\GetAccessTokenInteractor;
use App\Domain\Users\Interactors\Sessions\GetAccessTokenRequest;
use App\Domain\Users\Interactors\Sessions\GetUserActiveSessionsInteractor;
use App\Domain\Users\Interactors\Sessions\GetUserActiveSessionsRequest;
use App\Domain\Users\Interactors\Sessions\TerminateSessionInteractor;
use App\Domain\Users\Interactors\Sessions\TerminateSessionRequest;
use App\Http\Common\ApiResponse;
use App\Http\Common\Mappers\PaginationMetaMapper;
use App\Http\Common\Requests\DefaultRequest;
use App\Http\Common\Resources\IdResource;
use App\Http\Users\Requests\GetSessionsApiRequest;
use App\Http\Users\Requests\TerminateSessionApiRequest;
use App\Http\Users\Resources\AccessTokenResource;
use App\Http\Users\Resources\SessionCollectionResource;

final class SessionController
{
    public function getSessions(
        GetSessionsApiRequest $request,
        GetUserActiveSessionsInteractor $getUserActiveSessionsInteractor
    ): ApiResponse {
        $sessionsResponse = $getUserActiveSessionsInteractor->execute(
            new GetUserActiveSessionsRequest([
                'id' => $request->userId(),
                'page' => $request->page(),
                'perPage' => $request->perPage(),
                'sort' => $request->sort(),
                'direction' => $request->direction(),
            ])
        );

        return ApiResponse::success(
            new SessionCollectionResource($sessionsResponse->sessions),
            PaginationMetaMapper::map($sessionsResponse->meta),
        );
    }

    public function getAccessToken(
        DefaultRequest $request,
        GetAccessTokenInteractor $accessTokenInteractor
    ): ApiResponse {
        $accessTokenResponse = $accessTokenInteractor->execute(
            new GetAccessTokenRequest([
                'id' => $request->sessionId(),
            ])
        );

        return ApiResponse::success(new AccessTokenResource($accessTokenResponse));
    }

    public function terminate(
        TerminateSessionApiRequest $request,
        TerminateSessionInteractor $terminateSessionInteractor
    ): ApiResponse {
        $terminateSessionResponse = $terminateSessionInteractor->execute(
            new TerminateSessionRequest([
                'sessionId' => $request->id(),
                'userId' => $request->userId(),
            ])
        );

        return ApiResponse::success(new IdResource($terminateSessionResponse));
    }
}
