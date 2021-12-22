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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login')->name('login');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('merchant', 'Api\MerchantController');
    Route::resource('outlet', 'Api\OutletController');
    Route::resource('transaction', 'Api\TransactionController');

    Route::group(['prefix' => 'list'], function () {
        Route::get('outlet', 'Api\OutletController@getList')->name('outlet.list');
        Route::get('merchant', 'Api\MerchantController@getList')->name('merchant.list');
        // Route::get('transaction', 'Api\TransactionController@getList')->name('transaction.list');
    });
});