<?php

declare(strict_types=1);

namespace App\Http\Markets\Controllers;

use App\Domain\Markets\Interactors\News\GetNewsInteractor;
use App\Domain\Markets\Interactors\News\GetNewsRequest;
use App\Http\Common\ApiResponse;
use App\Http\Markets\Requests\GetNewsApiRequest;
use App\Http\Markets\Resources\NewsCollectionResource;

final class NewsController
{
    public function getNews(
        GetNewsApiRequest $request,
        GetNewsInteractor $newsInteractor
    ): ApiResponse {
        $newsResponse = $newsInteractor->execute(
            new GetNewsRequest([
                'page' => $request->page(),
                'perPage' => $request->perPage(),
                'sort' => $request->sort(),
                'direction' => $request->direction(),
            ])
        );

        return ApiResponse::success(
            new NewsCollectionResource($newsResponse->news),
            [
                'total' => $newsResponse->total,
                'page' => $newsResponse->page,
                'per_page' => $newsResponse->perPage,
                'last_page' => $newsResponse->lastPage,
            ]
        );
    }
}
