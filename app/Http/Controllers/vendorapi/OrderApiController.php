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
                    'orders.vendor_payment_status',
                    'orders.order_delivery_status',
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
                )
                    ->leftjoin('categories', 'orders.category_id', '=', 'categories.id')
                    ->leftjoin('sub_categories', 'orders.sub_category_id', '=', 'sub_categories.id')
                    ->leftjoin('products', 'orders.product_id', '=', 'products.id')
                    ->leftjoin('measurement_units', 'orders.measurement_unit_id', '=', 'measurement_units.id')
                    ->leftjoin('countries', 'orders.country_id', '=', 'countries.id')
                    ->leftjoin('currencies', 'orders.currency_id', '=', 'currencies.id')
                    ->where($main_table . '' . '.vendor_id', $vendor_id);





                // $data = Order::select('id', 'product_id', 'category_id', 'shelf_life', 'product_quantity', 'vendor_amount')->with(['product' => function ($query) {
                //     $query->select('id', 'product_name', 'product_description');
                // }])->with(['category' => function ($query) {
                //     $query->select('id', 'category_name');
                // }])->where('vendor_id', $vendor_id);

                $orderData = Order::whereRaw("1 = 1");

                if ($request->order_delivery_status) {
                    $orderData = $orderData->where($main_table . '' . '.order_delivery_status', $request->order_delivery_status);
                    $data = $data->where($main_table . '' . '.order_delivery_status', $request->order_delivery_status);
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
                    $from = Carbon::parse($new_from_data)->format('Y-m-d H:i:s');


                    $to_date = $request->to_date;
                    $old_to_date = explode('/', $to_date);
                    $new_to_data = $old_to_date[2] . '-' . $old_to_date[1] . '-' . $old_to_date[0];
                    $to = Carbon::parse($new_to_data)->format('Y-m-d H:i:s');


                    $orderData = $orderData->whereBetween($main_table . '' . '.created_at', [$from, $to]);
                    $data = $data->whereBetween($main_table . '' . '.created_at', [$from, $to]);
                } elseif ($request->from_date && !isset($request->to_date)) {
                    $from_date = $request->from_date;
                    $old_from_date = explode('/', $from_date);
                    $new_from_data = $old_from_date[2] . '-' . $old_from_date[1] . '-' . $old_from_date[0];
                    $from = Carbon::parse($new_from_data)->format('Y-m-d H:i:s');

                    $orderData = $orderData->whereDate($main_table . '' . '.created_at', '>=', $from);
                    $data = $data->whereDate($main_table . '' . '.created_at', '>=', $from);
                } elseif ($request->to_date && !isset($request->from_date)) {
                    $to_date = $request->to_date;
                    $old_to_date = explode('/', $to_date);
                    $new_to_data = $old_to_date[2] . '-' . $old_to_date[1] . '-' . $old_to_date[0];
                    $to = Carbon::parse($new_to_data)->format('Y-m-d H:i:s');
                    $orderData = $orderData->whereDate($main_table . '' . '.created_at', '<=', $to);
                    $data = $data->whereDate($main_table . '' . '.created_at', '<=', $to);
                }


                if (empty($orderData->first())) {
                    errorMessage(__('order.order_not_found'), $msg_data);
                }

                if ($request->id) {
                    $data = $data->where($main_table . '' . '.id', $request->id);
                }

                if (isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search, 'product_name|category_name');
                }

                $total_records = $data->get()->count();

                $data = $data->limit($limit)->offset($offset)->get()->toArray();

                // $i = 0;
                // foreach ($data as $row) {
                //     $data[$i]['product_image'] = getFile($row['product_image'], 'product');
                //     $data[$i]['product_thumb_image'] = getFile($row['product_thumb_image'], 'product', false, 'thumb');
                //     $i++;
                // }
                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;
                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Order fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
