<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Mappers;

use App\Domain\Portfolios\Entities\Transaction;
use App\Http\Markets\Mappers\CoinMapper;

final class TransactionMapper
{
    public static function map(Transaction $transaction): array
    {
        return [
            'id' => $transaction->id,
            'coin' => CoinMapper::map($transaction->coin),
            'type' => $transaction->type->value,
            'price_per_coin' => $transaction->pricePerCoin,
            'quantity' => $transaction->quantity,
            'fee' => $transaction->fee,
            'cost' => $transaction->cost,
            'current_value' => $transaction->currentValue,
            'value_change' => $transaction->valueChange,
            'datetime' => $transaction->datetime,
            'portfolio_id' => $transaction->portfolioId,
        ];
    }
}
