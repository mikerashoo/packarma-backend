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

class PaymentApiController extends Controller
{
    /**
     * Display a listing of the Payments.
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
                    'orders.product_quantity',
                    'orders.mrp',
                    'orders.vendor_amount',
                    'orders.vendor_pending_payment',
                    'orders.vendor_payment_status',
                    'orders.order_delivery_status',
                    'orders.created_at',
                    'customer_enquiries.address',
                    'states.state_name',
                    'customer_enquiries.city_name',
                )

                    ->leftjoin('customer_enquiries', 'orders.customer_enquiry_id', '=', 'customer_enquiries.id')
                    ->leftjoin('states', 'customer_enquiries.state_id', '=', 'states.id')
                    ->leftjoin('cities', 'customer_enquiries.city_id', '=', 'cities.id')
                    ->where([['vendor_id', $vendor_id]]);

                // vendor payment list
                // $data = VendorPayment::select('id', 'order_id', 'order_id', 'payment_mode', 'amount', 'remark', 'transaction_date')
                //     ->with(['order' => function ($query) {
                //         $query->select('id', 'product_weight', 'product_quantity', 'mrp', 'vendor_amount', 'vendor_pending_payment', 'vendor_payment_status', 'order_delivery_status');
                //     }])->where([['vendor_id', $vendor_id], ['payment_status', $status]]);

                // $data = Order::select('id', 'product_weight', 'product_quantity', 'mrp', 'vendor_amount', 'vendor_pending_payment', 'vendor_payment_status', 'order_delivery_status', 'created_at')
                //     ->where([['vendor_id', $vendor_id], ['vendor_payment_status', $status]]);



                $awaiting_payments = Order::where('vendor_id', $vendor_id)->sum('vendor_pending_payment');
                $grand_total = Order::where('vendor_id', $vendor_id)->sum('grand_total');
                $awaiting_orders =
                    VendorQuotation::where('vendor_id', $vendor_id)->where(function ($query) {
                        $query->where('enquiry_status', '=', 'quoted')
                            ->orWhere('enquiry_status', '=', 'viewed')
                            ->orWhere('enquiry_status', '=', 'requote');
                    })->get()->count();

                $payments_received = Order::selectRaw('SUM(grand_total - vendor_pending_payment) as payments_received')
                    ->where('vendor_id', $vendor_id);



                $paymentData = Order::whereRaw("1 = 1");


                if ($request->last_no_of_days && is_numeric($request->last_no_of_days)) {
                    $date_from_no_of_days = Carbon::now()->subDays($request->last_no_of_days);
                    $paymentData = $paymentData->whereDate($main_table . '' . '.created_at', '>=', $date_from_no_of_days);
                    $data = $data->whereDate($main_table . '' . '.created_at', '>=', $date_from_no_of_days);
                    $payments_received = $payments_received->whereDate($main_table . '' . '.created_at', '>=', $date_from_no_of_days);
                }
                if ($request->payment_status) {
                    $paymentData = $paymentData->where($main_table . '' . '.vendor_payment_status', $request->payment_status);
                    $data = $data->where($main_table . '' . '.vendor_payment_status', $request->payment_status);
                }

                $payments_received = $payments_received->first();

                if (empty($payments_received->payments_received)) {
                    $payments_received->payments_received = "0.00";
                }

                // if (empty($paymentData->first())) {
                //     errorMessage(__('payment.payment_not_found'), $msg_data);
                // }

                if ($request->id) {
                    $data = $data->where('id', $request->id);
                }

                if (isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search, 'vendor_pending_payment|vendor_amount');
                }

                if ($defaultSortById) {
                    $orderByArray = ['orders.id' => 'DESC'];
                }

                $data = allOrderBy($data, $orderByArray);

                $total_records = $data->get()->count();

                $data = $data->limit($limit)->offset($offset)->get()->toArray();

                $i = 0;
                foreach ($data as $row) {
                    $data[$i]->odr_id = getFormatid($row->id, $main_table);
                    $i++;
                }


                $responseData['result'] = $data;
                $responseData['awaiting_payments'] = $awaiting_payments;
                $responseData['payments_received'] = $payments_received->payments_received;
                // $responseData['payments_received'] = $grand_total - $awaiting_payments;
                $responseData['awaiting_orders'] = $awaiting_orders;
                $responseData['total_records'] = $total_records;

                if (empty($data)) {
                    errorMessage(__('payment.payment_not_found'), $responseData);
                }


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
