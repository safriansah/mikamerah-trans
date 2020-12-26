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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/transactions', 'API\TransactionsController@store');
Route::get('/transactions', 'API\TransactionsController@show');
Route::put('/transactions', 'API\TransactionsController@update');
Route::delete('/transactions', 'API\TransactionsController@destroy');