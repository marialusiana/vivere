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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', 'App\Http\Controllers\AuthController@login');
Route::post('register', 'App\Http\Controllers\AuthController@register');

Route::group(['middleware' => 'sentinel.permission:api'], function () {
    Route::get('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('voucher/store', 'App\Http\Controllers\VoucherController@store')->name('voucher-store');
Route::post('user/store', 'App\Http\Controllers\UserController@store')->name('user-store');

});