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
                $last_six_month_payment = VendorPayment::where('vendor_id', $vendor_id)
                    ->whereBetween('transaction_date', [Carbon::now()->subMonth(6)->format('Y-m-d'), Carbon::now()->format('Y-m-d')])
                    ->selectRaw('sum(amount) as amount,
                           MONTH(transaction_date) as month,
                           DATE_FORMAT(transaction_date,"%b") as month_name
                          ')
                    ->groupBy('month', 'month_name')
                    ->get()->toArray();



                $last_three_enquiries = DB::table('vendor_quotations')->select(
                    'vendor_quotations.id',
                    'vendor_quotations.vendor_price',
                    'vendor_quotations.enquiry_status',
                    'vendor_quotations.created_at',
                    'customer_enquiries.description',
                    'customer_enquiries.enquiry_type',
                    'customer_enquiries.product_weight',
                    'customer_enquiries.product_quantity',
                    'customer_enquiries.shelf_life',
                    'products.product_name',
                    'products.product_description',
                    'cities.city_name',
                    'states.state_name',
                )
                    ->leftjoin('products', 'vendor_quotations.product_id', '=', 'products.id')
                    ->leftjoin('customer_enquiries', 'vendor_quotations.customer_enquiry_id', '=', 'customer_enquiries.id')
                    ->leftjoin('user_addresses', 'customer_enquiries.user_id', '=', 'user_addresses.user_id')
                    ->leftjoin('cities', 'user_addresses.city_id', '=', 'cities.id')
                    ->leftjoin('states', 'user_addresses.state_id', '=', 'states.id')
                    ->where([['vendor_quotations.vendor_id', $vendor_id], ['enquiry_status', 'mapped'], ['vendor_quotations.deleted_at', NULL]])
                    ->orderBy('vendor_quotations.created_at', 'desc')->take(3)->get()->toArray();


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
            \Log::error("Payment fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
