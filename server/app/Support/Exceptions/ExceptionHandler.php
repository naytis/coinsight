<?php

declare(strict_types=1);

namespace App\Support\Exceptions;

use App\Domain\Markets\Exceptions\CoinNotFound;
use App\Domain\Markets\Exceptions\NewsArticleNotFound;
use App\Domain\Markets\Models\Coin;
use App\Domain\Markets\Models\News;
use App\Domain\Portfolios\Exceptions\PortfolioNotFound;
use App\Domain\Portfolios\Exceptions\TransactionNotFound;
use App\Domain\Portfolios\Models\Portfolio;
use App\Domain\Portfolios\Models\Transaction;
use App\Domain\Users\Exceptions\SessionNotFound;
use App\Domain\Users\Exceptions\UserNotFound;
use App\Domain\Users\Models\Session;
use App\Domain\Users\Models\User;
use App\Http\Common\ApiResponse;
use App\Http\Common\Exceptions\RequestValidation;
use App\Http\Common\Exceptions\UnknownException;
use App\Support\Contracts\Exception as ExceptionContract;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Validation\ValidationException;
use Throwable;

final class ExceptionHandler extends Handler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return ApiResponse::error(new RequestValidation($exception->validator));
        }

        if ($exception instanceof ModelNotFoundException) {
            $exception = [
                User::class => new UserNotFound(),
                Session::class => new SessionNotFound(),
                Coin::class => new CoinNotFound(),
                News::class => new NewsArticleNotFound(),
                Portfolio::class => new PortfolioNotFound(),
                Transaction::class => new TransactionNotFound(),
            ][$exception->getModel()];
        }

        if ($exception instanceof ExceptionContract) {
            return ApiResponse::error($exception);
        }

        if ($exception instanceof Exception) {
            return ApiResponse::error(new UnknownException($exception->getMessage()));
        }

        return parent::render($request, $exception);
    }
}
