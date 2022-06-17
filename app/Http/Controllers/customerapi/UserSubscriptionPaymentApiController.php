<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use App\Models\UserSubscriptionPayment;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\User;
use Response;

class UserSubscriptionPaymentApiController extends Controller
{
    /**
     * Created By Pradyumn Dwivedi
     * Created at : 03/06/2022
     * Uses : To start subscription payment process and store in table
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function new_subscription_payment(Request $request){
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];

                $validationErrors = $this->validateNewSubscriptionPayment($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage($validationErrors->all(), $validationErrors->all());
                }
                \Log::info("My order payment started!");

                // $user = User::find(auth('api')->user()->id);
                $subscription_payment_data = UserSubscriptionPayment::find($request->user_subscription_payment_id);
                if($subscription_payment_data){
                    $data =[];
                    
                    if($subscription_payment_data->amount == 0){
                        $payment_status = 'paid';
                        $razorpay_order_id = NULL;
                    }else{
                        $payment_status = 'pending';
                        $api = new Api('rzp_live_bR8azqSzFdzMBU','c9emzzGMOhWxXsACsEKfQKwi');
                        $razorpay_order = $api->subscription_payment->create(array(
                            'amount' => $subscription_payment_data->amount * 100,
                            'currency' => 'INR'
                            )
                        );
                        $razorpay_order_id = $razorpay_order['id'];
                    }

                    $userSubscriptionPayment = new UserSubscriptionPayment;
                    $userSubscriptionPayment->payment_reference = $razorpay_order_id;
                    $userSubscriptionPayment->payment_status = $payment_status;
                    $userSubscriptionPayment->save();

                    if($subscription_payment_data->amount == 0){
                        $data['gateway_id'] = '';
                        $data['razorpay_api_key'] = '';
                        $data['currency'] = '';
                        $data['amount'] = $subscription_payment_data->amount;
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
            \Log::error("My new Subscription payment failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By Pradyumn Dwivedi
     * Created at : 03/06/2022
     * Uses : To check payment success status
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function subscription_payment_success(Request $request){
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];

                $validationErrors = $this->validatePaymentSuccess($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage($validationErrors->all(), $validationErrors->all());
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
                    $subscriptionPayment = UserSubscriptionPayment::where('gateway_id',$request->gateway_id)->first();
                    if($subscriptionPayment){
                        $subscriptionPayment->payment_reference = $payment->id;
                        $subscriptionPayment->payment_status = 'paid';
                        $subscriptionPayment->save();

                        return response()->json(['msg' => 'Subscribed successfully'],200);
                    }else{
                        return response()->json(['msg' => 'Subscription not found'],400);
                    }
                }else{
                    return response()->json(['msg' => 'Payment failed'],400);
                }            
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("My subscription payment success checking failed: " . $e->getMessage());
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
    private function validateNewSubscriptionPayment(Request $request)
    {
        return \Validator::make($request->all(), [
            'user_subscription_payment_id' => 'required|numeric'
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
