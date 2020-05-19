<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Coinfo\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class FetchNews extends Command
{
    protected $signature = 'coinsight:fetch-news';

    protected $description = 'Fetch news about cryptocurrencies';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(Client $client)
    {
        $news = $client->news();

        $newsRecords = [];
        foreach ($news as $newsArticle) {
            $newsRecords[] = [
                'title' => $newsArticle->title,
                'content' => $newsArticle->content,
                'published_at' => $newsArticle->publishedAt,
                'author' => $newsArticle->author,
            ];
        }

        DB::table('news')->insert($newsRecords);
    }
}
