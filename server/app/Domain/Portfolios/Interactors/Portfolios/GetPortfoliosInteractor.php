<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use App\Domain\Common\Responses\PaginationMeta;
use App\Domain\Portfolios\Entities\Portfolio as PortfolioEntity;
use App\Domain\Portfolios\Models\Portfolio;

final class GetPortfoliosInteractor
{
    public function execute(GetPortfoliosRequest $request): GetPortfoliosResponse
    {
        $portfoliosPaginator = Portfolio::orderBy($request->sort, $request->direction)
            ->whereUserId($request->userId)
            ->paginate($request->perPage, ['*'], null, $request->page);

        $portfolios = $portfoliosPaginator->map(
            fn (Portfolio $session) => PortfolioEntity::fromModel($session)
        );

        return new GetPortfoliosResponse([
            'portfolios' => $portfolios,
            'meta' => PaginationMeta::fromPaginator($portfoliosPaginator),
        ]);
    }
}
