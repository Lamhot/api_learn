<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::get('/', function() {
    return auth()->user();
});

Route::post('auth/login', ['uses' => 'Auth\AuthController@login']);

Route::group(['middleware' => ['web']], function () {
    //
});

Route::get('/', function() {

    if (auth()->check()) {
        return auth()->user();
    }

    abort(403, "You're not authorized to access this page.");
});

Route::get('api', function() {

    $auth = auth()->guard('api'); // Switch guard ke "api" driver

    if ($auth->check()) {
        return $auth->user();
    };

    abort(403, "You're not authorized to access this public REST API.");
});

$options = [
    'prefix' => 'api',
    'namespace' => 'Api',
    'middleware' => 'auth.api',
];

Route::group($options, function() {

    Route::get('/', 'UserController@index');

    Route::get('{user_id}', 'UserController@show');

});