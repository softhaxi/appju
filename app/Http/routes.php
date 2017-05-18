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

// View Routing
Route::get('/', function () {
    return view('user.signin');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', function () {
        return view('admin.home');
    });

    Route::group(['prefix' => 'user'], function() {
        Route::get('/', function () {
            return view('user.index');
        });
        Route::get('/{id}', function ($id) {
            return view('user.detail')
                ->with('id', $id);
        });
        Route::get('/edit/{id}', function ($id) {
            return view('user.edit')
                ->with('id', $id);
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
});

// Web Routing
Route::post('signin', 'Auth\AuthController@login');
Route::group(['middleware' => 'auth'], function () {
    Route::post('signout', 'Auth\AuthController@logout');
});

// JSON Routing
Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => '/json'], function() {
        Route::group(['prefix' => '/user'], function() {
            Route::post('/', 'Security\UserController@post');
            Route::post('/search', 'Security\UserController@find');
            Route::get('/{id}', 'Security\UserController@view');
        });
    });
});