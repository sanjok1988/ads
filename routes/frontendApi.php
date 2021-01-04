<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'api/v1/',
    'namespace' => 'Sanjok\Blog\Api',
    'as' => 'site.'

], function ($router) {
    Route::get('news', 'FrontendApiController@index')->name('news.index');

    Route::get('news/popular', 'FrontendApiController@getPopularPosts')->name('news.popular');
    Route::get('news/{id}', 'FrontendApiController@show')->name('news.show');

    Route::get('category/{id}', 'FrontendApiController@getPostByCategory')->name('news.category');
    Route::get('topstories', 'FrontendApiController@getTopStories')->name('news.topstories');
});
