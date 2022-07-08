<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\VendorPayment;
use App\Models\VendorQuotation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Response;

class HomeApiController extends Controller
{
    /**
     * Display a listing of the Homepage Data.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        try {
            $vendor_token = readVendorHeaderToken();
            if ($vendor_token) {
                $vendor_id = $vendor_token['sub'];

                $pending_payments = Order::where('vendor_id', $vendor_id)->sum('vendor_pending_payment');

                $received_today = VendorPayment::where([['vendor_id', $vendor_id], ['transaction_date', Carbon::now()->format('Y-m-d')]])->sum('amount');


                $last_no_of_days = 7;
                $date_from_last_no_of_days = Carbon::now()->subDays($last_no_of_days);

                // last 7 days completed orders
                $completed_orders =
                    Order::where('vendor_id', $vendor_id)->where(function ($query) use ($date_from_last_no_of_days) {
                        $query->where('order_delivery_status', 'delivered')
                            ->where('created_at', '>=', $date_from_last_no_of_days);
                    })->get()->count();

                // last 7 days pending orders
                $pending_orders =
                    Order::where('vendor_id', $vendor_id)->where(function ($query) use ($date_from_last_no_of_days) {
                        $query->where('order_delivery_status', 'pending')
                            ->where('created_at', '>=', $date_from_last_no_of_days);
                    })->get()->count();

                // last 7 days ongoing orders
                $ongoing_orders =
                    Order::where('vendor_id', $vendor_id)->where(function ($query) use ($date_from_last_no_of_days) {
                        $query->where('created_at', '>=', $date_from_last_no_of_days)
                            ->where('order_delivery_status', 'processing')
                            ->orwhere('order_delivery_status', 'out_for_delivery');
                    })->get()->count();


                // last 6 months payments
                $last_six_month_payment = VendorPayment::where('vendor_payments.vendor_id', $vendor_id)
                    ->whereBetween('transaction_date', [Carbon::now()->subMonth(6)->format('Y-m-d'), Carbon::now()->format('Y-m-d')])
                    ->selectRaw('sum(amount) as amount,
                           MONTH(transaction_date) as month,
                           DATE_FORMAT(transaction_date,"%b") as month_name,currency_symbol
                          ')
                    ->leftjoin('orders', 'vendor_payments.order_id', '=', 'orders.id')
                    ->leftjoin('currencies', 'orders.currency_id', '=', 'currencies.id')
                    ->groupBy('month', 'month_name')
                    ->get()->toArray();



                $last_three_enquiries = DB::table('vendor_quotations')->select(
                    // 'vendor_quotations.id',
                    // 'vendor_quotations.vendor_price',
                    // 'vendor_quotations.enquiry_status',
                    // 'vendor_quotations.created_at',
                    // 'customer_enquiries.description',
                    // 'customer_enquiries.enquiry_type',
                    // 'customer_enquiries.product_weight',
                    // 'customer_enquiries.product_quantity',
                    // 'customer_enquiries.shelf_life',
                    // 'customer_enquiries.address',
                    // 'products.product_name',
                    // 'products.product_description',
                    // 'measurement_units.unit_name',
                    // 'measurement_units.unit_symbol',
                    // 'cities.city_name',
                    // 'states.state_name',
                    'vendor_quotations.id',
                    'vendor_quotations.vendor_price',
                    'vendor_quotations.enquiry_status',
                    'vendor_quotations.created_at',
                    'customer_enquiries.description',
                    'customer_enquiries.enquiry_type',
                    'customer_enquiries.product_weight',
                    'customer_enquiries.product_quantity',
                    'customer_enquiries.shelf_life',
                    'customer_enquiries.address',
                    'measurement_units.unit_name',
                    'measurement_units.unit_symbol',
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
                    'products.product_name',
                    'products.product_description',
                    'categories.category_name',
                    'states.state_name',
                    'cities.city_name',
                )
                    // ->leftjoin('products', 'vendor_quotations.product_id', '=', 'products.id')
                    // ->leftjoin('customer_enquiries', 'vendor_quotations.customer_enquiry_id', '=', 'customer_enquiries.id')
                    // ->leftjoin('measurement_units', 'customer_enquiries.measurement_unit_id', '=', 'measurement_units.id')
                    // ->leftjoin('cities', 'customer_enquiries.city_id', '=', 'cities.id')
                    // ->leftjoin('states', 'customer_enquiries.state_id', '=', 'states.id')
                    ->leftjoin('products', 'vendor_quotations.product_id', '=', 'products.id')
                    ->leftjoin('customer_enquiries', 'vendor_quotations.customer_enquiry_id', '=', 'customer_enquiries.id')
                    ->leftjoin('categories', 'customer_enquiries.category_id', '=', 'categories.id')
                    ->leftjoin('measurement_units', 'customer_enquiries.measurement_unit_id', '=', 'measurement_units.id')
                    ->leftjoin('storage_conditions', 'customer_enquiries.storage_condition_id', '=', 'storage_conditions.id')
                    ->leftjoin('packaging_machines', 'customer_enquiries.packaging_machine_id', '=', 'packaging_machines.id')
                    ->leftjoin('product_forms', 'customer_enquiries.product_form_id', '=', 'product_forms.id')
                    ->leftjoin('packing_types', 'customer_enquiries.packing_type_id', '=', 'packing_types.id')
                    ->leftjoin('packaging_treatments', 'customer_enquiries.packaging_treatment_id', '=', 'packaging_treatments.id')
                    ->leftjoin('recommendation_engines', 'customer_enquiries.recommendation_engine_id', '=', 'recommendation_engines.id')
                    ->leftjoin('packaging_materials', 'customer_enquiries.packaging_material_id', '=', 'packaging_materials.id')
                    ->leftjoin('states', 'customer_enquiries.state_id', '=', 'states.id')
                    ->leftjoin('cities', 'customer_enquiries.city_id', '=', 'cities.id')
                    ->where([['vendor_quotations.vendor_id', $vendor_id], ['enquiry_status', 'mapped']])
                    ->orderBy('vendor_quotations.created_at', 'desc')->take(3)->get()->toArray();

                $i = 0;
                foreach ($last_three_enquiries as $row) {
                    $last_three_enquiries[$i]->unit_symbol = 'kg';
                    $i++;
                }

                $responseData['pending_payments'] = $pending_payments;
                $responseData['received_today'] = $received_today;
                $responseData['completed_orders'] = $completed_orders;
                $responseData['pending_orders'] = $pending_orders;
                $responseData['ongoing_orders'] = $ongoing_orders;
                $responseData['last_six_month_payment'] = $last_six_month_payment;
                $responseData['last_three_enquiries'] = $last_three_enquiries;
                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Home Page Data Fetch failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
