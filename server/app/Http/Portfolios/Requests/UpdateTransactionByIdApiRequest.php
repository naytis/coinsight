<?php

declare(strict_types=1);

namespace App\Http\Portfolios\Requests;

use App\Domain\Portfolios\Enums\TransactionType;
use App\Http\Common\Requests\ApiRequest;
use App\Http\Common\Requests\AuthContextTrait;
use Carbon\Carbon;

final class UpdateTransactionByIdApiRequest extends ApiRequest
{
    use AuthContextTrait;

    public function rules(): array
    {
        return [
            'portfolio_id' => 'integer|min:1',
            'coin_id' => 'integer|min:1',
            'type' => 'in:buy,sell',
            'price_per_coin' => 'numeric|min:0',
            'quantity' => 'numeric|min:0',
            'fee' => 'numeric|min:0',
            'datetime' => 'date',
        ];
    }

    public function transactionId(): int
    {
        return (int) $this->route('transaction_id');
    }

    public function currentPortfolioId(): int
    {
        return (int) $this->route('portfolio_id');
    }

    public function newPortfolioId(): ?int
    {
        return $this->has('portfolio_id') ? (int) $this->get('portfolio_id') : null;
    }

    public function coinId(): ?int
    {
        return $this->has('coin_id') ? (int) $this->get('coin_id') : null;
    }

    public function type(): ?TransactionType
    {
        return $this->has('type') ? new TransactionType($this->get('type')) : null;
    }

    public function pricePerCoin(): ?float
    {
        return $this->has('price_per_coin') ? (float) $this->get('price_per_coin') : null;
    }

    public function quantity(): ?float
    {
        return $this->has('quantity') ? (float) $this->get('quantity') : null;
    }

    public function fee(): ?float
    {
        return $this->has('fee') ? (float) $this->get('fee') : null;
    }

    public function datetime(): ?Carbon
    {
        return $this->has('datetime') ? Carbon::parse($this->get('datetime')) : null;
    }
}
