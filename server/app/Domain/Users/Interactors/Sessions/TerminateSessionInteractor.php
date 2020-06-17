<?php

declare(strict_types=1);

namespace App\Domain\Users\Interactors\Sessions;

use App\Domain\Users\Models\Session;
use Carbon\Carbon;

final class TerminateSessionInteractor
{
    public function execute(TerminateSessionRequest $request): TerminateSessionResponse
    {
        $session = Session::whereId($request->sessionId)
            ->whereUserId($request->userId)
            ->active()
            ->firstOrFail();

        $session->terminated_at = Carbon::now();
        $session->save();

        return new TerminateSessionResponse([
            'id' => $session->id
        ]);
    }
}
