<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Controllers;

use App\Domain\Portfolios\Interactors\Portfolios\CreatePortfolioInteractor;
use App\Domain\Portfolios\Interactors\Portfolios\CreatePortfolioRequest;
use App\Domain\Portfolios\Interactors\Portfolios\DeletePortfolioByIdInteractor;
use App\Domain\Portfolios\Interactors\Portfolios\DeletePortfolioByIdRequest;
use App\Domain\Portfolios\Interactors\Portfolios\GetPortfolioAssetsByIdInteractor;
use App\Domain\Portfolios\Interactors\Portfolios\GetPortfolioAssetsByIdRequest;
use App\Domain\Portfolios\Interactors\Portfolios\GetPortfolioOverviewByIdInteractor;
use App\Domain\Portfolios\Interactors\Portfolios\GetPortfolioOverviewByIdRequest;
use App\Domain\Portfolios\Interactors\Portfolios\GetPortfolioChartByIdInteractor;
use App\Domain\Portfolios\Interactors\Portfolios\GetPortfolioChartByIdRequest;
use App\Domain\Portfolios\Interactors\Portfolios\GetPortfoliosInteractor;
use App\Domain\Portfolios\Interactors\Portfolios\GetPortfoliosRequest;
use App\Domain\Portfolios\Interactors\Portfolios\UpdatePortfolioByIdInteractor;
use App\Domain\Portfolios\Interactors\Portfolios\UpdatePortfolioByIdRequest;
use App\Http\Common\Mappers\PaginationMetaMapper;
use App\Http\Common\Resources\IdResource;
use App\Http\Common\Resources\MetaResource;
use App\Http\Portfolios\Requests\CreatePortfolioApiRequest;
use App\Http\Portfolios\Requests\DeletePortfolioByIdApiRequest;
use App\Http\Portfolios\Requests\GetPortfolioAssetsByIdApiRequest;
use App\Http\Portfolios\Requests\GetPortfolioByIdApiRequest;
use App\Http\Portfolios\Requests\GetPortfoliosApiRequest;
use App\Http\Portfolios\Requests\UpdatePortfolioByIdApiRequest;
use App\Http\Portfolios\Resources\PortfolioAssetsResource;
use App\Http\Portfolios\Resources\PortfolioChartResource;
use App\Http\Portfolios\Resources\PortfolioCollectionResource;
use App\Http\Portfolios\Resources\PortfolioOverviewResource;
use App\Http\Portfolios\Resources\PortfolioResource;
use App\Http\Common\ApiResponse;

final class PortfolioController
{
    public function createPortfolio(
        CreatePortfolioApiRequest $request,
        CreatePortfolioInteractor $createPortfolioInteractor
    ): ApiResponse {
        $portfolio = $createPortfolioInteractor
            ->execute(new CreatePortfolioRequest([
                'name' => $request->name(),
                'userId' => $request->userId(),
            ]))
            ->portfolio;

        return ApiResponse::success(new PortfolioResource($portfolio));
    }

    public function getPortfolios(
        GetPortfoliosApiRequest $request,
        GetPortfoliosInteractor $portfoliosInteractor
    ): ApiResponse {
        $portfoliosResponse = $portfoliosInteractor->execute(
            new GetPortfoliosRequest([
                'userId' => $request->userId(),
                'page' => $request->page(),
                'perPage' => $request->perPage(),
                'sort' => $request->sort(),
                'direction' => $request->direction(),
            ])
        );

        return ApiResponse::success(
            new PortfolioCollectionResource($portfoliosResponse->portfolios),
            PaginationMetaMapper::map($portfoliosResponse->meta),
        );
    }

    public function getPortfolioOverviewById(
        GetPortfolioByIdApiRequest $request,
        GetPortfolioOverviewByIdInteractor $portfolioByIdInteractor
    ): ApiResponse {
        $portfolioOverview = $portfolioByIdInteractor
            ->execute(new GetPortfolioOverviewByIdRequest([
                'userId' => $request->userId(),
                'portfolioId' => $request->id(),
            ]))
            ->overview;

        return ApiResponse::success(new PortfolioOverviewResource($portfolioOverview));
    }

    public function getPortfolioChartById(
        GetPortfolioByIdApiRequest $request,
        GetPortfolioChartByIdInteractor $portfolioChartByIdInteractor
    ): ApiResponse {
        $portfolioChart = $portfolioChartByIdInteractor
            ->execute(new GetPortfolioChartByIdRequest([
                'userId' => $request->userId(),
                'portfolioId' => $request->id(),
            ]))
            ->portfolioValueByTime;

        return ApiResponse::success(new PortfolioChartResource($portfolioChart));
    }

    public function getPortfolioAssetsById(
        GetPortfolioAssetsByIdApiRequest $request,
        GetPortfolioAssetsByIdInteractor $portfolioAssetsByIdInteractor
    ): ApiResponse {
        $portfolioAssetsResponse = $portfolioAssetsByIdInteractor->execute(
            new GetPortfolioAssetsByIdRequest([
                'userId' => $request->userId(),
                'portfolioId' => $request->id(),
                'page' => $request->page(),
                'perPage' => $request->perPage(),
            ])
        );

        return ApiResponse::success(
            new PortfolioAssetsResource($portfolioAssetsResponse->assets),
            PaginationMetaMapper::map($portfolioAssetsResponse->meta),
        );
    }

    public function updatePortfolioById(
        UpdatePortfolioByIdApiRequest $request,
        UpdatePortfolioByIdInteractor $updatePortfolioByIdInteractor
    ): ApiResponse {
        $portfolio = $updatePortfolioByIdInteractor
            ->execute(new UpdatePortfolioByIdRequest([
                'portfolioId' => $request->id(),
                'userId' => $request->userId(),
                'name' => $request->name(),
            ]))
            ->portfolio;

        return ApiResponse::success(new PortfolioResource($portfolio));
    }

    public function deletePortfolioById(
        DeletePortfolioByIdApiRequest $request,
        DeletePortfolioByIdInteractor $deletePortfolioByIdInteractor
    ): ApiResponse {
        $deletedPortfolioResponse = $deletePortfolioByIdInteractor->execute(
            new DeletePortfolioByIdRequest([
                'portfolioId' => $request->id(),
                'userId' => $request->userId(),
            ])
        );

        return ApiResponse::success(new IdResource($deletedPortfolioResponse));
    }
}
