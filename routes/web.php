<?php

use Illuminate\Support\Facades\Route;
use Sanjok\Blog\Http\Controllers\DashboardController;

Route::group(['prefix' => 'admin', 'namespace' => 'Sanjok\Blog\Http\Controllers', 'middleware' => ['web', 'auth']], function () {


    Route::group(['as' => 'dashboard.'], function () {
        Route::get('/', 'DashboardController@index')->name('index');
    });

    Route::group(['prefix' => 'team'], function () {
        Route::post('import/csv', 'TeamController@importCsv')->name('team.import.csv');
    });

    Route::resource('post', 'PostController');

    Route::group(['prefix' => 'post'], function () {
        Route::get('unlink/{id}', 'PostController@detachPost')->name('post.unlink');
    });

    Route::resource('page', 'PageController');

    Route::resource('category', 'CategoryController');

    Route::resource('user', 'UserController');

    Route::resource('slider', 'SliderController');

    Route::resource('team', 'TeamController');

    Route::resource('document', 'DocumentController');

    Route::get('filter', 'PostController@postFilter');

    Route::resource('ads', 'AdsController');
});

Route::group(['namespace' => 'Sanjok\Blog\Http\Controllers'], function () {
    Route::get('filter', 'PostController@filterPost');
});

Route::get('blog', function () {
    dd('blog package is ready to use');
});

Route::group(['prefix' => 'admin/partners', 'namespace' => 'Sanjok\Blog\Http\Controllers', 'middleware' => ['web', 'auth']], function () {
    Route::get('/', 'PartnersController@index')->name('partner.index');
    Route::get('create', 'PartnersController@create')->name('createPartners');
    Route::get('edit/{id}', 'PartnersController@edit')->name('editPartners');
    Route::get('view/{id}', 'PartnersController@show')->name('viewPartners');
    Route::post('update/{id}', 'PartnersController@update')->name('updatePartners');
    Route::post('create', 'PartnersController@store')->name('storePartners');
    Route::get('delete/{id}', 'PartnersController@destroy')->name('deletePartners');
});
