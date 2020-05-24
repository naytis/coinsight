<?php

declare(strict_types=1);

namespace App\Domain\Markets\Models;

use App\Domain\Portfolios\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Coin extends Model
{
    public function profile(): HasOne
    {
        return $this->hasOne(CoinProfile::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(CoinLink::class);
    }

    public function marketData(): HasOne
    {
        return $this->hasOne(CoinMarketData::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
