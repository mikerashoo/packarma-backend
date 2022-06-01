<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StorageCondition;
use Response;

class StorageConditionApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 12-05-2022
     * Uses : Display a listing of the Storage Condition.
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
                $data = StorageCondition::select('id','storage_condition_title','storage_condition_description','seo_url','meta_title','meta_description','meta_keyword')->where('status','1');
                $storageConditionData = StorageCondition::whereRaw("1 = 1");
                if($request->storage_condition_id)
                {
                    $storageConditionData = $storageConditionData->where('id', $request->storage_condition_id);
                    $data = $data->where('id',$request->storage_condition_id);
                }
                if($request->storage_condition_title)
                {
                    $storageConditionData = $storageConditionData->where('storage_condition_title',$request->storage_condition_title);
                    $data = $data->where('storage_condition_title',$request->storage_condition_title);
                }
                if(empty($storageConditionData->first()))
                {
                    errorMessage(__('storage_condition.storage_condition_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'storage_condition_title|storage_condition_description');
                }
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                if(empty($data)) {
                    errorMessage(__('storage_condition.storage_condition_not_found'), $msg_data);
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
            \Log::error("Storage Condition fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
