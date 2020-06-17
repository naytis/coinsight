<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use App\Domain\Portfolios\Models\Portfolio;

final class DeletePortfolioByIdInteractor
{
    public function execute(DeletePortfolioByIdRequest $request): DeletePortfolioByIdResponse
    {
        $portfolio = Portfolio::whereId($request->portfolioId)
            ->whereUserId($request->userId)
            ->firstOrFail();

        $portfolio->delete();

        return new DeletePortfolioByIdResponse([
            'id' => $portfolio->id,
        ]);
    }
}
