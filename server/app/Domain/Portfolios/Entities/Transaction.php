<?php

declare(strict_types=1);

namespace App\Domain\Portfolios\Entities;

use App\Domain\Markets\Entities\Coin;
use App\Domain\Portfolios\Enums\TransactionType;
use App\Domain\Portfolios\Models\Transaction as TransactionModel;
use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

final class Transaction extends DataTransferObject
{
    public int $id;
    public Coin $coin;
    public TransactionType $type;
    public float $pricePerCoin;
    public float $quantity;
    public float $fee;
    public ?float $cost;
    public ?float $currentValue;
    public ?float $valueChange;
    public Carbon $datetime;

    public static function fromModel(TransactionModel $transaction): self {
        return new static([
            'id' => $transaction->id,
            'coin' => Coin::fromModel($transaction->coin),
            'type' => new TransactionType($transaction->type),
            'pricePerCoin' => $transaction->price_per_coin,
            'quantity' => $transaction->quantity,
            'fee' => $transaction->fee,
            'datetime' => $transaction->datetime,
        ]);
    }
}
