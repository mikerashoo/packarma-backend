<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
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
        try
        {
            $token = readHeaderToken();
            if($token)
            {
                $user_id = $token['sub'];
                $page_no=1;
                $limit=10;
                
                if(isset($request->page_no) && !empty($request->page_no)) {
                    $page_no=$request->page_no;
                }
                if(isset($request->limit) && !empty($request->limit)) {
                    $limit=$request->limit;
                }
                $offset=($page_no-1)*$limit;

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
                    'orders.created_at'
                )
                    ->leftjoin('packaging_materials', 'packaging_materials.id', '=', 'orders.packaging_material_id')
                    ->leftjoin('measurement_units', 'measurement_units.id', '=', 'orders.measurement_unit_id')
                    ->where('orders.user_id', $user_id)->whereIn('orders.order_delivery_status', ['pending', 'processing','out_for_delivery']);

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
                if(empty($orderData->first()))
                {
                    errorMessage(__('order.order_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'order_payment_status|vendor_payment_status|order_delivery_status');
                }
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;
                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            }
            else
            {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        }
        catch(\Exception $e)
        {
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
        try
        {
            $token = readHeaderToken();
            if($token)
            {
                $user_id = $token['sub'];
                $page_no=1;
                $limit=10;
                
                if(isset($request->page_no) && !empty($request->page_no)) {
                    $page_no=$request->page_no;
                }
                if(isset($request->limit) && !empty($request->limit)) {
                    $limit=$request->limit;
                }
                $offset=($page_no-1)*$limit;

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
                    'orders.created_at'
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
                if(empty($orderData->first()))
                {
                    errorMessage(__('order.order_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'order_payment_status|vendor_payment_status|order_delivery_status');
                }
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;
                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            }
            else
            {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        }
        catch(\Exception $e)
        {
            \Log::error("Order List fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 27-05-2022
     * Uses : Display details of the selected order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $msg_data = array();
        try
        {
            $token = readHeaderToken();
            if($token)
            {
                //Request Validation
                $validationErrors = $this->validateShowOrder($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage(__('auth.validation_failed'), $validationErrors->all());
                }
                $user_id = $token['sub'];

                $data = DB::table('orders')->select(
                    'orders.id',
                    // 'customer_enquiries.description',
                    'orders.grand_total',
                    'recommendation_engines.engine_name',
                    'recommendation_engines.structure_type',
                    'orders.customer_payment_status',
                    'orders.order_delivery_status',
                    'orders.created_at',
                    'categories.category_name',
                    'sub_categories.sub_category_name',
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
                    'orders.mrp',
                    'orders.gst_amount',
                    'orders.grand_total',
                    'states.state_name',
                    'cities.city_name',
                    'customer_enquiries.address',
                    'customer_enquiries.pincode'

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
                    ->leftjoin('states', 'states.id', '=', 'customer_enquiries.state_id')
                    ->leftjoin('cities', 'cities.id', '=', 'customer_enquiries.city_id')
                    ->where([['orders.user_id', $user_id],['orders.id',$request->order_id]]);

                
                $data = $data->get()->toArray();
                $responseData['result'] = $data;
                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            }
            else
            {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        }
        catch(\Exception $e)
        {
            \Log::error("Show Selected Order Details fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created on : 25/05/2022
     * Uses : Validate showing specific order request for Customer Enquiry.
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
}
