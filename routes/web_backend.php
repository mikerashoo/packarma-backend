<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// controllers

Route::get('/', 'LoginController@index');
Route::post('login', 'LoginController@login');
Route::group(['middleware'=>['customAuth']],function(){
	Route::get('dashboard', 'DashboardController@index');
	Route::get('dashboard/test', 'DashboardController@index_phpinfo');

	//profile
	Route::get('/profile', 'AdminController@profile');
	Route::post('/updateProfile', 'AdminController@updateProfile');

	//change password
	Route::get('/updatePassword', 'AdminController@updatePassword');
	Route::post('/resetPassword', 'AdminController@resetPassword');

	//General Settings
	Route::get('/generalSetting', 'AdminController@fetchSetting');
	Route::post('/updateSettingInfo', 'AdminController@updateSetting');
	Route::post('/publishEmailNotification', 'AdminController@updateEmailNotification');
	Route::post('/publishWhatsappNotification', 'AdminController@updateWhatsappNotification');
	Route::post('/publishSMSNotification', 'AdminController@updateSMSNotification');

	//ContactUs
	Route:: get('/contactus','ContactusController@index');
	Route::post('/contactus_data', 'ContactusController@fetch')->name('contactus_data');

	//Reviews
	Route::get('/review', 'ReviewController@index');
	Route::post('/review_data', 'ReviewController@fetch')->name('review_data');
	Route::get('reviewApproval/{id}','ReviewController@approval');
	Route::post('/updateReviewApproval', 'ReviewController@updateApprovalStatus');
	Route::post('/publishReview', 'ReviewController@updateStatus');
	Route::get('/review_view/{id}', 'ReviewController@view');
	
	//Roles
	Route::get('/roles', 'AdminController@roles');
	Route::post('/role_data', 'AdminController@roleData')->name('role_data');
	Route::get('/role_permission/{id}','AdminController@assignRolePermission');
	Route::post('/publishPermission', 'AdminController@publishPermission');

	//staff
	Route::get('/staff', 'StaffController@index');
	Route::post('/staff_data', 'StaffController@staffData')->name('staff_data');
	Route::get('/staff_add', 'StaffController@addStaff');
	Route::post('/saveStaff', 'StaffController@saveStaff');
	Route::get('/staff_edit/{id}','StaffController@editStaff');
	Route::post('/publishStaff', 'StaffController@publishStaff');
	Route::get('/staff_view/{id}','StaffController@view');

	//City
	Route::get('/city', 'CityController@index');
	Route::post('/city_data', 'CityController@fetch')->name('city_data');
	Route::get('/city_add', 'CityController@add');
	Route::post('/saveCity', 'CityController@saveFormData');
	Route::get('/city_edit/{id}','CityController@edit');
	Route::post('/publishCity', 'CityController@updateStatus');
	Route::get('/city_view/{id}','CityController@view');

	//Category
	Route::get('/category', 'CategoryController@index');
	Route::post('/category_data', 'CategoryController@fetch')->name('category_data');
	Route::get('/category_add', 'CategoryController@add');
	Route::post('/saveCategory', 'CategoryController@saveFormData');
	Route::get('/category_edit/{id}','CategoryController@edit');
	Route::post('/publishCategory', 'CategoryController@updateStatus');
	Route::get('/category_view/{id}','CategoryController@view');

	//Sub Category
	Route::get('/sub_category', 'SubCategoryController@index');
	Route::post('/sub_category_data', 'SubCategoryController@fetch')->name('sub_category_data');
	Route::get('/sub_category_add', 'SubCategoryController@add');
	Route::post('/saveSubCategory', 'SubCategoryController@saveFormData');
	Route::get('/sub_category_edit/{id}','SubCategoryController@edit');
	Route::post('/publishSubCategory', 'SubCategoryController@updateStatus');
	Route::get('/sub_category_view/{id}','SubCategoryController@view');

	//Company
	Route::get('/company', 'CompanyController@index');
	Route::post('/company_data', 'CompanyController@fetch')->name('company_data');
	Route::get('/company_add', 'CompanyController@add');
	Route::post('/saveCompany', 'CompanyController@saveFormData');
	Route::get('/company_edit/{id}','CompanyController@edit');
	Route::post('/publishCompany', 'CompanyController@updateStatus');
	Route::get('/company_view/{id}', 'CompanyController@view');

	//Country
	Route::get('/country', 'CountryController@index');
	Route::post('/country_data', 'CountryController@fetch')->name('country_data');
	Route::get('/country_add', 'CountryController@addCountry');
	Route::post('/saveCountry', 'CountryController@saveFormData');
	Route::get('/country_edit/{id}','CountryController@editCountry');
	Route::post('/publishCountry', 'CountryController@updateStatus');
	Route::get('/country_view/{id}','CountryController@view');

	//state
	Route::get('/state', 'StateController@index');
	Route::post('/state_data', 'StateController@fetch')->name('state_data');
	Route::get('/state_add', 'StateController@add');
	Route::post('/saveState', 'StateController@saveFormData');
	Route::get('/state_edit/{id}','StateController@edit');
	Route::post('/publishState', 'StateController@updateStatus');
	Route::get('/state_view/{id}','StateController@view');

	//Currency
	Route::get('/currency', 'CurrencyController@index');
	Route::post('/currency_data', 'CurrencyController@fetch')->name('currency_data');
	Route::get('/currency_add', 'CurrencyController@addCurrency');
	Route::post('/saveCurrency', 'CurrencyController@saveFormData');
	Route::get('/currency_edit/{id}','CurrencyController@editCurrency');
	Route::post('/publishCurrency', 'CurrencyController@updateStatus');
	Route::get('/currency_view/{id}','CurrencyController@view');

	//Subscription
	Route::get('/subscription', 'SubscriptionController@index');
	Route::post('/subscription_data', 'SubscriptionController@fetch')->name('subscription_data');
	Route::get('/subscriptionEdit/{id}','SubscriptionController@editSubscription');
	Route::post('/subscriptionUpdate', 'SubscriptionController@updateSubscription');

	//Order
	Route::get('/orders', 'OrderController@index');
	Route::post('/order_data', 'OrderController@fetch')->name('order_data');
	Route::get('/orderEdit/{id}','OrderController@editOrder');
	Route::post('/orderUpdate', 'OrderController@updateDeliveryStatus');
	Route::get('/orderPaymentUpdate/{id}','OrderController@updateOrderPayment');
	Route::post('/orderUpdatePayment', 'OrderController@updatePaymentStatus');
	Route::get('/order_view/{id}', 'OrderController@viewOrder');

	//CustomerSection:-
	//User approval List
	Route::get('/user_approval_list','UserController@indexApprovalList');
	Route::post('/user_approval_list_data', 'UserController@fetchUserApprovalList')->name('user_approval_list_data');
	Route::get('/user_approval_list_update/{id}','UserController@updateApproval');
	Route::post('/saveUserApprovalStatus', 'UserController@saveApprovalListFormData');
	Route::get('/user_approval_list_view/{id}', 'UserController@viewApprovalList');

	//User List
	Route:: get('/user_list','UserController@indexUserList');
	Route::post('/user_list_data', 'UserController@fetchUserList')->name('user_list_data');
	Route::get('/user_list_add', 'UserController@addUser');
	Route::get('/user_list_edit/{id}','UserController@editUserList');
	Route::post('/saveUserList', 'UserController@saveUserListFormData');
	Route::post('/publishUserList', 'UserController@updateStatus');
	Route::get('/user_list_view/{id}', 'UserController@viewUserList');
 
	//User Address
	Route:: get('/user_address_list','UserAddressController@index');
	Route::post('/user_address_data', 'UserAddressController@fetch')->name('user_address_data');
	Route::get('/add_user_address', 'UserAddressController@add');
	Route::post('/saveUserAddress', 'UserAddressController@saveFormData');
	Route::get('/user_address_edit/{id}','UserAddressController@edit');
	Route::get('/user_address_view/{id}', 'UserAddressController@view');
	Route::post('/publishUserAddress', 'UserAddressController@updateStatus');

	//Customer Enquiry
	Route::get('/customer_enquiry', 'CustomerEnquiryController@index');
	Route::post('/customer_enquiry_data', 'CustomerEnquiryController@fetch')->name('customer_enquiry_data');
	Route::get('/customer_enquiry_add', 'CustomerEnquiryController@addCustomerEnquiry');
	Route::post('/saveCustomerEnquiry', 'CustomerEnquiryController@saveCustomerEnquiryFormData');
	Route::get('/customer_enquiry_map_to_vendor/{id}', 'CustomerEnquiryController@customerEnquiryMapToVendor');
	Route::post('saveEnquiryMapToVendor', 'CustomerEnquiryController@saveFormDataVendor');
	Route::get('/customer_enquiry_view/{id}', 'CustomerEnquiryController@view');
	Route::post('/getVendorWarehouseDropdown', 'CustomerEnquiryController@getVendorWarehouse');

	//user subscription payment
	Route::get('/user_subscription_payment','UserSubscriptionPaymentController@index');
	Route::post('/user_subscription_data', 'UserSubscriptionPaymentController@fetch')->name('user_subscription_data');
	Route::get('/user_subscription_payment_view/{id}', 'UserSubscriptionPaymentController@view');

	//Banners
	Route::get('/banners', 'BannerController@index');
	Route::post('/banners_data', 'BannerController@fetch')->name('banners_data');
	Route::get('/banners_add', 'BannerController@add');
	Route::post('/saveBanners', 'BannerController@saveFormData');
	Route::get('/banners_edit/{id}','BannerController@edit');
	Route::post('/publishBanners', 'BannerController@updateStatus');
	Route::get('/banners_view/{id}','BannerController@view');

  	//Vendor
	Route::get('/vendor_list', 'VendorController@index');
	Route::post('/vendor_data', 'VendorController@fetch')->name('vendor_data');
	Route::get('/vendor_add', 'VendorController@add');
	Route::get('/vendor_edit/{id}','VendorController@edit');
	Route::post('/saveVendor', 'VendorController@saveFormData');
	Route::post('/publishVendor', 'VendorController@updateStatus');
	Route::post('/featuredVendor', 'VendorController@markFeatured');
	Route::get('/vendor_view/{id}', 'VendorController@view');


	//vendor approval List
	Route::get('/vendor_approval_list','VendorController@indexApprovalList');
	Route::post('/vendor_approval_list_data', 'VendorController@fetchVendorApprovalList')->name('vendor_approval_list_data');
	Route::get('/vendor_approval_list_update/{id}','VendorController@vendorApproval');
	Route::post('/saveVendorApprovalData', 'VendorController@saveVendorApprovalStatus');
	Route::get('/vendor_approval_list_view/{id}', 'VendorController@viewApprovalList');

	//Vendor material Mapping
	Route::get('/vendor_material_map', 'VendorMaterialController@index');
	Route::post('/vendor_material_map_data', 'VendorMaterialController@fetch')->name('vendor_material_map_data');
	Route::get('/vendor_material_map_add', 'VendorMaterialController@add');
	Route::post('/saveVendorMaterialMap', 'VendorMaterialController@saveFormData');
	Route::get('/vendor_material_map_edit/{id}','VendorMaterialController@edit');
	Route::get('/vendor_material_map_view/{id}', 'VendorMaterialController@view');
	Route::post('/publishVendorMaterialMap', 'VendorMaterialController@updateStatus');

	//Vendor Warehouse
	Route::get('/vendor_warehouse', 'VendorWarehouseController@index');
	Route::post('/vendor_warehouse_data', 'VendorWarehouseController@fetch')->name('vendor_warehouse_data');
	Route::get('/vendor_warehouse_add', 'VendorWarehouseController@add');
	Route::post('/saveVendorWarehouse', 'VendorWarehouseController@saveFormData');
	Route::get('/vendor_warehouse_edit/{id}','VendorWarehouseController@edit');
	Route::post('/publishVendorWarehouse', 'VendorWarehouseController@updateStatus');
	Route::get('/vendor_warehouse_view/{id}', 'VendorWarehouseController@view');

	//vendor payment
	Route::get('/vendor_payment_list', 'VendorPaymentController@index');
	Route::post('/vendor_payment_data', 'VendorPaymentController@fetch')->name('vendor_payment_data');
	Route::get('/vendor_payment_add','VendorPaymentController@add');
	Route::get('/vendor_payment_edit','VendorPaymentController@edit');
	Route::post('/saveVendorPaymentStatus', 'VendorPaymentController@saveVendorPaymentStatusData');
	Route::get('/vendor_payment_view/{id}', 'VendorPaymentController@view'); 
	Route::post('/getVendorOrderDropdown', 'VendorPaymentController@getVendorOrders');
	Route::post('/getOrderDetailsTableData', 'VendorPaymentController@getVendoPaymentDetails');

	//vendor quotation
	Route::get('/vendor_quotation_list','VendorQuotationController@index');
	Route::post('/vendor_quotation_data', 'VendorQuotationController@fetch')->name('vendor_quotation_data');
	Route::get('/vendor_quotation_view/{id}', 'VendorQuotationController@view');

	//Product Form
	Route::get('/product_form', 'ProductFormController@index');
	Route::post('/product_form_data', 'ProductFormController@fetch')->name('product_form_data');
	Route::get('/product_form_add', 'ProductFormController@add');
	Route::post('/saveProductForm', 'ProductFormController@saveFormData');
	Route::get('/product_form_edit/{id}','ProductFormController@edit');
	Route::post('/publishProductForm', 'ProductFormController@updateStatus');
	Route::get('/product_form_view/{id}', 'ProductFormController@view');

	//Packing Types
	Route::get('/packing_type', 'PackingTypeController@index');
	Route::post('/packing_type_data', 'PackingTypeController@fetch')->name('packing_type_data');
	Route::get('/packing_type_add', 'PackingTypeController@add');
	Route::post('/savePackingType', 'PackingTypeController@saveFormData');
	Route::get('/packing_type_edit/{id}','PackingTypeController@edit');
	Route::post('/publishPackingType', 'PackingTypeController@updateStatus');
	Route::get('/packing_type_view/{id}', 'PackingTypeController@view');

	//Packaging Machine
	Route::get('/packaging_machine', 'PackagingMachineController@index');
	Route::post('/packaging_machine_data', 'PackagingMachineController@fetch')->name('packaging_machine_data');
	Route::get('/packaging_machine_add', 'PackagingMachineController@add');
	Route::post('/savePackagingMachine', 'PackagingMachineController@saveFormData');
	Route::get('/packaging_machine_edit/{id}','PackagingMachineController@edit');
	Route::post('/publishPackagingMachine', 'PackagingMachineController@updateStatus');
	Route::get('/packaging_machine_view/{id}', 'PackagingMachineController@view');

	//Packaging Treatment
	Route::get('/packaging_treatment', 'PackagingTreatmentController@index');
	Route::post('/packaging_treatment_data', 'PackagingTreatmentController@fetch')->name('packaging_treatment_data');
	Route::get('/packaging_treatment_add', 'PackagingTreatmentController@add');
	Route::post('/savePackagingTreatment', 'PackagingTreatmentController@saveFormData');
	Route::get('packaging_treatment_edit/{id}','PackagingTreatmentController@edit');
	Route::post('/publishPackagingTreatment', 'PackagingTreatmentController@updateStatus');
	Route::get('packaging_treatment_view/{id}', 'PackagingTreatmentController@view');

	//Product
	Route::get('/product', 'ProductController@index');
	Route::post('/product_data', 'ProductController@fetch')->name('product_data');
	Route::get('/product_add', 'ProductController@add');
	Route::post('/saveProduct', 'ProductController@saveFormData');
	Route::get('/product_edit/{id}','ProductController@edit');
	Route::post('/publishProduct', 'ProductController@updateStatus');
	Route::get('/product_view/{id}', 'ProductController@view');

	//Packaging material
	Route::get('/packaging_material', 'PackagingMaterialController@index');
	Route::post('/packaging_material_data', 'PackagingMaterialController@fetch')->name('packaging_material_data');
	Route::get('/packaging_material_add', 'PackagingMaterialController@add');
	Route::post('/savePackagingMaterial', 'PackagingMaterialController@saveFormData');
	Route::get('/packaging_material_edit/{id}','PackagingMaterialController@edit');
	Route::post('/publishPackagingMaterial', 'PackagingMaterialController@updateStatus');
	Route::get('/packaging_material_view/{id}', 'PackagingMaterialController@view');

	//recommendation engine
	Route::get('/recommendation_engine', 'RecommendationEngineController@index');
	Route::post('/recommendation_engine_data', 'RecommendationEngineController@fetch')->name('recommendation_engine_data');
	Route::get('/recommendation_engine_add', 'RecommendationEngineController@add');
	Route::post('/saveRecommendationEngine', 'RecommendationEngineController@saveFormData');
	Route::get('/recommendation_engine_edit/{id}','RecommendationEngineController@edit');
	Route::post('/publishRecommendationEngine', 'RecommendationEngineController@updateStatus');
	Route::get('/recommendation_engine_view/{id}', 'RecommendationEngineController@view');

	//Order list
	Route::get('/order_list', 'OrderController@index');
	Route::post('/order_data', 'OrderController@fetch')->name('order_data');
	Route::get('order_delivery_update/{id}','OrderController@updateOrderDelivery');
	Route::post('/saveOrderDeliveryStatus', 'OrderController@updateDeliveryStatusData');
	Route::get('/order_payment_update/{id}','OrderController@updateOrderPayment');
	Route::post('/saveOrderPaymentStatus', 'OrderController@updatePaymentStatusData');
	Route::get('/order_view/{id}', 'OrderController@viewOrder');

	//order payment
	Route::get('/order_payment_list','OrderPaymentController@index');
	Route::post('/order_payment_data', 'OrderPaymentController@fetch')->name('order_payment_data');
	Route::get('/order_payment_view/{id}', 'OrderPaymentController@view');

	//storage condition
	Route::get('/storage_condition', 'StorageConditionController@index');
	Route::post('/storage_condition_data', 'StorageConditionController@fetch')->name('storage_condition_data');
	Route::get('/storage_condition_add', 'StorageConditionController@add');
	Route::post('/saveStorageCondition', 'StorageConditionController@saveFormData');
	Route::get('/storage_condition_edit/{id}','StorageConditionController@edit');
	Route::post('/publishStorageCondition', 'StorageConditionController@updateStatus');
	Route::get('/storage_condition_view/{id}','StorageConditionController@view');

	//measurement unit
	Route::get('/measurement_unit', 'MeasurementUnitController@index');
	Route::post('/measurement_unit_data', 'MeasurementUnitController@fetch')->name('measurement_unit_data');
	Route::get('/measurement_unit_add', 'MeasurementUnitController@add');
	Route::post('/saveMeasurementUnit', 'MeasurementUnitController@saveFormData');
	Route::get('/measurement_unit_edit/{id}','MeasurementUnitController@edit');
	Route::post('/publishMeasurementUnit', 'MeasurementUnitController@updateStatus');
	Route::get('/measurement_unit_view/{id}','MeasurementUnitController@view');

	//message sms
	Route::get('/sms', 'MessageSmsController@index');
	Route::post('/sms_data', 'MessageSmsController@fetch')->name('sms_data');
	Route::post('/saveSms', 'MessageSmsController@saveFormData');
	Route::get('/sms_edit/{id}','MessageSmsController@edit');
	Route::post('/publishSmsStatus', 'MessageSmsController@updateStatus');
	Route::get('/sms_view/{id}','MessageSmsController@view');

	//message whatsapp
	Route::get('/whatsapp', 'MessageWhatsappController@index');
	Route::post('/whatsapp_data', 'MessageWhatsappController@fetch')->name('whatsapp_data');
	Route::post('/saveWhatsapp', 'MessageWhatsappController@saveFormData');
	Route::get('/whatsapp_edit/{id}','MessageWhatsappController@edit');
	Route::post('/publishWhatsappStatus', 'MessageWhatsappController@updateStatus');
	Route::get('/whatsapp_view/{id}','MessageWhatsappController@view');

	//message notification
	Route::get('/notification', 'MessageNotificationController@index');
	Route::post('/notification_data', 'MessageNotificationController@fetch')->name('notification_data');
	Route::get('/notification_add','MessageNotificationController@add');
	Route::get('/notification_edit/{id}','MessageNotificationController@edit');
	Route::post('/saveNotification', 'MessageNotificationController@saveFormData');
	Route::post('/publishNotificationStatus', 'MessageNotificationController@updateStatus');
	Route::get('/notification_view/{id}','MessageNotificationController@view');

	//message email
	Route::get('/email', 'MessageEmailController@index');
	Route::post('/email_data', 'MessageEmailController@fetch')->name('email_data');
	Route::get('/email_edit/{id}','MessageEmailController@edit');
	Route::post('/saveEmail', 'MessageEmailController@saveFormData');
	Route::post('/publishEmailStatus', 'MessageEmailController@updateStatus');
	Route::get('/email_view/{id}','MessageEmailController@view');

});

// routes
Route::get('/logout', function () {
    session()->forget('data');
    return redirect('/webadmin');
});
