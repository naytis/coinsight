<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Sessions;

use App\Domain\Users\Entities\Session as SessionEntity;
use App\Domain\Users\Models\Session;

final class GetActiveSessionByIdInteractor
{
    public function execute(GetActiveSessionByIdRequest $request): GetActiveSessionByIdResponse
    {
        $session = Session::whereId($request->id)->active()->firstOrFail();

        return new GetActiveSessionByIdResponse([
            'session' => SessionEntity::fromModel($session)
        ]);
    }
}
