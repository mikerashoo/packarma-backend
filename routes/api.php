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

Route::middleware(['basicAuth'])->group(function () {
    Route::post('/register_api', 'RegisterApiController@index');
    Route::post('/request_otp', 'OtpApiController@requestOtp');
    Route::post('/verify_otp', 'OtpApiController@verifyOtp');
    Route::post('/login_api', 'LoginApiController@index');
    Route::middleware(['tokenAuth'])->group(function () {
    	Route::post('/products/listing', 'ProductApiController@index');
    	// Route::post('/products/store', 'ProductApiController@store');
    	// Route::post('/products/update', 'ProductApiController@update');
    	// Route::post('/products/destroy', 'ProductApiController@destroy');
    });
});
