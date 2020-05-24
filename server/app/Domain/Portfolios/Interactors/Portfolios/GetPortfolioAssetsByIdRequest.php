<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Interactors\Portfolios;

use App\Domain\Common\Contracts\PaginationRequest;
use Spatie\DataTransferObject\DataTransferObject;

final class GetPortfolioAssetsByIdRequest extends DataTransferObject implements PaginationRequest
{
    public int $userId;
    public int $portfolioId;
    public int $page = self::DEFAULT_PAGE;
    public int $perPage = self::DEFAULT_PER_PAGE;
}
