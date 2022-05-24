<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
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
                $validationErrors = $this->validateRequest($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage(__('auth.validation_failed'), $validationErrors->all());
                }
                $page_no=1;
                $limit=10;
                
                if(isset($request->search_country_id)){
                    $country_id = $request->search_country_id;
                }
                else{
                    $country_id = 1;
                }
                if(isset($request->page_no) && !empty($request->page_no)) {
                    $page_no=$request->page_no;
                }
                if(isset($request->limit) && !empty($request->limit)) {
                    $limit=$request->limit;
                }
                $offset=($page_no-1)*$limit;

                $data = DB::table('cities')->select(
                    'cities.id',
                    'cities.city_name',
                    'states.state_name',
                    'countries.country_name',
                )
                    ->leftjoin('states', 'states.id', '=', 'cities.state_id')
                    ->leftjoin('countries', 'countries.id', '=', 'cities.country_id')
                    ->where([['cities.status', 1],['cities.state_id',$request->state_id],['cities.country_id',$country_id]]);

                $cityData = City::whereRaw("1 = 1");
                if($request->city_id)
                {
                    $cityData = $cityData->where('cities.id',$request->city_id);
                    $data = $data->where('cities.id',$request->city_id);
                }
                if($request->city_name)
                {
                    $cityData = $cityData->where('cities.city_name',$request->city_name);
                    $data = $data->where('cities.city_name',$request->city_name);
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
                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
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

    /**
     * Validate request for City.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'state_id' => 'required|numeric',
        ])->errors();
    }
}
