<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('user.signin');
});

Route::get('/home', function () {
    return view('admin.home');
});

Route::group(['prefix' => 'user'], function() {
    Route::get('/', function () {
        return view('user.index');
    });
    Route::get('/detail?id={id}', function () {
        return view('user.detail');
    });
});

Route::group(['prefix' => 'streetlighting'], function() {
    Route::get('/', function () {
        return view('streetlighting.index');
    });
    Route::get('/detail', function () {
        return view('streetlighting.detail');
    });
});

Route::get('/survey', function () {
    return view('survey.index');
});

Route::post('signin', 'Auth\AuthController@login');
Route::post('signout', 'Auth\AuthController@logout');
Route::group(['prefix' => '/json'], function() {
    Route::group(['prefix' => '/user'], function() {
        Route::post('/', 'Security\UserController@post');
        Route::get('/{id}', 'Security\UserController@view');
        Route::post('/search', 'Security\UserController@search');
    });
});