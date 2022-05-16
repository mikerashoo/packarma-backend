<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\VendorPayment;
use App\Models\VendorQuotation;
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

                if (isset($request->page_no) && !empty($request->page_no)) {
                    $page_no = $request->page_no;
                }
                if (isset($request->limit) && !empty($request->limit)) {
                    $limit = $request->limit;
                }
                $offset = ($page_no - 1) * $limit;
                // vendor payment list
                $data = VendorPayment::with('order')->where([['vendor_id', $vendor_id], ['payment_status', $request->payment_status]]);
                $awaiting_payments = Order::where('vendor_id', $vendor_id)->sum('vendor_pending_payment');
                $grand_total = Order::where('vendor_id', $vendor_id)->sum('grand_total');
                $awaiting_orders =
                    VendorQuotation::where('vendor_id', $vendor_id)->where(function ($query) {
                        $query->where('enquiry_status', '=', 'quoted')
                            ->orWhere('enquiry_status', '=', 'viewed')
                            ->orWhere('enquiry_status', '=', 'requote');
                    })->get()->count();

                $payments_received = Order::selectRaw('SUM(grand_total - vendor_pending_payment) as payments_received')
                    ->where('vendor_id', $vendor_id)
                    ->first();



                $paymentData = VendorPayment::whereRaw("1 = 1");

                // if ($request->payment_status) {
                //     $paymentData = $paymentData->where('payment_status', $request->payment_status);
                //     $data = $data->where('payment_status', $request->payment_status);
                // }


                if (empty($paymentData->first())) {
                    errorMessage(__('payment.payment_not_found'), $msg_data);
                }

                // if($request->id)
                // {
                //     $data = $data->where('id',$request->id);
                // }

                if (isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search, 'amount|remark');
                }

                $total_records = $data->get()->count();

                $data = $data->limit($limit)->offset($offset)->get()->toArray();

                $i = 0;
                // foreach ($data as $row) {
                //     $data[$i]['product_image'] = getFile($row['product_image'], 'product');
                //     $data[$i]['product_thumb_image'] = getFile($row['product_thumb_image'], 'product', false, 'thumb');
                //     $i++;
                // }
                $responseData['result'] = $data;
                $responseData['awaiting_payments'] = $awaiting_payments;
                $responseData['payments_received'] = $payments_received->payments_received;
                // $responseData['payments_received'] = $grand_total - $awaiting_payments;
                $responseData['awaiting_orders'] = $awaiting_orders;
                $responseData['total_records'] = $total_records;
                successMessage('data_fetched_successfully', $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Payment fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
