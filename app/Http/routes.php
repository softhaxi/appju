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
    if(Auth::check()) {
        return view('admin.home');
    } else {
        return view('user.signin');
    }
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', function () {
        return view('admin.home');
    });

    Route::group(['prefix' => '/user'], function() {
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
    
    Route::group(['prefix' => '/streetlighting'], function() {
        Route::get('/', function () {
            return view('streetlighting.index');
        });
        Route::get('/edit/{id}', function ($id) {
            return view('streetlighting.edit')
                ->with('id', $id);
        });
        Route::get('/location/{id}', function ($id) {
            return view('streetlighting.location')
                ->with('id', $id);
        });
        Route::get('/unregistered', function () {
            return view('streetlighting.unregistered.index');
        });
        Route::get('/{id}', function ($id) {
            return view('streetlighting.detail')
                ->with('id', $id);
        });
    });

    Route::group(['prefix' => 'survey'], function() {
        Route::get('/', function () {
            return view('survey.index');
        });
    });
    
    Route::group(['prefix' => 'report'], function() {
        Route::get('/', function () {
            return view('report.index');
        });
        Route::post('/streetlightinglocation', 'Report\StreetLightingLocationController@download');
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
            Route::post('/delete', 'Security\UserController@delete');
            Route::post('/reset', 'Security\UserController@reset');
            Route::post('/search', 'Security\UserController@search');
            Route::post('/status', 'Security\UserController@status');
            Route::post('/update', 'Security\UserController@put');
            Route::get('/{id}', 'Security\UserController@view');
        });

        Route::group(['prefix' => '/streetlighting'], function() {
            Route::post('/', 'Master\StreetLightingController@post');
            Route::post('/delete', 'Master\StreetLightingController@delete');
            Route::post('/import', 'Master\StreetLightingController@import');
            Route::post('/location', 'Master\StreetLightingController@location');
            Route::get('/location/{id}', 'Master\StreetLightingController@locationView');
            Route::get('/search', 'Master\StreetLightingController@search');
            Route::post('/search', 'Master\StreetLightingController@search');
            Route::post('/status', 'Master\StreetLightingController@status');
            Route::post('/update', 'Master\StreetLightingController@put');
            Route::post('/unregistered/search', 'Master\StreetLightingController@unregistered');
            Route::get('/unregistered/search', 'Master\StreetLightingController@unregistered');
            Route::get('/{id}', 'Master\StreetLightingController@view');
        });

        Route::group(['prefix' => '/survey'], function() {
            Route::post('/search', 'Detail\SurveyController@search');
            Route::post('/streetlighting/search', 'Survey\StreetLightingSurveyController@search');
            Route::post('/streetlighting/lamp/search', 'Survey\StreetLightingSurveyController@search');
            Route::get('/{id}', 'Detail\SurveyController@view');
        });
    });
});

// API Routing
Route::group(['prefix' => '/api'], function() {
    Route::group(['prefix' => '/user'], function() {
        Route::post('/auth', 'Security\UserController@auth');
        Route::post('/changepassword', 'Security\UserController@changePassword');
    });

    Route::group(['prefix' => '/streetlighting'], function() {
        Route::get('/', 'Master\StreetLightingController@index');
    });

    Route::group(['prefix' => '/survey'], function() {
        Route::group(['prefix' => '/streetlighting'], function() {
            Route::post('/', 'Survey\StreetLightingSurveyController@post');
            Route::post('/lamp', 'Survey\StreetLightingSurveyController@postLamp');    
        });
    });
});