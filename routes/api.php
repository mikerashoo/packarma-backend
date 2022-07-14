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
    Route::post('/customer_app_version', 'CustomerVersionControlApiController@index');
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
        Route::post('/packaging_treatment/featured_listing', 'PackagingTreatmentApiController@featured_index');
        Route::post('/packaging_treatment/treatment_applicable_products', 'PackagingTreatmentApiController@applicable_products');

        //Subscription
        Route::post('/subscription/listing', 'SubscriptionApiController@index');
        Route::post('/subscription/buy_new_subscription', 'SubscriptionApiController@buy_subscription');
        Route::post('/subscription/my_subscription', 'SubscriptionApiController@my_subscription');

        //Subscription payment
        Route::post('/subscription_payment/new_payment', 'UserSubscriptionPaymentApiController@new_subscription_payment');
        Route::post('/subscription_payment/payment_success', 'UserSubscriptionPaymentApiController@subscription_payment_success');

        //Measurement Unit
        Route::post('/measurement_unit/listing', 'MeasurementUnitApiController@index');

        //Measurement Unit
        Route::post('/storage_condition/listing', 'StorageConditionApiController@index');

        //Packaging Machine
        Route::post('/packaging_machine/listing', 'PackagingMachineApiController@index');

        //Packaging Machine
        Route::post('/product_form/listing', 'ProductFormApiController@index');

        //Packing Type (packaging type)
        Route::post('/packaging_type/listing', 'PackingTypeApiController@index');

        //Packaging Solution
        Route::post('/packaging_solution/get_packaging_solution', 'PackagingSolutionApiController@index');

        //Customer enquiry
        Route::post('/customer_enquiry/my_place_enquiry', 'CustomerEnquiryApiController@store');
        Route::post('/customer_enquiry/my_enquiry_listing', 'CustomerEnquiryApiControlleindexr@');

        //Packaging Material
        Route::post('/packaging_material/listing', 'PackagingMaterialApiController@index');

        //City
        Route::post('/city/listing', 'CityApiController@index');

        //State
        Route::post('/state/listing', 'StateApiController@index');

        //Country
        Route::post('/country/listing', 'CountryApiController@index');

        //User Address
        Route::post('/user_address/my_listing', 'UserAddressApiController@index');
        Route::post('/user_address/add_my_address', 'UserAddressApiController@create');
        Route::post('/user_address/update_my_address', 'UserAddressApiController@update');
        Route::post('/user_address/delete_my_address', 'UserAddressApiController@destroy');

        //Change Password
        Route::post('/my_profile/change_password_api', 'ChangePasswordApiController@index');

        //Customer Enquiry Quote 
        Route::post('/customer_quote/my_listing', 'CustomerQuoteApiController@index');
        Route::post('/customer_quote/my_accept_quotation', 'CustomerQuoteApiController@accept_quotation');
        Route::post('/customer_quote/my_reject_quotation', 'CustomerQuoteApiController@reject_quotation');        
        Route::post('/customer_quote/my_accepted_quotation_details', 'CustomerQuoteApiController@accepted_quotation_details');        

        //Order
        Route::post('/order/my_order_listing', 'OrderApiController@index');
        Route::post('/order/my_selected_order_details', 'OrderApiController@show');
        Route::post('/order/my_completed_order_listing', 'OrderApiController@completed_orders');
        Route::post('/order/cancel_my_order', 'OrderApiController@cancel_order');
        Route::post('/order/enter_final_quantity', 'OrderApiController@final_quantity');
        Route::post('/order/create_new_order', 'OrderApiController@new_order');

        //Order payment
        Route::post('/order_payment/new_payment', 'OrderPaymentApiController@new_order_payment');
        Route::post('/order_payment/payment_success', 'OrderPaymentApiController@order_payment_success');

        //Feedback
        Route::post('/feedback/send_feedback', 'FeedbackApiController@store');

        //General Info
        Route::post('/customer_general_info', 'CustomerGeneralInfoApiController@index');

        //Update vendor quotation
        Route::post('/vendor_quotation/update_product_quantity', 'VendorQuotationApiController@update_quantity');

        //Get pincode data
        Route::post('/get_pincode_data', 'PincodeDetailApiController@index');

        //My profile
        Route::post('/user/my_profile', 'MyProfileApiController@show');
        Route::post('/user/update_my_profile', 'MyProfileApiController@update');
        Route::post('/user/delete_my_account', 'MyProfileApiController@destroy');

        //GST Details
        Route::post('/user/show_gst_details', 'GstDetailsApiController@show');
        Route::post('/user/store_gst_details', 'GstDetailsApiController@store');
    });
});
