<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'api/v1/auth',
    'namespace' => 'Sanjok\Blog\Api'

], function ($router) {
    Route::post('login', 'JwtAuthController@login');
    Route::post('register', 'JwtAuthController@register');
    Route::post('logout', 'JwtAuthController@logout');
    Route::post('refresh', 'JwtAuthController@refresh');
    Route::get('user-profile', 'JwtAuthController@userProfile');
});




Route::middleware('jwt.verify')->group(function () {
    Route::group(['prefix' => 'api/v1/', 'namespace' => 'Sanjok\Blog\Api'], function () {
        Route::resource('posts', 'PostApiController')->only(['index', 'show', 'store', 'delete']);
        Route::group(['prefix' => 'posts'], function () {
            Route::post('{post_id}', 'PostApiController@update')->name('[api.posts.update');
            // Route::post('{post_id}', 'PostApiController@update');
            Route::post('toggle-status/{id}', 'PostApiController@toggleStatus');
            Route::get('limit/{limit}', 'PostApiController@getLimitedPosts')->name('api.posts.limit');
        });





        // Route::get('category/{category}', 'PostApiController@getPostsByCategory')->name('api.posts.category');
        Route::get('filter', 'PostApiController@filterPost')->name('api.posts.filter');

        Route::apiResource('users', 'UserApiController');



        Route::group(['prefix' => 'admin', 'as' => 'api.category.'], function () {
            Route::resource('category', 'CategoryApiController', ['only' => [
                'index', 'edit', 'show', 'store', 'update', 'destroy'
            ]]);
            Route::post('category/toggle-status/{id}', 'CategoryApiController@toggleStatus');
        });
        Route::group(['prefix' => 'ads', 'as' => 'api.ads.'], function () {
            Route::resource('container', 'AdsContainerApiController', ['only' => [
                'index'
            ]]);
        });
    });
});

// Route::group(['prefix' => 'api/v1/', 'namespace' => 'Sanjok\Blog\Api'], function () {
//     Route::group(['prefix' => 'ads', 'as' => 'api.ads.'], function () {
//         Route::resource('container', 'AdsContainerApiController', ['only' => [
//             'index'
//         ]]);
//     });
// });

// \DB::listen(function($query) {
//     var_dump($query->sql);
// });
