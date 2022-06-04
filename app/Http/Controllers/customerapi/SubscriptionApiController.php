<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use App\Models\UserSubscriptionPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Response;

class SubscriptionApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 10-05-2022
     * Uses : Display a listing of the subscription.
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
                $page_no=1;
                $limit=10;
                if(isset($request->page_no) && !empty($request->page_no)) {
                    $page_no=$request->page_no;
                }
                if(isset($request->limit) && !empty($request->limit)) {
                    $limit=$request->limit;
                }
                $offset=($page_no-1)*$limit;
                $data = Subscription::select('id','subscription_type','amount');
                $subscriptionData = Subscription::whereRaw("1 = 1");
                if($request->subscription_id)
                {
                    $subscriptionData = $subscriptionData->where('id', $request->subscription_id);
                    $data = $data->where('id',$request->subscription_id);
                }
                if($request->subscription_type)
                {
                    $subscriptionData = $subscriptionData->where('subscription_type',$request->subscription_type);
                    $data = $data->where('subscription_type',$request->subscription_type);
                }
                if(empty($subscriptionData->first()))
                {
                    errorMessage(__('subscription.subscription_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'subscription_type');
                }
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                if(empty($data)) {
                    errorMessage(__('subscription.subscription_not_found'), $msg_data);
                }
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
            \Log::error("Subscription fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 03-06-2022
     * Uses : Buy new subscription and store data in table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function buy_subscription(Request $request)
    {
        $msg_data = array();
        try
        {
            $token = readHeaderToken();
            if($token)
            {
                $user_id = $token['sub'];
                $user = User::find($user_id);
                $subscription = Subscription::find($request->subscription_id);

                if($subscription->subscription_type == 'monthly'){
                    $currentDateTime = Carbon::now()->toArray();
                    $subscription_start_date = $currentDateTime['formatted'];

                    $newDateTime = Carbon::now()->addMonths(1)->toArray();
                    $subscription_end_date =  $newDateTime['formatted'];
                }
                if($subscription->subscription_type == 'quarterly'){
                    $currentDateTime = Carbon::now()->toArray();
                    $subscription_start_date = $currentDateTime['formatted'];

                    $newDateTime = Carbon::now()->addMonths(3)->toArray();
                    $subscription_end_date =  $newDateTime['formatted'];
                }
                if($subscription->subscription_type == 'semi_yearly'){
                    $currentDateTime = Carbon::now()->toArray();
                    $subscription_start_date = $currentDateTime['formatted'];

                    $newDateTime = Carbon::now()->addMonths(6)->toArray();
                    $subscription_end_date =  $newDateTime['formatted'];
                }
                if($subscription->subscription_type == 'yearly'){
                    $currentDateTime = Carbon::now()->toArray();
                    $subscription_start_date = $currentDateTime['formatted'];

                    $newDateTime = Carbon::now()->addMonths(12)->toArray();
                    $subscription_end_date =  $newDateTime['formatted'];
                }
                //data to enter in user table of selected user id
                $subscription_request_data = array();
                $subscription_request_data['subscription_id'] = $subscription->id;
                $subscription_request_data['subscription_start'] = $subscription_start_date;
                $subscription_request_data['subscription_end'] = $subscription_end_date;
                $subscription_request_data['type'] = 'premium';

                //update subscription data of user
                $user->update($subscription_request_data);
                $subscription_data = $user;
                $subscribed = $subscription_data->toArray();
                $subscription_data->created_at->toDateTimeString();
                $subscription_data->updated_at->toDateTimeString();
                \Log::info("Subscription, user subscribed successfully!");

                //data to enter in subscription payment table
                $subscription_payment_data = array();
                $subscription_payment_data['user_id'] = $user_id;
                $subscription_payment_data['subscription_id'] = $subscription->id;
                $subscription_payment_data['amount'] = $subscription->amount;
                $subscription_payment_data['subscription_type'] = $subscription->subscription_type;
                $subscription_payment_data['payment_status'] = 'pending';
                $subscription_payment_data['created_by'] = $user_id;

                //store subsciption payment details to subscription payment table
                $subscription_payment = UserSubscriptionPayment::create($subscription_payment_data);
                \Log::info("Subscribed user payment details entered successfully!");

                $subscriptionPaymentData = $subscription_payment->toArray();
                $subscription_payment->created_at->toDateTimeString();
                $subscription_payment->updated_at->toDateTimeString();

                // successMessage(__('subscription.subscription_payment_entry_created_successfully'), $subscriptionPaymentData);
                successMessage(__('subscription.you_have_successfully_subscribed'), $subscribed);
            }
            else
            {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        }
        catch(\Exception $e)
        {
            \Log::error("Subscription fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 03-06-2022
     * Uses : Display a listing of my subscription.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function my_subscription(Request $request)
    {
        $msg_data = array();
        try
        {
            $token = readHeaderToken();
            if($token)
            {
                $user_id = $token['sub'];

                $data = DB::table('users')->select(
                    
                    'users.subscription_id',
                    'users.type',
                    'subscriptions.subscription_type',
                    'users.subscription_start',
                    'users.subscription_end',
                )
                    ->leftjoin('subscriptions', 'users.subscription_id', '=', 'subscriptions.id')
                    ->leftjoin('user_subscription_payments', 'users.subscription_id', '=', 'user_subscription_payments.subscription_id')
                    ->where([['users.id', $user_id],['user_subscription_payments.payment_status','paid']]);

                $data = $data->get()->toArray();
                if(empty($data)) {
                    errorMessage(__('subscription.subscription_not_found'), $msg_data);
                }
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
            \Log::error("Subscription fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * 
     * Created By : Pradyumn Dwivedi
     * Created at : 30/05/2022
     * Uses : Validate request for Buy subscription.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateBuySubscription(Request $request)
    {
        return \Validator::make($request->all(), [
            'subscription_id' => 'required|numeric',
        ])->errors();
    }
}
