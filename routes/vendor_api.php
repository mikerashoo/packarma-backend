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
    Route::post('/general_info_all', 'GeneralInfoController@generalInforAll');
    Route::middleware(['vendorTokenAuth'])->group(function () {
        Route::post('/country/listing', 'CountryController@index');
        Route::post('/state/listing', 'StateController@index');
        Route::post('/city/listing', 'CityController@index');
        Route::post('/materials/listing', 'PackagingMaterialApiController@index');
        Route::post('/material/price_update', 'PackagingMaterialApiController@updatePrice');
        Route::post('/orders/listing', 'OrderApiController@index');
        Route::post('/order/update_delivery_status', 'OrderApiController@updateDeliveryStatus');
        Route::post('/enquiry/listing', 'EnquiryApiController@index');
        Route::post('/enquiry/send_quotation', 'EnquiryApiController@sendQuotation');
        Route::post('/quotation/listing', 'QuotationApiController@index');
        Route::post('/payment/listing', 'PaymentApiController@index');
        Route::post('/home', 'HomeApiController@index');
        Route::post('/change_password', 'ChangePasswordController@index');
        Route::post('/general_info', 'GeneralInfoController@index');
        Route::post('/profile', 'MyProfileController@show');
        Route::post('/update_profile', 'MyProfileController@update');
        Route::post('/delete_account', 'MyProfileController@destroy');
        Route::post('/get_pincode_data', 'PincodeDetailController@index');
        Route::post('/address/listing', 'MyAddressController@index');
        Route::post('/address/create', 'MyAddressController@create');
        Route::post('/address/update', 'MyAddressController@update');
        Route::post('/address/delete', 'MyAddressController@destroy');
        Route::post('/show_gst_detils', 'GstDetailsController@show');
        Route::post('/store_gst_details', 'GstDetailsController@store');
    });
});
