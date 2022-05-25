<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\UserAddress;
use Response;

class UserAddressApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 16-05-2022
     * Uses : Display a listing of the User address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        try
        {
            //user token
            $token = readHeaderToken();
            if($token)
            {
                $user_id = $token['sub'];
                $page_no=1;
                $limit=10;
                if(isset($request->page_no) && !empty($request->page_no)) {
                    $page_no=$request->page_no;
                }
                if(isset($request->limit) && !empty($request->limit)) {
                    $limit=$request->limit;
                }
                $offset=($page_no-1)*$limit;

                $data = DB::table('user_addresses')->select(
                    'user_addresses.id',
                    'user_addresses.address_name',
                    'user_addresses.gstin',
                    'user_addresses.type',
                    'countries.phone_code',
                    'user_addresses.mobile_no',
                    'countries.country_name',
                    'states.state_name',
                    'cities.city_name',
                    'user_addresses.address',
                    'user_addresses.pincode',
                )
                    ->leftjoin('countries', 'user_addresses.country_id', '=', 'countries.id')
                    ->leftjoin('states', 'user_addresses.state_id', '=', 'states.id')
                    ->leftjoin('cities', 'user_addresses.city_id', '=', 'cities.id')
                    ->where('user_addresses.user_id', $user_id);
                    
                $userAddressData = UserAddress::with('user')->whereRaw("1 = 1");
                if($request->address_id)
                {
                    $userAddressData = $userAddressData->where('user_addresses.id', $request->address_id);
                    $data = $data->where('user_addresses.id',$request->address_id);
                }
                // getting user address type for filter
                if($request->address_type)
                {
                    $userAddressData = $userAddressData->where('user_addresses.type',$request->address_type);
                    $data = $data->where('user_addresses.type',$request->address_type);
                }
                if($request->address_name)
                {
                    $userAddressData = $userAddressData->where('user_addresses.address_name',$request->address_name);
                    $data = $data->where('user_addresses.address_name',$request->address_name);
                }
                if(empty($userAddressData->first()))
                {
                    errorMessage(__('user_address.address_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'address_name|type|address|pincode');
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
            \Log::error("User Address fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
