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

Route::get('/users', 'App\Http\Controllers\UserController@index');
Route::get('/tree', 'App\Http\Controllers\UserController@tree');
Route::get('/indications', 'App\Http\Controllers\UserController@indications');
Route::get('/allowed_indicate', 'App\Http\Controllers\UserController@allowed_indicate');
Route::get('/user/{id}', 'App\Http\Controllers\UserController@show');
Route::post('/user', 'App\Http\Controllers\UserController@store');
Route::put('/user/{id}', 'App\Http\Controllers\UserController@update');
