<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackingType;
use Response;

class PackingTypeApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 13-05-2022
     * Uses : Display a listing of the packing types.
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

                $data = PackingType::select('id','packing_name','packing_description','meta_title','meta_description','meta_keyword')
                                    ->where('status','1');

                $packingTypeData = PackingType::whereRaw("1 = 1");
                if($request->packaging_type_id)
                {
                    $packingTypeData = $packingTypeData->where('id',$request->packaging_type_id);
                    $data = $data->where('id',$request->packaging_type_id);
                }
                if($request->packaging_type_name)
                {
                    $packingTypeData = $packingTypeData->where('packing_name',$request->packaging_type_name);
                    $data = $data->where('packing_name',$request->packaging_type_name);
                }
                if(empty($packingTypeData->first()))
                {
                    errorMessage(__('packing_type.packaging_type_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'packing_name|packing_description');
                }
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                if(empty($data)) {
                    errorMessage(__('packing_type.packaging_type_not_found'), $msg_data);
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
            \Log::error("Packaging Type fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
