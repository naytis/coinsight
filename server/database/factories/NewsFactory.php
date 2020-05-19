<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\Markets\Models\News;
use Illuminate\Support\Str;

$factory->define(News::class, function () {
    $randomString = Str::random();

    return [
        'title' => $randomString,
        'content' => $randomString,
        'published_at' => now(),
        'author' => $randomString,
    ];
});
