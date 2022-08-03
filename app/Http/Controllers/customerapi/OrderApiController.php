<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OrderPayment;
use App\Models\CustomerEnquiry;
use App\Models\Order;
use App\Models\UserAddress;
use App\Models\VendorQuotation;
use App\Models\User;
use App\Models\State;
use App\Models\Review;
use App\Models\Country;
use App\Models\Currency;
use Response;

class OrderApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 24-05-2022
     * Uses : Display a listing of the orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];
                $page_no = 1;
                $limit = 10;
                $orderByArray = ['orders.id' => 'DESC',];
                $defaultSortByName = false;
                if (isset($request->page_no) && !empty($request->page_no)) {
                    $page_no = $request->page_no;
                }
                if (isset($request->limit) && !empty($request->limit)) {
                    $limit = $request->limit;
                }
                $offset = ($page_no - 1) * $limit;

                $data = DB::table('orders')->select(
                    'orders.id',
                    'orders.grand_total',
                    'packaging_materials.packaging_material_name',
                    'packaging_materials.material_description',
                    'orders.customer_payment_status',
                    'orders.order_delivery_status',
                    'orders.product_quantity',
                    'measurement_units.unit_symbol',
                    'orders.created_at',
                    'orders.gst_type',
                    'orders.gst_amount',
                    'orders.gst_percentage'
                )
                    ->leftjoin('packaging_materials', 'packaging_materials.id', '=', 'orders.packaging_material_id')
                    ->leftjoin('measurement_units', 'measurement_units.id', '=', 'orders.measurement_unit_id')
                    ->where('orders.user_id', $user_id)->whereIn('orders.order_delivery_status', ['pending', 'processing', 'out_for_delivery']);

                $orderData = Order::whereRaw("1 = 1");
                if ($request->order_id) {
                    $orderData = $orderData->where('orders' . '' . '.id', $request->order_id);
                    $data = $data->where('orders' . '' . '.id', $request->order_id);
                }
                if ($request->category_id) {
                    $orderData = $orderData->where('orders' . '' . '.category_id', $request->category_id);
                    $data = $data->where('orders' . '' . '.category_id', $request->category_id);
                }
                if ($request->sub_category_id) {
                    $orderData = $orderData->where('orders' . '' . '.sub_category_id', $request->sub_category_id);
                    $data = $data->where('orders' . '' . '.sub_category_id', $request->sub_category_id);
                }
                if ($request->product_id) {
                    $orderData = $orderData->where('orders' . '' . '.product_id', $request->product_id);
                    $data = $data->where('orders' . '' . '.product_id', $request->product_id);
                }
                if (empty($orderData->first())) {
                    errorMessage(__('order.order_not_found'), $msg_data);
                }
                if (isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search, 'orders.order_payment_status|orders.vendor_payment_status|orders.order_delivery_status');
                }
                if ($defaultSortByName) {
                    $orderByArray = ['products.product_name' => 'ASC'];
                }
                $data = allOrderBy($data, $orderByArray);
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                if (empty($data)) {
                    errorMessage(__('order.order_not_found'), $msg_data);
                }
                $i = 0;
                foreach ($data as $row) {
                    $data[$i]->cgst_amount = "0.00";
                    $data[$i]->sgst_amount = "0.00";
                    $data[$i]->igst_amount = "0.00";
                    $payNowButton = false;

                    if ($row->gst_type == 'cgst+sgst') {
                        $data[$i]->sgst_amount = $data[$i]->cgst_amount = number_format(($row->gst_amount / 2), 2, '.', '');
                        $data[$i]->gst_percentage = number_format(($row->gst_percentage / 2), 2, '.', '');
                    }
                    if ($row->gst_type == 'igst') {
                        $data[$i]->igst_amount = $row->gst_amount;
                    }
                    $data[$i]->odr_id = getFormatid($row->id, 'orders');

                    if ($row->customer_payment_status == 'pending') {
                        $payNowButton = true;
                    }
                    $data[$i]->pay_now =  $payNowButton;

                    // if(!empty($row->shipping_details)) {
                    //     $data[$i]->shipping_details = json_decode($row->shipping_details,true);
                    // } else {
                    //     $data[$i]->shipping_details = null;
                    // }
                    // if(!empty($row->billing_details)) {
                    //     $data[$i]->billing_details = json_decode($row->billing_details,true);
                    // } else {
                    //     $data[$i]->billing_details = null;
                    // }
                    $i++;
                }
                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;
                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Order List fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 27-05-2022
     * Uses : Display a listing of the completed orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function completed_orders(Request $request)
    {
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];
                $page_no = 1;
                $limit = 10;
                $orderByArray = ['orders.updated_at' => 'DESC'];
                $defaultSortByName = false;
                if (isset($request->page_no) && !empty($request->page_no)) {
                    $page_no = $request->page_no;
                }
                if (isset($request->limit) && !empty($request->limit)) {
                    $limit = $request->limit;
                }
                $offset = ($page_no - 1) * $limit;

                $data = DB::table('orders')->select(
                    'orders.id',
                    // 'customer_enquiries.description',
                    'orders.grand_total',
                    'packaging_materials.packaging_material_name',
                    'packaging_materials.material_description',
                    'orders.customer_payment_status',
                    'orders.order_delivery_status',
                    'orders.product_quantity',
                    'measurement_units.unit_symbol',
                    'orders.created_at',
                    'orders.gst_type',
                    'orders.gst_amount',
                    'orders.gst_percentage'
                )
                    ->leftjoin('packaging_materials', 'packaging_materials.id', '=', 'orders.packaging_material_id')
                    ->leftjoin('measurement_units', 'measurement_units.id', '=', 'orders.measurement_unit_id')
                    ->where('orders.user_id', $user_id)->whereIn('orders.order_delivery_status', ['delivered', 'cancelled']);

                $orderData = Order::whereRaw("1 = 1");
                if ($request->order_id) {
                    $orderData = $orderData->where('orders' . '' . '.id', $request->order_id);
                    $data = $data->where('orders' . '' . '.id', $request->order_id);
                }
                if ($request->category_id) {
                    $orderData = $orderData->where('orders' . '' . '.category_id', $request->category_id);
                    $data = $data->where('orders' . '' . '.category_id', $request->category_id);
                }
                if ($request->sub_category_id) {
                    $orderData = $orderData->where('orders' . '' . '.sub_category_id', $request->sub_category_id);
                    $data = $data->where('orders' . '' . '.sub_category_id', $request->sub_category_id);
                }
                if ($request->product_id) {
                    $orderData = $orderData->where('orders' . '' . '.product_id', $request->product_id);
                    $data = $data->where('orders' . '' . '.product_id', $request->product_id);
                }
                if (empty($orderData->first())) {
                    errorMessage(__('order.order_not_found'), $msg_data);
                }
                if (isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search, 'order_payment_status|vendor_payment_status|order_delivery_status');
                }
                if ($defaultSortByName) {
                    $orderByArray = ['products.product_name' => 'ASC'];
                }
                $data = allOrderBy($data, $orderByArray);
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                $i = 0;
                foreach ($data as $row) {
                    $data[$i]->cgst_amount = "0.00";
                    $data[$i]->sgst_amount = "0.00";
                    $data[$i]->igst_amount = "0.00";

                    if ($row->gst_type == 'cgst+sgst') {
                        $data[$i]->sgst_amount = $data[$i]->cgst_amount = number_format(($row->gst_amount / 2), 2, '.', '');
                        $data[$i]->gst_percentage = number_format(($row->gst_percentage / 2), 2, '.', '');
                    }
                    if ($row->gst_type == 'igst') {
                        $data[$i]->igst_amount = $row->gst_amount;
                    }
                    $data[$i]->odr_id = getFormatid($row->id, 'orders');




                    $i++;
                }

                if (empty($data)) {
                    errorMessage(__('order.order_not_found'), $msg_data);
                }
                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;
                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Order List fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 27-05-2022
     * Uses : Display details of the selected order details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                //Request Validation
                $validationErrors = $this->validateShowOrder($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage($validationErrors->all(), $validationErrors->all());
                }
                $user_id = $token['sub'];
                $data = DB::table('orders')->select(
                    'orders.id',
                    'orders.recommendation_engine_id',
                    'recommendation_engines.engine_name',
                    'recommendation_engines.structure_type',
                    'orders.customer_payment_status',
                    'orders.order_delivery_status',
                    'orders.created_at',
                    'categories.category_name',
                    'sub_categories.sub_category_name',
                    'orders.product_id',
                    'products.product_name',
                    'customer_enquiries.product_weight',
                    'customer_enquiries.shelf_life',
                    'storage_conditions.storage_condition_title',
                    'packaging_machines.packaging_machine_name',
                    'product_forms.product_form_name',
                    'packing_types.packing_name',
                    'packaging_treatments.packaging_treatment_name',
                    'vendors.vendor_name',
                    'vendor_warehouses.warehouse_name',
                    'orders.product_quantity',
                    'measurement_units.unit_symbol',
                    'orders.shipping_details',
                    'orders.billing_details',
                    'orders.mrp',
                    'orders.gst_type',
                    'orders.gst_amount',
                    'orders.gst_percentage',
                    'orders.sub_total',
                    'orders.grand_total',
                    'currencies.currency_symbol',
                )
                    ->leftjoin('customer_enquiries', 'customer_enquiries.id', '=', 'orders.customer_enquiry_id')
                    ->leftjoin('recommendation_engines', 'recommendation_engines.id', '=', 'orders.recommendation_engine_id')
                    ->leftjoin('categories', 'categories.id', '=', 'orders.category_id')
                    ->leftjoin('sub_categories', 'sub_categories.id', '=', 'orders.sub_category_id')
                    ->leftjoin('products', 'products.id', '=', 'orders.product_id')
                    ->leftjoin('storage_conditions', 'storage_conditions.id', '=', 'orders.storage_condition_id')
                    ->leftjoin('packaging_machines', 'packaging_machines.id', '=', 'orders.packaging_machine_id')
                    ->leftjoin('product_forms', 'product_forms.id', '=', 'orders.product_form_id')
                    ->leftjoin('packing_types', 'packing_types.id', '=', 'orders.packing_type_id')
                    ->leftjoin('packaging_treatments', 'packaging_treatments.id', '=', 'orders.packaging_treatment_id')
                    ->leftjoin('vendors', 'vendors.id', '=', 'orders.vendor_id')
                    ->leftjoin('vendor_warehouses', 'vendor_warehouses.id', '=', 'orders.vendor_warehouse_id')
                    ->leftjoin('measurement_units', 'measurement_units.id', '=', 'orders.measurement_unit_id')
                    ->leftjoin('currencies', 'currencies.id', '=', 'orders.currency_id')
                    ->leftjoin('states', 'states.id', '=', 'customer_enquiries.state_id')
                    ->leftjoin('cities', 'cities.id', '=', 'customer_enquiries.city_id')
                    ->where([['orders.user_id', $user_id], ['orders.id', $request->order_id]]);

                $data = $data->get()->toArray();
                $reviewData = Review::where('order_id', $request->order_id)->get()->count();
                $i = 0;
                foreach ($data as $row) {
                    $data[$i]->cgst_amount = "0.00";
                    $data[$i]->sgst_amount = "0.00";
                    $data[$i]->igst_amount = "0.00";
                    $payNowButton = false;

                    if ($row->gst_type == 'cgst+sgst') {
                        $data[$i]->sgst_amount = $data[$i]->cgst_amount = number_format(($row->gst_amount / 2), 2, '.', '');
                        $data[$i]->gst_percentage = number_format(($row->gst_percentage / 2), 2, '.', '');
                    }
                    if ($row->gst_type == 'igst') {
                        $data[$i]->igst_amount = $row->gst_amount;
                    }
                    $data[$i]->order_id = getFormatid($row->id, 'orders');
                    $data[$i]->show_feedback_button = false;
                    $data[$i]->show_cancel_button = false;
                    if ($row->order_delivery_status == 'delivered' && $reviewData == 0) {
                        $data[$i]->show_feedback_button = true;
                    }
                    if ($row->order_delivery_status == 'pending') {
                        $data[$i]->show_cancel_button = true;
                    }
                    if (!empty($row->billing_details)) {
                        $data[$i]->shipping_details = json_decode($row->shipping_details, true);
                        $data[$i]->billing_details = json_decode($row->billing_details, true);
                    } else {
                        $data[$i]->shipping_details = null;
                        $data[$i]->billing_details = null;
                    }

                    if ($row->customer_payment_status == 'pending') {
                        $payNowButton = true;
                    }
                    $data[$i]->pay_now =  $payNowButton;


                    $i++;
                }
                $responseData['result'] = $data;
                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Show Selected Order Details fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 30-05-2022
     * Uses : Update cancelled order data in order table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cancel_order(Request $request)
    {
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];
                // Request Validation
                $validationErrors = $this->validateCancelOrder($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage($validationErrors->all(), $validationErrors->all());
                }
                $statusData = Order::where('id', $request->order_id)->first();
                if ($statusData->order_delivery_status == "delivered") {
                    errorMessage(__('order.order_already_delivered'), $msg_data);
                }
                if ($statusData->order_delivery_status == "cancelled") {
                    errorMessage(__('order.order_already_cancelled'), $msg_data);
                }
                if ($request->order_delivery_status == "cancelled") {
                    $order_details = Order::find($request->order_id);
                    $orderStatusData = Order::find($request->order_id)->update($request->all());
                    \Log::info("Order Cancelled Successfully");
                    successMessage(__('order.order_cancelled_successfully'), $msg_data);
                }
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Quotation Reject failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created on : 25/05/2022
     * Uses : Validate showing specific order request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateShowOrder(Request $request)
    {
        return \Validator::make($request->all(), [
            'order_id' => 'required|integer'
        ])->errors();
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 02/06/2022
     * Uses : To get product quantity, calculate the amountband return value to customer.
     * 
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function final_quantity(Request $request)
    {
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];

                $validationErrors = $this->validateFinalQuantityRequest($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage($validationErrors->all(), $validationErrors->all());
                }
                \Log::info("Taking Product Quantity and amount calculation started");
                //storing values in variable from request
                $customer_enquiry_id = $request->customer_enquiry_id;
                $vendor_quotation_id = $request->vendor_quotation_id;
                $product_quantity = $request->product_quantity;

                //fetching data of vendor quotation by vendor quotation id
                // $vendor_quotation_data = VendorQuotation::where([['id', $vendor_quotation_id], ['customer_enquiry_id', $customer_enquiry_id], ['user_id', $user_id]])->first();
                $vendor_quotation_data = VendorQuotation::where([['id', $vendor_quotation_id]])->first();

                //storing values in variable from vendor quotation table
                $mrp_rate_price = $vendor_quotation_data->mrp;
                $freight_amount_price = $vendor_quotation_data->freight_amount;
                $gst_percentage = $vendor_quotation_data->gst_percentage;

                //calculate sub total amount and store in variable
                $sub_total_price = $product_quantity * $mrp_rate_price;



                //calculate gst amount
                if ($gst_percentage != 0.00) {
                    $gst_amount_price = $sub_total_price * ($gst_percentage / 100.00);
                } else {
                    $gst_amount_price = 0.00;
                }


                //calculate total amount
                $total_amount_price = $sub_total_price + $gst_amount_price + $freight_amount_price;


                //create an array and store all value
                $quantity_calculation_data =  array();
                //gst amount show
                $quantity_calculation_data['cgst_amount'] = "0.00";
                $quantity_calculation_data['sgst_amount'] = "0.00";
                $quantity_calculation_data['igst_amount'] = "0.00";
                if ($vendor_quotation_data->gst_type == 'cgst+sgst') {
                    $quantity_calculation_data['cgst_amount'] = $quantity_calculation_data['sgst_amount'] = number_format(($gst_amount_price / 2), 2, '.', '');
                }
                if ($vendor_quotation_data->gst_type == 'igst') {
                    $quantity_calculation_data['igst_amount'] = $gst_amount_price;
                }
                // print_r($quantity_calculation_data);
                // die;
                $currency_symbol = Currency::where('id', $vendor_quotation_data->currency_id)->pluck('currency_symbol')->first();

                $quantity_calculation_data['quantity'] = $product_quantity;
                $quantity_calculation_data['rate'] = $mrp_rate_price;
                $quantity_calculation_data['gst_amount'] = $gst_amount_price;
                $quantity_calculation_data['gst_percentage'] = $gst_percentage;
                $quantity_calculation_data['sub_total'] = $sub_total_price;
                $quantity_calculation_data['total_amount'] = $total_amount_price;
                $quantity_calculation_data['currency_symbol'] = $currency_symbol;

                successMessage(__('order.billing_details_calculated_successfully'), $quantity_calculation_data);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("My get quantity and Billing details calculation failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }


    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 31/05/2022
     * Uses : To create new order and store.
     * 
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function new_order(Request $request)
    {
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];
                $user_data = User::find($user_id);

                $validationErrors = $this->validateNewOrder($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage($validationErrors->all(), $validationErrors->all());
                }
                \Log::info("My order creation started!");
                //storing customer_enquiry_id and vendor_quotation_id in variable from request
                $customer_enquiry_id = $request->customer_enquiry_id;
                $vendor_quotation_id = $request->vendor_quotation_id;
                $product_quantity = $request->product_quantity;

                //fetching data of vendor quotation by vendor quotation id
                $vendor_quotation_data = VendorQuotation::where('id', $vendor_quotation_id)->first();

                //storing values in variable from vendor quotation table
                $sub_total_price = $vendor_quotation_data->sub_total;
                $mrp_rate_price = $vendor_quotation_data->mrp;
                $gst_amount_price = $vendor_quotation_data->gst_amount;
                $freight_amount_price = $vendor_quotation_data->freight_amount;

                $commission_price = $vendor_quotation_data->commission_amt;
                $vendor_amount_price = $vendor_quotation_data->vendor_price;
                $vendor_warehouse_id = $vendor_quotation_data->vendor_warehouse_id;
                $gst_type = $vendor_quotation_data->gst_type;
                $gst_percentage = $vendor_quotation_data->gst_percentage;

                //calculate sub total amount and store in variable
                $sub_total_price = $product_quantity * $mrp_rate_price;

                //calculate gst amount
                if ($gst_percentage != 0) {
                    $gst_amount_price = $sub_total_price * $gst_percentage / 100;
                } else {
                    $gst_amount_price = 0;
                }

                //calculate total amount
                $total_amount_price = $sub_total_price + $gst_amount_price + $freight_amount_price;

                //calculate commision and vendor price
                $commission = $commission_price * $product_quantity;
                $vendor_amount = $vendor_amount_price * $product_quantity;

                //adding additional data in request order
                $order_request_data = $request->all();
                $order_request_data['user_id'] = $user_id;
                $order_request_data['product_quantity'] = $product_quantity;
                $order_request_data['mrp'] = $mrp_rate_price;
                $order_request_data['sub_total'] = $sub_total_price;
                $order_request_data['gst_amount'] = $gst_amount_price;
                $order_request_data['gst_type'] = $gst_type;
                $order_request_data['gst_percentage'] = $gst_percentage;
                $order_request_data['freight_amount'] = $freight_amount_price;
                $order_request_data['grand_total'] = $total_amount_price;
                $order_request_data['commission'] = $commission;
                $order_request_data['vendor_amount'] = $vendor_amount;
                $order_request_data['customer_pending_payment'] = $total_amount_price;
                $order_request_data['vendor_pending_payment'] = $vendor_amount;
                $order_request_data['created_by'] = $user_id;
                $order_request_data['currency_id'] = $vendor_quotation_data->currency_id;

                //checking address checkbox
                if ($request->same_address_checkbox == 'yes') {
                    $billing_address_data = UserAddress::find($request->user_billing_address_id);
                    $shipping_address_data = $billing_address_data;

                    $billing_state_data = State::find($shipping_address_data->state_id);
                    $billing_country_data = Country::find($shipping_address_data->country_id);
                    $shipping_state_data = State::find($shipping_address_data->state_id);
                    $shipping_country_data = Country::find($shipping_address_data->country_id);
                    // print_r($shipping_country_data->phone_code);exit;
                } else {
                    $billing_address_data = UserAddress::find($request->user_billing_address_id);
                    $shipping_address_data = UserAddress::find($request->user_shipping_address_id);
                    $billing_state_data = State::find($billing_address_data->state_id);
                    $billing_country_data = Country::find($billing_address_data->country_id);
                    $shipping_state_data = State::find($shipping_address_data->state_id);
                    $shipping_country_data = Country::find($shipping_address_data->country_id);
                }
                //store order details in json array
                $order_detail = array(
                    'user_name' => $user_data->name,
                    'product_quantity' => $product_quantity,
                    'mrp' => $mrp_rate_price,
                    'sub_total' => $sub_total_price,
                    'gst_amount' => $gst_amount_price,
                    'gst_type' => $gst_type,
                    'gst_percentage' => $gst_percentage,
                    'freight_amount' => $freight_amount_price,
                    'grand_total' => $total_amount_price,
                    'commission' => $commission,
                    'vendor_amount' => $vendor_amount,
                    'customer_pending_payment' => $total_amount_price,
                    'vendor_pending_payment' => 0,
                );
                //store product details in json array
                $product_detail = array(
                    "category_id" => $request->category_id,
                    "sub_category_id" => $request->sub_category_id,
                    "product_id" => $request->product_id,
                    "shelf_life" => $request->shelf_life,
                    "product_weight" => $request->product_weight,
                    "measurement_unit_id" => $request->measurement_unit_id,
                    "product_quantity" => $request->product_weight,
                    "storage_condition_id" => $request->storage_condition_id,
                    "packaging_machine_id" => $request->packaging_machine_id,
                    "product_form_id" => $request->product_form_id,
                    "packing_type_id" => $request->packing_type_id,
                    "packaging_treatment_id" => $request->packaging_treatment_id,
                    "recommendation_engine_id" => $request->recommendation_engine_id,
                    "packaging_material_id" => $request->packaging_material_id,
                );
                //store shipping details in json array
                // print_r($shipping_country_data->phone_code);exit;
                $shipping_detail = array(
                    "user_address_id" => $shipping_address_data->id,
                    "user_name" => $user_data->name,
                    "address_name" => $shipping_address_data->address_name,
                    "type" => $shipping_address_data->type,
                    "phone_code" => $shipping_country_data->phone_code,
                    "mobile_no" => $shipping_address_data->mobile_no,
                    "country_name" => $shipping_country_data->country_name,
                    "state_name" => $shipping_state_data->state_name,
                    "city_name" => $shipping_address_data->city_name,
                    "flat" => $shipping_address_data->flat,
                    "area" => $shipping_address_data->area,
                    "land_mark" => $shipping_address_data->land_mark,
                    "pincode" => $shipping_address_data->pincode
                );
                //store billing details in json array
                $billing_detail = array(
                    "user_address_id" => $billing_address_data->id,
                    "user_name" => $user_data->name,
                    "address_name" => $billing_address_data->address_name,
                    "type" => $billing_address_data->type,
                    "phone_code" => $billing_country_data->phone_code,
                    "mobile_no" => $billing_address_data->mobile_no,
                    "country_name" => $billing_country_data->country_name,
                    "state_name" => $billing_state_data->state_name,
                    "city_name" => $billing_address_data->city_name,
                    "flat" => $billing_address_data->flat,
                    "area" => $billing_address_data->area,
                    "land_mark" => $billing_address_data->land_mark,
                    "pincode" => $billing_address_data->pincode,
                    "gstin" => $billing_address_data->gstin
                );
                //json array in json columns
                $order_request_data['order_details'] = json_encode($order_detail);
                $order_request_data['product_details'] = json_encode($product_detail);
                $order_request_data['shipping_details'] = json_encode($shipping_detail);
                $order_request_data['billing_details'] = json_encode($billing_detail);
                // return $order_request_data;
                $newOrderData = Order::create($order_request_data);
                CustomerEnquiry::where('id', $request->customer_enquiry_id)->update(['quote_type' => 'order']);
                $newOrderData->odr_id = getFormatid($newOrderData->id, 'orders');
                \Log::info("My new order created successfully!");
                $myOrderData = $newOrderData->toArray();
                $newOrderData->created_at->toDateTimeString();
                $newOrderData->updated_at->toDateTimeString();
                successMessage(__('order.my_order_created_successfully'), $myOrderData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("My Order Creation failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 31/05/2022
     * Uses : To validate order creation request
     * 
     * Validate request for registeration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateNewOrder(Request $request)
    {
        return \Validator::make($request->all(), [
            'vendor_quotation_id' => 'required|numeric',
            'vendor_id' => 'required|numeric',
            'vendor_warehouse_id' => 'required|numeric',
            'customer_enquiry_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'sub_category_id' => 'required|numeric',
            'product_id' => 'required|numeric',
            'shelf_life' => 'required|int|digits_between:1,3',
            'product_weight' => 'required|numeric',
            'measurement_unit_id' => 'required|numeric',
            'product_quantity' => 'required|numeric',
            'storage_condition_id' => 'required|numeric',
            'packaging_machine_id' => 'required|numeric',
            'product_form_id' => 'required|numeric',
            'packing_type_id' => 'required|numeric',
            'packaging_treatment_id' => 'required|numeric',
            'recommendation_engine_id' => 'required|numeric',
            'packaging_material_id' => 'required|numeric',
            'same_address_checkbox' => 'required|in:yes,no',
            'user_billing_address_id' => 'required|numeric',
            'user_shipping_address_id' => 'required_if:same_address_checkbox,==,no',
        ])->errors();
    }

    /**
     * 
     * Created By : Pradyumn Dwivedi
     * Created at : 31/05/2022
     * Uses : To validate final quantity request
     * 
     * Validate request for registeration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateFinalQuantityRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            // 'customer_enquiry_id' => 'required|numeric',
            'vendor_quotation_id' => 'required|numeric',
            'product_quantity' => 'required|int|digits_between:1,4'
        ])->errors();
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created on : 30/05/2022
     * Uses : Validate cancel order request for order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateCancelOrder(Request $request)
    {
        return \Validator::make($request->all(), [
            'order_id' => 'required|integer',
            'order_delivery_status' => 'required|string'
        ])->errors();
    }
}
