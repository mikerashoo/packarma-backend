<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
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
                $data = Subscription::whereRaw("1 = 1");
                $subscriptionData = Subscription::whereRaw("1 = 1");
                if($request->subscription_id)
                {
                    $subscriptionData = $subscriptionData->where('id', $request->subscription_id);
                    $data = $data->where('id',$request->subscription_id);
                }
                if($request->subscription_type)
                {
                    $subscriptionData = $subscriptionData->where('type',$request->subscription_type);
                    $data = $data->where('type',$request->subscription_type);
                }
                if(empty($subscriptionData->first()))
                {
                    errorMessage(__('subscription.subscription_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'type');
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
            \Log::error("Subscription fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
