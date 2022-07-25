<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\UserSubscriptionPayment;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\User;
use Response;

class UserSubscriptionPaymentApiController extends Controller
{
    /**
     * Created By Maaz Ansari
     * Created at : 22/07/2022
     * Uses : To start subscription payment process and store in table
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    private $testRazerpayKeyId;
    private $testRazerpayKeySecrete;
    private $liveRazerpayKeyId;
    private $liveRazerpayKeySecrete;

    public function __construct()
    {
        $this->testRazerpayKeyId = 'rzp_test_IbGrIYYPsUpuDu';
        $this->testRazerpayKeySecrete = 'eQ0raEWDhl22k47atkqZXAvm';
        $this->liveRazerpayKeyId = '';
        $this->liveRazerpayKeySecrete = '';
    }

    public function new_subscription_payment(Request $request)
    {
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];
                $platform = $request->header('platform');
                $ip_address = request()->ip();

                $validationErrors = $this->validateNewSubscriptionPayment($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage($validationErrors->all(), $validationErrors->all());
                }
                \Log::info("Subscription payment started!");

                // $user = User::find(auth('api')->user()->id);
                $Subscription = Subscription::find($request->subscription_id);
                if ($Subscription) {
                    $data = [];

                    if ($Subscription->amount == 0) {
                        $payment_status = 'paid';
                        $razorpay_order_id = NULL;
                    } else {
                        $payment_status = 'pending';
                        $api = new Api($this->testRazerpayKeyId, $this->testRazerpayKeySecrete);
                        $razorpay_order = $api->subscription_payment->create(
                            array(
                                'amount' => $Subscription->amount * 100,
                                'currency' => 'INR'
                            )
                        );
                        $razorpay_order_id = $razorpay_order['id'];
                    }

                    $userSubscriptionPayment = new UserSubscriptionPayment();
                    $userSubscriptionPayment->user_id = $user_id;
                    $userSubscriptionPayment->subscription_id = $Subscription->id;
                    $userSubscriptionPayment->subscription_type = $Subscription->subscription_type;
                    $userSubscriptionPayment->amount = $Subscription->amount;
                    $userSubscriptionPayment->gateway_id = $razorpay_order_id;
                    $userSubscriptionPayment->payment_status = $payment_status;
                    $userSubscriptionPayment->transaction_date = date('Y-m-d');
                    $userSubscriptionPayment->call_from = $platform;
                    $userSubscriptionPayment->ip_address = $ip_address;
                    $userSubscriptionPayment->save();

                    if ($Subscription->amount == 0) {
                        $data['gateway_id'] = '';
                        $data['razorpay_api_key'] = '';
                        $data['currency'] = '';
                        $data['amount'] = $Subscription->amount;
                        $data['gateway_call'] = 'no';
                        $data['msg'] = 'Thank you, you have successfully completed your Payment';
                    } else {
                        $data['gateway_id'] = $razorpay_order_id;
                        $data['razorpay_api_key'] = $this->testRazerpayKeyId;
                        $data['currency'] = 'INR';
                        $data['amount'] = $Subscription->amount;
                        $data['gateway_call'] = 'yes';
                        $data['msg'] = 'Please continue to pay the order amount';
                    }
                    return response()->json($data)->setStatusCode(200);
                } else {
                    errorMessage(__('subscription.subscription_not_found'), $msg_data);
                }
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data, 400);
            }
        } catch (\Exception $e) {
            \Log::error("My new Subscription payment failed: " . $e->getMessage());
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
    public function subscription_payment_success(Request $request)
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

                $api = new Api($this->testRazerpayKeyId, $this->testRazerpayKeySecrete);
                try {
                    $payment = $api->payment->fetch($request->gateway_key);
                } catch (\Exception $e) {
                    // return response()->json(['msg' => 'Gateway key given not correct, Payment failed'], 400);
                    errorMessage(__('payment.wrong_gateway_key'), $msg_data, 400);
                }

                if ($payment) {
                    $subscriptionPayment = UserSubscriptionPayment::where('gateway_id', $request->gateway_id)->first();
                    if ($subscriptionPayment) {
                        $subscriptionPayment->gateway_key = $payment->id;
                        $subscriptionPayment->transaction_date = date('Y-m-d');
                        $subscriptionPayment->call_from = $platform;
                        $subscriptionPayment->ip_address = $ip_address;
                        $subscriptionPayment->payment_status = 'paid';
                        $subscriptionPayment->save();


                        //update order status in order table 
                        $User = User::find($user_id);
                        $User->subscription_id = $subscriptionPayment->subscription_id;
                        $User->save();

                        // return response()->json(['msg' => 'Subscribed successfully'], 200);
                        successMessage(__('subscription.you_have_successfully_subscribed'), $msg_data);
                    } else {
                        // return response()->json(['msg' => 'Subscription not found'], 400);
                        errorMessage(__('subscription.subscription_not_found'), $msg_data, 400);
                    }
                } else {
                    // return response()->json(['msg' => 'Payment failed'], 400);
                    errorMessage(__('payment.payment_failed'), $msg_data, 400);
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
     * Created By : Maaz Ansari
     * Created at : 22/07/2022
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
            'subscription_id' => 'required|numeric'
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
