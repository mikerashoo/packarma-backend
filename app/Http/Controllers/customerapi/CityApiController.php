<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use Response;

class CityApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 13-05-2022
     * Uses : Display a listing of the city.
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
                $data = City::with('state','country')->where('status','1');
                $cityData = City::with('state','country')->whereRaw("1 = 1");
                if($request->city_id)
                {
                    $cityData = $cityData->where('id',$request->city_id);
                    $data = $data->where('id',$request->city_id);
                }
                if($request->city_name)
                {
                    $cityData = $cityData->where('city_name',$request->city_name);
                    $data = $data->where('city_name',$request->city_name);
                }
                if(empty($cityData->first()))
                {
                    errorMessage(__('city.city_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'city_name');
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
            \Log::error("City fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
