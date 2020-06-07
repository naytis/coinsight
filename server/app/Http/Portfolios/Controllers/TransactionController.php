<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Controllers;

use App\Domain\Portfolios\Interactors\Transactions\CreateTransactionInteractor;
use App\Domain\Portfolios\Interactors\Transactions\CreateTransactionRequest;
use App\Domain\Portfolios\Interactors\Transactions\DeleteTransactionByIdInteractor;
use App\Domain\Portfolios\Interactors\Transactions\DeleteTransactionByIdRequest;
use App\Domain\Portfolios\Interactors\Transactions\GetTransactionsInteractor;
use App\Domain\Portfolios\Interactors\Transactions\GetTransactionsRequest;
use App\Domain\Portfolios\Interactors\Transactions\UpdateTransactionByIdInteractor;
use App\Domain\Portfolios\Interactors\Transactions\UpdateTransactionByIdRequest;
use App\Http\Common\Mappers\PaginationMetaMapper;
use App\Http\Common\Resources\IdResource;
use App\Http\Portfolios\Requests\CreateTransactionApiRequest;
use App\Http\Portfolios\Requests\DeleteTransactionByIdApiRequest;
use App\Http\Portfolios\Requests\GetTransactionsApiRequest;
use App\Http\Portfolios\Requests\UpdateTransactionByIdApiRequest;
use App\Http\Portfolios\Resources\TransactionCollectionResource;
use App\Http\Portfolios\Resources\TransactionResource;
use App\Http\Common\ApiResponse;

final class TransactionController
{
    public function createTransaction(
        CreateTransactionApiRequest $request,
        CreateTransactionInteractor $createTransactionInteractor
    ): ApiResponse {
        $transaction = $createTransactionInteractor
            ->execute(new CreateTransactionRequest([
                'userId' => $request->userId(),
                'portfolioId' => $request->portfolioId(),
                'coinId' => $request->coinId(),
                'type' => $request->type(),
                'pricePerCoin' => $request->pricePerCoin(),
                'quantity' => $request->quantity(),
                'fee' => $request->fee(),
                'datetime' => $request->datetime(),
            ]))
            ->transaction;

        return ApiResponse::success(new TransactionResource($transaction));
    }

    public function getTransactions(
        GetTransactionsApiRequest $request,
        GetTransactionsInteractor $transactionsInteractor
    ): ApiResponse {
        $transactionsResponse = $transactionsInteractor->execute(
            new GetTransactionsRequest([
                'userId' => $request->userId(),
                'portfolioId' => $request->portfolioId(),
                'page' => $request->page(),
                'perPage' => $request->perPage(),
                'sort' => $request->sort(),
                'direction' => $request->direction(),
            ])
        );

        return ApiResponse::success(
            new TransactionCollectionResource($transactionsResponse->transactions),
            PaginationMetaMapper::map($transactionsResponse->meta),
        );
    }

    public function updateTransactionById(
        UpdateTransactionByIdApiRequest $request,
        UpdateTransactionByIdInteractor $updateTransactionByIdInteractor
    ): ApiResponse {
        $transaction = $updateTransactionByIdInteractor
            ->execute(new UpdateTransactionByIdRequest([
                'transactionId' => $request->transactionId(),
                'userId' => $request->userId(),
                'portfolioId' => $request->newPortfolioId(),
                'coinId' => $request->coinId(),
                'type' => $request->type(),
                'pricePerCoin' => $request->pricePerCoin(),
                'quantity' => $request->quantity(),
                'fee' => $request->fee(),
                'datetime' => $request->datetime(),
            ]))
            ->transaction;

        return ApiResponse::success(new TransactionResource($transaction));
    }

    public function deleteTransactionById(
        DeleteTransactionByIdApiRequest $request,
        DeleteTransactionByIdInteractor $deleteTransactionByIdInteractor
    ): ApiResponse {
        $deleteTransactionResponse = $deleteTransactionByIdInteractor->execute(
            new DeleteTransactionByIdRequest([
                'userId' => $request->userId(),
                'transactionId' => $request->transactionId(),
            ])
        );

        return ApiResponse::success(new IdResource($deleteTransactionResponse));
    }
}
