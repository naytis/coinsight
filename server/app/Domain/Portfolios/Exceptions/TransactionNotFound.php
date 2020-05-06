<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Exceptions;

use App\Domain\Common\Exceptions\ModelNotFound;

final class TransactionNotFound extends ModelNotFound
{
    protected $message = 'Transaction not found.';
}
