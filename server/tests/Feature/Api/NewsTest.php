<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Domain\Markets\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

final class NewsTest extends ApiTestCase
{
    use RefreshDatabase;

    public function test_get_news()
    {
        factory(News::class, 10)->create();

        $this
            ->apiGet("news")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'news' => [
                        '*' => [
                            'id',
                            'title',
                            'content',
                            'published_at',
                            'author',
                        ],
                    ],
                ],
                'meta' => [
                    'total',
                    'page',
                    'per_page',
                    'last_page',
                ],
            ]);
    }

    public function test_get_news_article()
    {
        $id = factory(News::class)->create()->id;

        $this
            ->apiGet("news/{$id}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'content',
                    'published_at',
                    'author',
                ],
            ]);
    }
}
