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
    Route::post('/forgot_password_api', 'ForgotPasswordApiController@index');
    Route::middleware(['tokenAuth'])->group(function () {
        //Product
    	Route::post('/products/listing', 'ProductApiController@index');
    	// Route::post('/products/store', 'ProductApiController@store');
    	// Route::post('/products/update', 'ProductApiController@update');
    	// Route::post('/products/destroy', 'ProductApiController@destroy');

        //Category
    	Route::post('/category/listing', 'CategoryApiController@index');

        //Sub Category
        Route::post('/sub_category/listing', 'SubCategoryApiController@index');

        //Sub Category
        Route::post('/sub_category/listing', 'SubCategoryApiController@index');

        //Banner
        Route::post('/banner/listing', 'BannerApiController@index');

        //Packaging Treatment
        Route::post('/packaging_treatment/listing', 'PackagingTreatmentApiController@index');

        //Subscription
        Route::post('/subscription/listing', 'SubscriptionApiController@index');

        //Measurement Unit
        Route::post('/measurement_unit/listing', 'MeasurementUnitApiController@index');

        //Measurement Unit
        Route::post('/storage_condition/listing', 'StorageConditionApiController@index');

        //Packaging Machine
        Route::post('/packaging_machine/listing', 'PackagingMachineApiController@index');

        //Packaging Machine
        Route::post('/product_form/listing', 'ProductFormApiController@index');

        //Customer enquiry
        // Route::post('/customer_enquiry/store', 'CustomerEnquiryApiController@index');
    });
});
