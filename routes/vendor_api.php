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

Route::middleware(['vendorbasicAuth'])->group(function () {
    Route::post('/version', 'VersionApiController@index');
    Route::post('/register_api', 'RegisterApiController@index');
    Route::post('/request_otp', 'OtpApiController@requestOtp');
    Route::post('/verify_otp', 'OtpApiController@verifyOtp');
    Route::post('/login_api', 'LoginApiController@index');
    Route::post('/forgot_password_api', 'ForgotPasswordApiController@index');
    Route::middleware(['vendorTokenAuth'])->group(function () {
        Route::post('/materials/listing', 'PackagingMaterialApiController@index');
        Route::post('/orders/listing', 'OrderApiController@index');
        Route::post('/enquiry/listing', 'EnquiryApiController@index');
        Route::post('/quotation/listing', 'QuotationApiController@index');
        Route::post('/payment/listing', 'PaymentApiController@index');
        Route::post('/home', 'HomeApiController@index');
        Route::post('/change_password', 'ChangePasswordController@index');
        Route::post('/general_info', 'GeneralInfoController@index');
    });
});
