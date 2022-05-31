<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackagingMachine;
use Response;

class PackagingMachineApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 12-05-2022
     * Uses : Display a listing of the Packaging Machine.
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
                $data = PackagingMachine::select('id','packaging_machine_name','packaging_machine_description','packaging_machine_image','packaging_machine_thumb_image','meta_title','meta_description','meta_keyword')->where('status','1');
                $machineData = PackagingMachine::whereRaw("1 = 1");
                if($request->machine_id)
                {
                    $machineData = $machineData->where('id', $request->machine_id);
                    $data = $data->where('id',$request->machine_id);
                }
                if($request->machine_name)
                {
                    $machineData = $machineData->where('packaging_machine_name',$request->machine_name);
                    $data = $data->where('packaging_machine_name',$request->machine_name);
                }
                if(empty($machineData->first()))
                {
                    errorMessage(__('packaging_machine.packaging_machine_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'packaging_machine_name|packaging_machine_description');
                }
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                $i=0;
                foreach($data as $row)
                {
                    $data[$i]['packaging_machine_image'] = getFile($row['packaging_machine_image'], 'packaging_machine');
                    $data[$i]['packaging_machine_thumb_image'] = getFile($row['packaging_machine_thumb_image'], 'packaging_machine',false,'thumb');
                    $i++;
                }
                if(empty($data)) {
                    errorMessage(__('packaging_machine.packaging_machine_not_found'), $msg_data);
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
            \Log::error("Packaging Machine fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
