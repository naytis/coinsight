<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Users\Controllers')->group(function () {
    Route::group([
        'prefix' => 'auth',
    ], function() {
        Route::post('/register', 'AuthController@register');
        Route::post('/login', 'AuthController@login');
        Route::middleware('token:access')
            ->get('/me', 'AuthController@me');
    });

    Route::group([
        'prefix' => 'sessions',
    ], function() {
        Route::group([
            'middleware' => 'token:access'
        ], function () {
            Route::get('/', 'SessionController@getSessions');
            Route::put('/terminate', 'SessionController@terminate');
        });

        Route::middleware('token:refresh')
            ->get('/refresh', 'SessionController@getAccessToken');
    });
});

Route::namespace('Markets\Controllers')->group(function () {
    Route::get('/global', 'GlobalStatsController@getGlobalStats');

    Route::group([
        'prefix' => 'coins',
    ], function () {
        Route::get('/', 'CoinController@getCoins');
        Route::get('/{id}/profile', 'CoinController@getCoinProfile');
        Route::get('/{id}/latest', 'CoinController@getCoinMarketData');
        Route::get('/{id}/historical', 'CoinController@getCoinHistoricalData');
    });

    Route::group([
        'prefix' => 'news',
    ], function () {
       Route::get('/', 'NewsController@getNews');
       Route::get('/{id}', 'NewsController@getNewsArticleById');
    });
});

Route::namespace('Portfolios\Controllers')->group(function () {
    Route::group([
        'prefix' => 'portfolios',
        'middleware' => 'token:access',
    ], function () {
        Route::post('/', 'PortfolioController@createPortfolio');
        Route::get('/', 'PortfolioController@getPortfolios');
        Route::get('/{id}', 'PortfolioController@getPortfolioOverviewById');
        Route::get('/{id}/chart', 'PortfolioController@getPortfolioChartById');
        Route::get('/{id}/assets', 'PortfolioController@getPortfolioAssetsById');
        Route::put('/{id}', 'PortfolioController@updatePortfolioById');
        Route::delete('/{id}', 'PortfolioController@deletePortfolioById');

        Route::group([
            'prefix' => '/{portfolio_id}/transactions',
            'middleware' => 'token:access',
        ], function () {
            Route::post('/', 'TransactionController@createTransaction');
            Route::get('/', 'TransactionController@getTransactions');
            Route::put('/{transaction_id}', 'TransactionController@updateTransactionById');
            Route::delete('/{transaction_id}', 'TransactionController@deleteTransactionById');
        });
    });
});

