<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Sessions;

use App\Domain\Common\Responses\PaginationMeta;
use App\Domain\Users\Entities\Session as SessionEntity;
use App\Domain\Users\Models\Session;

final class GetUserActiveSessionsInteractor
{
    public function execute(GetUserActiveSessionsRequest $request): GetUserActiveSessionsResponse
    {
        $sessionsPaginator = Session::orderBy($request->sort, $request->direction)
            ->whereUserId($request->id)
            ->active()
            ->paginate($request->perPage, ['*'], null, $request->page);

        $sessions = $sessionsPaginator->map(
            fn(Session $session) => SessionEntity::fromModel($session)
        );

        return new GetUserActiveSessionsResponse([
            'sessions' => $sessions,
            'meta' => PaginationMeta::fromPaginator($sessionsPaginator),
        ]);
    }
}
