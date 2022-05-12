<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MeasurementUnit;
use Response;

class MeasurementUnitApiController extends Controller
{
   /**
     * Created By : Pradyumn Dwivedi
     * Created at : 12-05-2022
     * Uses : Display a listing of the measurement unit.
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
                
                $data = MeasurementUnit::where('status','1');
                $unitData = MeasurementUnit::whereRaw("1 = 1");
                if($request->unit_id)
                {
                    $unitData = $unitData->where('id', $request->unit_id);
                    $data = $data->where('id',$request->unit_id);
                }
                if($request->unit_name)
                {
                    $unitData = $unitData->where('unit_name',$request->unit_name);
                    $data = $data->where('unit_name',$request->unit_name);
                }
                if($request->unit_symbol)
                {
                    $unitData = $unitData->where('unit_symbol',$request->unit_symbol);
                    $data = $data->where('unit_symbol',$request->unit_symbol);
                }
                if(empty($unitData->first()))
                {
                    errorMessage(__('measurement_unit.measurement_unit_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'unit_name|unit_symbol');
                }
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;
                successMessage('data_fetched_successfully', $responseData);
            }
            else
            {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        }
        catch(\Exception $e)
        {
            \Log::error("Measurement Unit fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
