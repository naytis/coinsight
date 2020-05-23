<?php

declare(strict_types=1);

namespace App\Domain\Markets\Interactors\GlobalStats;

use App\Domain\Markets\Entities\GlobalStats;
use Illuminate\Support\Facades\DB;

final class GetGlobalStatsInteractor
{
    private const BITCOIN_NAME = 'Bitcoin';

    public function execute(): GetGlobalStatsResponse
    {
        $globalStats = DB::table('coin_market_data')
            ->select([
                DB::raw("sum(market_cap) as global_market_cap"),
                DB::raw("sum(volume) as global_volume"),
                DB::raw("(
                    select market_cap from coin_market_data where coin_id = (
                        select id from coins where name = '" . self::BITCOIN_NAME . "'
                    )
                ) as bitcoin_market_cap"),
            ])->first();

        $bitcoinDominance = $globalStats->bitcoin_market_cap / $globalStats->global_market_cap * 100;

        return new GetGlobalStatsResponse([
            'globalStats' => new GlobalStats([
                'marketCap' => (int) $globalStats->global_market_cap,
                'volume' => (int) $globalStats->global_volume,
                'bitcoinDominance' => (float) $bitcoinDominance,
            ])
        ]);
    }
}
