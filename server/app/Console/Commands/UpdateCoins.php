<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Coinfo\Client;
use App\Coinfo\Types\CoinMarketData;
use App\Coinfo\Types\CoinProfile;
use App\Domain\Markets\Models\Coin;
use App\Domain\Markets\Models\CoinLink;
use App\Domain\Markets\Models\CoinProfile as CoinProfileModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class UpdateCoins extends Command
{
    protected $signature = 'coinsight:update-coins';

    protected $description = 'Update coins list';

    private array $coinErrors = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(Client $client)
    {
        $retrievedCoins = collect($client->markets());
        $this->line("\n" . $retrievedCoins->count() . " coins loaded.");

        $missingCoins = $this->getMissingCoins($retrievedCoins);
        $missingCoinsCount = $missingCoins->count();

        $this->line("Found {$missingCoinsCount} new coin(s).");

        if ($missingCoinsCount === 0) {
            return;
        }

        $this->line("\nSaving new coin(s) to the database:");

        $bar = $this->output->createProgressBar($missingCoinsCount);
        $bar->start();

        foreach ($missingCoins as $missingCoin) {
            try {
                $coin = $this->saveCoin($missingCoin);
                $coinProfile = $client->coinProfile($coin->name);
                $coin->profile()->save($this->makeCoinProfile($coinProfile));
                $coin->links()->saveMany($this->makeCoinLinks($coinProfile));

            } catch (Exception $exception) {
                $this->addError($missingCoin, $exception);
            }

            sleep(1);

            $bar->advance();
        }

        $bar->finish();
        $this->line("\n");

        if (count($this->coinErrors) === 0) {
            $this->info("All coins successfully stored to the database.");
            $this->info("No errors occurred.");
        } else {
            $savedCount = $missingCoinsCount - count($this->coinErrors);
            $this->info("{$savedCount} coin(s) stored to the database.");
            $this->line("\nOccurred error(s): ");
            $this->table(['Coin name', 'Error'], $this->coinErrors);
        }
    }

    private function getMissingCoins(Collection $retrievedCoins): Collection
    {
        $names = $retrievedCoins->pluck('name');
        $persistedCoins = Coin::whereIn('name', $names)->get()->toBase();

        return $retrievedCoins->filter(fn (CoinMarketData $coin) =>
            !$persistedCoins->contains('name', $coin->name)
        );
    }

    private function saveCoin(CoinMarketData $coinMarketData): Coin
    {
        try {
            $iconUrl = $this->saveIcon($coinMarketData);
        } catch (Exception $exception) {
            $this->addError($coinMarketData, $exception);
        }

        $coin = new Coin();
        $coin->name = $coinMarketData->name;
        $coin->symbol = $coinMarketData->symbol;
        $coin->icon = $iconUrl ?? null;
        $coin->coin_gecko_id = $coinMarketData->id;
        $coin->save();

        return $coin;
    }

    private function saveIcon(CoinMarketData $coinMarketData): string
    {
        $filename = Str::slug($coinMarketData->name) . '.png';
        $filePath = 'public/' . $filename;

        if (Storage::missing($filePath)) {
            Storage::put($filePath, file_get_contents($coinMarketData->icon));
        }

        return Storage::url($filename);
    }

    private function addError(CoinMarketData $coin, Exception $e): void
    {
        $this->coinErrors[] = [
            'coin_name' => $coin->name,
            'error' => $e->getMessage(),
        ];
    }

    private function makeCoinProfile(CoinProfile $coinProfile): CoinProfileModel
    {
        $coinProfileModel = new CoinProfileModel();

        $coinProfileModel->tagline = $coinProfile->tagline;
        $coinProfileModel->description = $coinProfile->description;
        $coinProfileModel->type = $coinProfile->type;
        $coinProfileModel->genesis_date = $coinProfile->genesisDate;
        $coinProfileModel->consensus_mechanism = $coinProfile->consensusMechanism;
        $coinProfileModel->hashing_algorithm = $coinProfile->hashingAlgorithm;

        return $coinProfileModel;
    }

    private function makeCoinLinks(CoinProfile $coinProfile): array
    {
        $coinLinks = [];

        foreach ($coinProfile->links as $link) {
            $coinLink = new CoinLink();
            $coinLink->type = $link->type;
            $coinLink->link = $link->link;
            $coinLinks[] = $coinLink;
        }

        if (!$coinProfile->blockExplorers) {
            return $coinLinks;
        }

        foreach ($coinProfile->blockExplorers as $blockExplorer) {
            $coinLink = new CoinLink();
            $coinLink->type = $blockExplorer->type;
            $coinLink->link = $blockExplorer->link;
            $coinLinks[] = $coinLink;
        }

        return $coinLinks;
    }
}
