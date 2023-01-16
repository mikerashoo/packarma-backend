<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\State;
use Response;

class StateApiController extends Controller
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
                $orderByArray = ['states.state_name' => 'ASC'];
                $defaultSortByName = false;
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
                $data = DB::table('states')->select(
                    'states.id',
                    'states.state_name',
                    'countries.country_name',
                )
                    ->leftjoin('countries', 'countries.id', '=', 'states.country_id')
                    ->where([['states.status', 1],['states.country_id',$country_id]]);

                $stateData = State::whereRaw("1 = 1");
                if($request->state_id)
                {
                    $stateData = $stateData->where('states.id',$request->state_id);
                    $data = $data->where('states.id',$request->state_id);
                }
                if($request->state_name)
                {
                    $stateData = $stateData->where('states.state_name',$request->state_name);
                    $data = $data->where('states.state_name',$request->state_name);
                }
                if(empty($stateData->first()))
                {
                    errorMessage(__('state.state_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'state_name');
                }
                if ($defaultSortByName) {
                    $orderByArray = ['states.state_name' => 'ASC'];
                }
                $data = allOrderBy($data, $orderByArray);
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                if(empty($data)) {
                    errorMessage(__('state.state_not_found'), $msg_data);
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
            \Log::error("State fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
