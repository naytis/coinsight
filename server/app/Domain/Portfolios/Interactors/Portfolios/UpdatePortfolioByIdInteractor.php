<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use App\Domain\Portfolios\Entities\Portfolio;
use App\Domain\Portfolios\Models\Portfolio as PortfolioModel;

final class UpdatePortfolioByIdInteractor
{
    public function execute(UpdatePortfolioByIdRequest $request): UpdatePortfolioByIdResponse
    {
        $portfolio = PortfolioModel::whereId($request->portfolioId)
            ->whereUserId($request->userId)
            ->firstOrFail();

        $portfolio->name = $request->name;
        $portfolio->save();

        return new UpdatePortfolioByIdResponse([
            'portfolio' => Portfolio::fromModel($portfolio),
        ]);
    }
}
