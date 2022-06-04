<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// require_once __DIR__ . "Razorpay/Razorpay.php";
use Razorpay\Api\Api;
use App\Models\Order;
use App\Models\User;
use Response;

class OrderPaymentApiController extends Controller
{
    /**
     * Created By Pradyumn Dwivedi
     * Created at : 02/06/2022
     * Uses : To start payment process and store in table
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function new_order_payment(Request $request){
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];

                $validationErrors = $this->validateNewOrderPayment($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage(__('auth.validation_failed'), $validationErrors->all());
                }
                \Log::info("My order payment started!");

                // $user = User::find(auth('api')->user()->id);
                $order = Order::find($request->order_id);
                if($order){
                    $data =[];
                    
                    if($order->customer_pending_payment == 0){
                        $payment_status = 'fully_paid';
                        $razorpay_order_id = NULL;
                    }else{
                        $payment_status = 'pending';
                        $api = new Api('rzp_live_bR8azqSzFdzMBU','c9emzzGMOhWxXsACsEKfQKwi');

                        print_r($api->order->fetch('order_JdMgJNcDrkslK8')); exit;

                        $payment_data = $api->order->create(array('receipt' => '123', 'amount' => 100, 'currency' => 'INR', 'notes'=> array('key1'=> 'value3','key2'=> 'value2')));
                        print_r($payment_data); exit;
                        // $api = new Api('rzp_live_bR8azqSzFdzMBU','c9emzzGMOhWxXsACsEKfQKwi');
                        // $input = array(
                        //     'amount' => $order->customer_pending_payment * 100,
                        //     'currency' => 'INR'
                        // );
                        // $api = new Api('rzp_live_bR8azqSzFdzMBU','c9emzzGMOhWxXsACsEKfQKwi');
  
                        // $payment = $api->payment->fetch('*');
                        // return $payment;
                        // if(count($input)  && !empty($input['razorpay_payment_id'])) {
                        //     $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$order->customer_pending_payment)); 
                        // }

                        // $razorpay_order_id = $input['razorpay_payment_id'];
                    }

                    $orderPayment = new OrderPayment;
                    $orderPayment->user_id = $user_id;
                    $orderPayment->order_id = $order->id;
                    $orderPayment->amount = $order->customer_pending_payment;
                    $orderPayment->gateway_id = $razorpay_order_id;
                    $orderPayment->payment_status = $payment_status;
                    $orderPayment->save();

                    if($order->customer_pending_payment == 0){
                        $data['gateway_id'] = '';
                        $data['razorpay_api_key'] = '';
                        $data['currency'] = '';
                        $data['amount'] = $order->customer_pending_payment;
                        $data['gateway_call'] = 'no';
                        $data['msg'] = 'Thank you, you have successfully completed your Payment';
                    }else{
                        $data['gateway_id'] = $razorpay_order_id;
                        $data['razorpay_api_key'] = 'rzp_live_bR8azqSzFdzMBU';
                        $data['currency'] = 'INR';
                        $data['amount'] = $order->customer_pending_payment;
                        $data['gateway_call'] = 'yes';
                        $data['msg'] = 'Please continue to pay the order amount';
                    }
                    return response()->json($data)->setStatusCode(200);
                }else{
                    return response()->json(['msg' => 'Order not found'],400);
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
     * Created By Pradyumn Dwivedi
     * Created at : 02/06/2022
     * Uses : To check payment success status
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function order_payment_success(Request $request){
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];

                $validationErrors = $this->validatePaymentSuccess($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage(__('auth.validation_failed'), $validationErrors->all());
                }
                \Log::info("Checking Payment success status!");

            // $user = User::find(auth('api')->user()->id);

                $api = new Api('rzp_live_bR8azqSzFdzMBU','c9emzzGMOhWxXsACsEKfQKwi');
                try{
                    $payment = $api->payment->fetch($request->payment_id);
                }catch (\Exception $e) {
                    return response()->json(['msg' => 'Payment ID given not correct, Payment failed'],400);
                }
                    
                if($payment){
                    $orderPayment = OrderPayment::where('gateway_id',$request->gateway_id)->first();
                    if($orderPayment){
                        $orderPayment->gateway_key = $payment->id;
                        $orderPayment->status = 'fully_paid';
                        $orderPayment->save();

                        //update customer pending payment and vendor pending payment 
                        $orderTable = new Order;
                        $orderTable->customer_pending_payment = 0;
                        $orderTable->vendor_pending_payment = $order->vendor_price;
                        $orderTable->save();

                        return response()->json(['msg' => 'Order placed successfully'],200);
                    }else{
                        return response()->json(['msg' => 'Order not found'],400);
                    }
                }else{
                    return response()->json(['msg' => 'Payment failed'],400);
                }            
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("My new Order payment success checking failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }


    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 02/06/2022
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
     * Created By : Pradyumn Dwivedi
     * Created at : 02/06/2022
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
            'order_id' => 'required|numeric',
            'payment_id' => 'required|numeric'
        ])->errors();
    }
}
