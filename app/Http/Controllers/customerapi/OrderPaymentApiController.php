<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// require_once __DIR__ . "Razorpay/Razorpay.php";
use Razorpay\Api\Api;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\User;
use Response;

class OrderPaymentApiController extends Controller
{
    /**
     * Created By Maaz Ansari
     * Created at : 22/07/2022
     * Uses : To start payment process and store in table
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function new_order_payment(Request $request)
    {
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];
                $platform = $request->header('platform');
                $ip_address = request()->ip();

                $validationErrors = $this->validateNewOrderPayment($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage($validationErrors->all(), $validationErrors->all());
                }
                \Log::info("My order payment started!");

                // $user = User::find(auth('api')->user()->id);
                $order = Order::find($request->order_id);
                if ($order) {
                    $data = [];

                    if ($order->grand_total == 0) {
                        $payment_status = 'fully_paid';
                        $razorpay_order_id = NULL;
                    } else {
                        $payment_status = 'pending';
                        $api = new Api(config('app.testRazerpayKeyId'), config('app.testRazerpayKeySecrete'));
                        $razorpay_order = $api->order->create(
                            array(
                                'amount' => $order->grand_total * 100,
                                'currency' => 'INR'
                            )
                        );
                        $razorpay_order_id = $razorpay_order['id'];
                    }

                    $orderPayment = new OrderPayment();
                    $orderPayment->user_id = $user_id;
                    $orderPayment->order_id = $order->id;
                    $orderPayment->product_id = $order->product_id;
                    $orderPayment->vendor_id = $order->vendor_id;
                    $orderPayment->amount = $order->grand_total;
                    $orderPayment->gateway_id = $razorpay_order_id;
                    $orderPayment->payment_status = $payment_status;
                    $orderPayment->transaction_date = date('Y-m-d');
                    $orderPayment->call_from = $platform;
                    $orderPayment->ip_address = $ip_address;
                    $orderPayment->save();

                    if ($order->grand_total == 0) {
                        $data['gateway_id'] = '';
                        $data['razorpay_api_key'] = '';
                        $data['currency'] = '';
                        $data['amount'] = $order->grand_total;
                        $data['gateway_call'] = 'no';
                        $data['msg'] = 'Thank you, you have successfully completed your Payment';
                    } else {
                        $data['gateway_id'] = $razorpay_order_id;
                        $data['razorpay_api_key'] = config('app.testRazerpayKeyId');
                        $data['currency'] = 'INR';
                        $data['amount'] = $order->grand_total;
                        $data['gateway_call'] = 'yes';
                        $data['msg'] = 'Please continue to pay the order amount';
                    }
                    return response()->json($data)->setStatusCode(200);
                } else {
                    errorMessage(__('order.order_not_found'), $msg_data, 400);
                }
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("My new Order payment failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By Maaz Ansari
     * Created at : 22/07/2022
     * Uses : To check payment success status
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function order_payment_success(Request $request)
    {
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];
                $platform = $request->header('platform');
                $ip_address = request()->ip();

                $validationErrors = $this->validatePaymentSuccess($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage($validationErrors->all(), $validationErrors->all());
                }
                \Log::info("Checking Payment success status!");

                // $user = User::find(auth('api')->user()->id);

                $api = new Api(config('app.testRazerpayKeyId'), config('app.testRazerpayKeySecrete'));
                try {
                    $payment = $api->payment->fetch($request->gateway_key);
                } catch (\Exception $e) {
                    // return response()->json(['msg' => 'Gateway Key given not correct, Payment failed'], 400);
                    errorMessage(__('payment.wrong_gateway_key'), $msg_data, 400);
                }

                if ($payment) {
                    $orderPayment = OrderPayment::where('gateway_id', $request->gateway_id)->first();
                    if ($orderPayment) {
                        $orderPayment->gateway_key = $payment->id;
                        $orderPayment->transaction_date = date('Y-m-d');
                        $orderPayment->call_from = $platform;
                        $orderPayment->ip_address = $ip_address;
                        $orderPayment->payment_status = 'fully_paid';
                        $orderPayment->save();

                        //update order status in order table 
                        $orderTable = Order::find($orderPayment->order_id);;
                        $orderTable->customer_payment_status = 'fully_paid';
                        $orderTable->customer_pending_payment = 0.00;
                        $orderTable->save();
                        successMessage(__('order.order_placed'), $msg_data);
                        // return response()->json(['msg' => 'Order placed successfully'], 200);
                    } else {
                        // return response()->json(['msg' => 'Order not found'], 400);
                        errorMessage(__('order.order_not_found'), $msg_data, 400);
                    }
                } else {
                    errorMessage(__('payment.payment_failed'), $msg_data, 400);
                    // return response()->json(['msg' => 'Payment failed'], 400);
                }
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("My new Order payment success checking failed: " . $e->getMessage());
            errorMessage(__('payment.payment_exception'), $msg_data);
        }
    }


    /**
     * Created By : Maaz Ansari
     * Created at : 22/07/2022
     * Uses : To validate order payment request
     * 
     * Validate request for registeration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateNewOrderPayment(Request $request)
    {
        return \Validator::make($request->all(), [
            'order_id' => 'required|numeric'
        ])->errors();
    }

    /**
     * Created By : Maaz Ansari
     * Created at : 22/07/2022
     * Uses : To validate payment success request
     * 
     * Validate request for registeration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validatePaymentSuccess(Request $request)
    {
        return \Validator::make($request->all(), [
            'gateway_id' => 'required',
            'gateway_key' => 'required'
        ])->errors();
    }
}
