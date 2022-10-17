<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\VendorNotificationHistory;
use Response;

class VendorNotificationHistoryApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 14-Oct-2022
     * Uses : Display a listing of the vendor notifcations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        try
        {
            $token = readVendorHeaderToken();
            if($token)
            {
                $imei_no = $request->header('imei-no');
                $vendor_id = $token['sub'];
                $page_no=1;
                $limit=10;
                $last_no_of_days = 15;
                $orderByArray = ['vendor_notification_histories.id' => 'DESC'];
                $defaultSortByName = false;
                if(isset($request->page_no) && !empty($request->page_no)) {
                    $page_no=$request->page_no;
                }
                if(isset($request->limit) && !empty($request->limit)) {
                    $limit=$request->limit;
                }
                $offset=($page_no-1)*$limit;
                $data = VendorNotificationHistory::select('id','notification_name','page_name','type_id','title','body','created_at')->where([['status','1'],['vendor_id', $vendor_id],['deleted_at', NULL]]);
                $notificationData = VendorNotificationHistory::whereRaw("1 = 1");
                if($request->id)
                {
                    $notificationData = $notificationData->where('id',$request->id);
                    $data = $data->where('id',$request->id);
                }
                if($request->title)
                {
                    $notificationData = $notificationData->where('title',$request->title);
                    $data = $data->where('title',$request->title);
                }

                // last number of days record
                $date_from_no_of_days = Carbon::now()->subDays($last_no_of_days);
                $notificationData = $notificationData->whereDate('created_at', '>=', $date_from_no_of_days);
                $data = $data->whereDate('created_at', '>=', $date_from_no_of_days);

                if(empty($notificationData->first()))
                {
                    errorMessage(__('vendor_notification_history.notification_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'title|body');
                }
                if ($defaultSortByName) {
                    $orderByArray = ['vendor_notification_histories.title' => 'ASC'];
                }
                $data = allOrderBy($data, $orderByArray);
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                // $i=0;
                // foreach($data as $row)
                // {
                //     $data[$i]['category_image'] = getFile($row['category_image'], 'category');
                //     $data[$i]['category_thumb_image'] = getFile($row['category_thumb_image'], 'category',false,'thumb');
                //     $i++;
                // }
                if(empty($data)) {
                    errorMessage(__('vendor_notification_history.notification_not_found'), $msg_data);
                }
                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;
                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Vendor Notificcation fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
