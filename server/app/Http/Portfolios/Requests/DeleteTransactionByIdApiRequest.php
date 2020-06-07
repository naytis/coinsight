<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Requests;

use App\Http\Common\Requests\ApiRequest;
use App\Http\Common\Requests\AuthContextTrait;

final class DeleteTransactionByIdApiRequest extends ApiRequest
{
    use AuthContextTrait;

    public function transactionId(): int
    {
        return (int) $this->route('transaction_id');
    }
}
