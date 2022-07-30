<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Response;

class OrderApiController extends Controller
{
    /**
     * Display a listing of the Orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        try {
            $vendor_token = readVendorHeaderToken();
            if ($vendor_token) {
                $vendor_id = $vendor_token['sub'];
                $page_no = 1;
                $limit = 10;
                $orderByArray = ['orders.updated_at' => 'DESC',];
                $defaultSortById = false;

                if (isset($request->page_no) && !empty($request->page_no)) {
                    $page_no = $request->page_no;
                }
                if (isset($request->limit) && !empty($request->limit)) {
                    $limit = $request->limit;
                }
                $offset = ($page_no - 1) * $limit;

                $main_table = 'orders';

                $data = DB::table('orders')->select(
                    'orders.id',
                    'orders.product_weight',
                    'orders.vendor_amount',
                    'orders.vendor_pending_payment',
                    'orders.customer_payment_status',
                    'orders.vendor_payment_status',
                    'orders.order_delivery_status',
                    'orders.product_quantity',
                    'orders.mrp',
                    'vendor_quotations.vendor_price',
                    'orders.gst_type',
                    'orders.gst_amount',
                    'orders.gst_percentage',
                    'orders.sub_total',
                    'orders.grand_total',
                    'orders.shipping_details',
                    'orders.billing_details',
                    'orders.shelf_life',
                    'orders.created_at',
                    'categories.category_name',
                    'sub_categories.sub_category_name',
                    'products.product_name',
                    'products.product_description',
                    'measurement_units.unit_name',
                    'measurement_units.unit_symbol',
                    'countries.country_name',
                    'countries.phone_code',
                    'currencies.currency_name',
                    'currencies.currency_symbol',
                    'storage_conditions.storage_condition_title',
                    'packaging_machines.packaging_machine_name',
                    'product_forms.product_form_name',
                    'packing_types.packing_name',
                    'packaging_treatments.packaging_treatment_name',
                    'recommendation_engines.engine_name',
                    'recommendation_engines.structure_type',
                    'recommendation_engines.min_shelf_life',
                    'recommendation_engines.max_shelf_life',
                    'packaging_materials.packaging_material_name',
                    'packaging_materials.material_description',
                    'customer_enquiries.address',
                    'states.state_name',
                    'customer_enquiries.city_name',
                )
                    ->leftjoin('categories', 'orders.category_id', '=', 'categories.id')
                    ->leftjoin('sub_categories', 'orders.sub_category_id', '=', 'sub_categories.id')
                    ->leftjoin('products', 'orders.product_id', '=', 'products.id')
                    ->leftjoin('measurement_units', 'orders.measurement_unit_id', '=', 'measurement_units.id')
                    ->leftjoin('countries', 'orders.country_id', '=', 'countries.id')
                    ->leftjoin('currencies', 'orders.currency_id', '=', 'currencies.id')
                    ->leftjoin('storage_conditions', 'orders.storage_condition_id', '=', 'storage_conditions.id')
                    ->leftjoin('packaging_machines', 'orders.packaging_machine_id', '=', 'packaging_machines.id')
                    ->leftjoin('product_forms', 'orders.product_form_id', '=', 'product_forms.id')
                    ->leftjoin('packing_types', 'orders.packing_type_id', '=', 'packing_types.id')
                    ->leftjoin('packaging_treatments', 'orders.packaging_treatment_id', '=', 'packaging_treatments.id')
                    ->leftjoin('recommendation_engines', 'orders.recommendation_engine_id', '=', 'recommendation_engines.id')
                    ->leftjoin('packaging_materials', 'orders.packaging_material_id', '=', 'packaging_materials.id')
                    ->leftjoin('customer_enquiries', 'orders.customer_enquiry_id', '=', 'customer_enquiries.id')
                    ->leftjoin('vendor_quotations', 'orders.vendor_quotation_id', '=', 'vendor_quotations.id')
                    ->leftjoin('states', 'customer_enquiries.state_id', '=', 'states.id')
                    ->leftjoin('cities', 'customer_enquiries.city_id', '=', 'cities.id')
                    ->where([[$main_table . '' . '.vendor_id', $vendor_id], [$main_table . '' . '.deleted_at', NULL]]);





                // $data = Order::select('id', 'product_id', 'category_id', 'shelf_life', 'product_quantity', 'vendor_amount')->with(['product' => function ($query) {
                //     $query->select('id', 'product_name', 'product_description');
                // }])->with(['category' => function ($query) {
                //     $query->select('id', 'category_name');
                // }])->where('vendor_id', $vendor_id);

                $orderData = Order::whereRaw("1 = 1");

                if ($request->order_delivery_status) {

                    if ($request->order_delivery_status == 'ongoing') {
                        $orderData = $orderData->whereIn($main_table . '' . '.order_delivery_status', ['processing', 'out_for_delivery']);
                        $data = $data->whereIn($main_table . '' . '.order_delivery_status', ['processing', 'out_for_delivery']);
                    } else {
                        $orderData = $orderData->where($main_table . '' . '.order_delivery_status', $request->order_delivery_status);
                        $data = $data->where($main_table . '' . '.order_delivery_status', $request->order_delivery_status);
                    }
                }

                if ($request->last_no_of_days && is_numeric($request->last_no_of_days)) {
                    $date_from_no_of_days = Carbon::now()->subDays($request->last_no_of_days);
                    $orderData = $orderData->whereDate($main_table . '' . '.created_at', '>=', $date_from_no_of_days);
                    $data = $data->whereDate($main_table . '' . '.created_at', '>=', $date_from_no_of_days);
                }

                if ($request->from_date && $request->to_date) {
                    $from_date = $request->from_date;
                    $old_from_date = explode('/', $from_date);
                    $new_from_data = $old_from_date[2] . '-' . $old_from_date[1] . '-' . $old_from_date[0];
                    $from = Carbon::parse($new_from_data)->format('Y-m-d 00:00:00');


                    $to_date = $request->to_date;
                    $old_to_date = explode('/', $to_date);
                    $new_to_data = $old_to_date[2] . '-' . $old_to_date[1] . '-' . $old_to_date[0];
                    $to = Carbon::parse($new_to_data)->format('Y-m-d 23:59:59');


                    // $orderData = $orderData->whereBetween($main_table . '' . '.created_at', [$from, $to]);
                    // $data = $data->whereBetween($main_table . '' . '.created_at', [$from, $to]);

                    $orderData = $orderData->whereDate($main_table . '' . '.created_at', '>=', $from)
                        ->whereDate($main_table . '' . '.created_at', '<=', $to);

                    $data = $data->whereDate($main_table . '' . '.created_at', '>=', $from)
                        ->whereDate($main_table . '' . '.created_at', '<=', $to);
                } elseif ($request->from_date && !isset($request->to_date)) {
                    $from_date = $request->from_date;
                    $old_from_date = explode('/', $from_date);
                    $new_from_data = $old_from_date[2] . '-' . $old_from_date[1] . '-' . $old_from_date[0];
                    $from = Carbon::parse($new_from_data)->format('Y-m-d 00:00:00');

                    $orderData = $orderData->whereDate($main_table . '' . '.created_at', '>=', $from);
                    $data = $data->whereDate($main_table . '' . '.created_at', '>=', $from);
                } elseif ($request->to_date && !isset($request->from_date)) {
                    $to_date = $request->to_date;
                    $old_to_date = explode('/', $to_date);
                    $new_to_data = $old_to_date[2] . '-' . $old_to_date[1] . '-' . $old_to_date[0];
                    $to = Carbon::parse($new_to_data)->format('Y-m-d 23:59:59');
                    $orderData = $orderData->whereDate($main_table . '' . '.created_at', '<=', $to);
                    $data = $data->whereDate($main_table . '' . '.created_at', '<=', $to);
                }


                // if (empty($orderData->first())) {
                //     errorMessage(__('order.order_not_found'), $msg_data);
                // }

                if ($request->id) {
                    $data = $data->where($main_table . '' . '.id', $request->id);
                }

                if (isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search, 'product_name|category_name');
                }

                if ($defaultSortById) {
                    $orderByArray = ['orders.id' => 'DESC'];
                }

                $data = allOrderBy($data, $orderByArray);

                $total_records = $data->get()->count();

                $data = $data->limit($limit)->offset($offset)->get()->toArray();


                $i = 0;
                foreach ($data as $row) {
                    $data[$i]->cgst_amount = "0.00";
                    $data[$i]->sgst_amount = "0.00";
                    $data[$i]->igst_amount = "0.00";
                    // $vendor_gst_amount = $row->vendor_amount * ($row->gst_percentage / 100);
                    $vendor_gst_amount = $row->gst_amount;

                    $data[$i]->odr_id = getFormatid($row->id, $main_table);
                    $data[$i]->shipping_details = json_decode($row->shipping_details, TRUE);
                    $data[$i]->billing_details = json_decode($row->billing_details, TRUE);
                    $data[$i]->material_unit_symbol = 'kg';
                    $data[$i]->order_status = $row->order_delivery_status;
                    $data[$i]->show_update_button = true;
                    // $data[$i]->gst_amount = number_format(($vendor_gst_amount), 2, '.', '');
                    // $data[$i]->grand_total = number_format(($row->vendor_amount + $vendor_gst_amount), 2, '.', '');
                    if ($row->order_delivery_status == 'pending' || $row->order_delivery_status == 'processing') {
                        $data[$i]->order_status = 'pending';
                    }

                    if ($row->order_delivery_status == 'out_for_delivery') {
                        $data[$i]->order_status = 'ongoing';
                    }

                    if ($row->order_delivery_status == 'delivered' || $row->order_delivery_status == 'cancelled') {
                        $data[$i]->show_update_button = false;
                    }

                    if ($row->customer_payment_status == 'fully_paid') {
                        $data[$i]->customer_payment_status = 'paid';
                    } else {
                        $data[$i]->customer_payment_status = 'pending';
                    }
                    if ($row->gst_type == 'cgst+sgst') {

                        $data[$i]->sgst_amount = $data[$i]->cgst_amount = number_format(($vendor_gst_amount / 2), 2, '.', '');
                    }

                    if ($row->gst_type == 'igst') {

                        $data[$i]->igst_amount = number_format(($vendor_gst_amount), 2, '.', '');
                    }



                    $i++;
                }


                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;


                // if (empty($data)) {
                //     errorMessage(__('order.order_not_found'), $responseData);
                // }

                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Order fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }



    public function updateDeliveryStatus(Request $request)
    {
        $msg_data = array();
        try {
            $vendor_token = readVendorHeaderToken();
            if ($vendor_token) {
                $vendor_id = $vendor_token['sub'];


                \Log::info("Order Delivery Status Update Started!");
                $order_data = array();
                if (!$request->id) {
                    errorMessage(__('order.id_require'), $msg_data);
                }

                if (!$request->order_delivery_status) {
                    errorMessage(__('order.delivery_status_require'), $msg_data);
                }
                $id = $request->id;
                $staus = $request->order_delivery_status;

                $status_array = ['pending', 'processing', 'out_for_delivery', 'delivered', 'cancelled'];
                $block_status_array = [];
                if (!in_array($staus, $status_array)) {
                    errorMessage(__('order.wrong_status'), $msg_data);
                }

                $checkOrder = Order::where([['id', $id], ['vendor_id', $vendor_id]])->first();
                if (empty($checkOrder)) {
                    errorMessage(__('order.order_not_found'), $msg_data);
                }

                $previousDeliveryStatus = $checkOrder->order_delivery_status;

                if ($previousDeliveryStatus == 'processing') {
                    $block_status_array = ['pending'];
                }

                if ($previousDeliveryStatus == 'out_for_delivery') {
                    $block_status_array = ['pending', 'processing'];
                }

                if ($previousDeliveryStatus == 'delivered') {
                    $block_status_array = ['pending', 'processing', 'out_for_delivery'];
                }

                if ($previousDeliveryStatus == 'cancelled') {
                    $block_status_array = ['pending', 'processing', 'out_for_delivery', 'delivered'];
                }

                if (in_array($staus, $block_status_array)) {
                    errorMessage(__('order.cant_revese_delivery_status'), $msg_data);
                }

                $order_data = $request->all();
                $order_data['vendor_id'] = $vendor_id;
                unset($order_data['id']);
                $checkOrder->update($order_data);
                $orderData = $checkOrder;

                $order = $orderData->toArray();
                $orderData->created_at->toDateTimeString();
                $orderData->updated_at->toDateTimeString();

                \Log::info("Order delivery status Updated successfully!");

                successMessage(__('order.updated'), $order);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Order delivery status Updation failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
