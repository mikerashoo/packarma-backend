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
                $main_table = 'user_addresses';
                $data = DB::table('user_addresses')->select(
                    'user_addresses.id',
                    'user_addresses.address_name',
                    'user_addresses.gstin',
                    'user_addresses.type',
                    'countries.phone_code',
                    'user_addresses.mobile_no',
                    'countries.country_name',
                    'states.state_name',
                    // 'cities.city_name',
                    'user_addresses.address',
                    'user_addresses.pincode',
                    'user_addresses.flat',
                    'user_addresses.area',
                    'user_addresses.land_mark',
                    'user_addresses.city_name'
                )
                    ->leftjoin('countries', 'user_addresses.country_id', '=', 'countries.id')
                    ->leftjoin('states', 'user_addresses.state_id', '=', 'states.id')
                    // ->leftjoin('cities', 'user_addresses.city_id', '=', 'cities.id')
                    ->where([[$main_table . '' . '.status', '1'], [$main_table . '' . '.deleted_at', NULL]])->where('user_id', $user_id);
                    
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
                    $data = fullSearchQuery($data, $request->search,'address_name|type|address|pincode|city_name|flat|area|land_mark');
                }
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                if(empty($data)) {
                    errorMessage(__('user_address.address_not_found'), $msg_data);
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
            \Log::error("User Address fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 30/05/2022
     * Uses : To create new address of user
     * 
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];
                
                // Request Validation
                $addressValidationErrors = $this->validateAddressRegister($request);
                if (count($addressValidationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $addressValidationErrors->all()));
                    errorMessage($addressValidationErrors->all(), $addressValidationErrors->all());
                }
                \Log::info("User address creation started!");
                $user_address_data = array();
                $user_address_data = $request->all();
                $user_address_data['user_id'] = $user_id;

                // Store a new vendor address
                $userAddressData = UserAddress::create($user_address_data);
                \Log::info("My address created successfully!");

                $userAddress = $userAddressData->toArray();
                $userAddressData->created_at->toDateTimeString();
                $userAddressData->updated_at->toDateTimeString();


                successMessage(__('user_address.my_address_created_successfully'), $userAddress);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("My address Creation failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 30/05/2022
     * Uses : Store Updated user address
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];
                
                // Request Validation
                $addressValidationErrors = $this->validateAddressUpdate($request);
                if (count($addressValidationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $addressValidationErrors->all()));
                    errorMessage($addressValidationErrors->all(), $addressValidationErrors->all());
                }
                \Log::info("User Address Update started!");
                $user_address_data = array();
                if (!$request->id) {
                    errorMessage(__('user_address.id_required'), $msg_data);
                }
                $id = $request->id;

                // Store a updated user address
                $checkUserAddress = UserAddress::where([['id', $id], ['user_id', $user_id]])->first();
                if (empty($checkUserAddress)) {
                    errorMessage(__('user_address.address_not_found'), $msg_data);
                }
                $user_address_data = $request->all();
                $user_address_data['user_id'] = $user_id;
                unset($user_address_data['id']);
                $checkUserAddress->update($user_address_data);
                $userAddressData = $checkUserAddress;

                $userAddress = $userAddressData->toArray();
                $userAddressData->created_at->toDateTimeString();
                $userAddressData->updated_at->toDateTimeString();

                \Log::info("My address Updated successfully!");

                successMessage(__('user_address.my_address_updated_successfully'), $userAddress);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("My Address Updation failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 30/05/2022
     * Uses : Delete user address of selected id
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];

                \Log::info("My address deletion started!");
                if (!$request->id) {
                    errorMessage(__('user_address.id_required'), $msg_data);
                }
                $id = $request->id;

                $checkUserAddress = UserAddress::where([['id', $id], ['user_id', $user_id]])->first();
                if (empty($checkUserAddress)) {
                    errorMessage(__('user_address.address_not_found'), $msg_data);
                }
                $checkUserAddress->delete();
                \Log::info("My address deleted successfully!");
                successMessage(__('user_address.deleted_successfully'), $msg_data);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("My Address deletion failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * 
     * Created By : Pradyumn Dwivedi
     * Created at : 30/05/2022
     * Uses : 30/05/2022
     * 
     * Validate request for registeration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateAddressRegister(Request $request)
    {
        return \Validator::make($request->all(), [
            'country_id' => 'required|numeric',
            'address_name' => 'required|string',
            'mobile_no' => 'required|numeric|digits:10|unique:user_addresses,mobile_no',
            'pincode' => 'required|numeric|digits:6',
            'flat' => 'required|string',
            'area' => 'required|string',
            'land_mark' => 'required|string',
            'city_name' => 'required|string',
            'state_id' => 'required|numeric',
            'type' => 'required|in:shipping,billing',
            'gstin'=> 'required_if:type,==,billing|string|min:15|max:15|regex:' . config('global.GST_NO_VALIDATION') . '|unique:user_addresses,gstin',
        ])->errors();
    }

    /**
     * 
     * Created By : Pradyumn Dwivedi
     * Created at : 30/05/2022
     * Uses : to validate address update request
     * 
     * Validate request for registeration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateAddressUpdate(Request $request)
    {
        return \Validator::make($request->all(), [
            'id' => 'required|numeric',
            'country_id' => 'required|numeric',
            'address_name' => 'required|string',
            'mobile_no' => 'required|numeric|digits:10|unique:user_addresses,mobile_no' . ($request->id ? ",$request->id" : ''),
            'pincode' => 'required|digits:6',
            'flat' => 'required|string',
            'area' => 'required|string',
            'land_mark' => 'required|string',
            'city_name' => 'required|string',
            'state_id' => 'required|numeric',
            'type' => 'required|in:shipping,billing',
            'gstin'=> 'required_if:type,==,billing|string|min:15|max:15|regex:' . config('global.GST_NO_VALIDATION') . '|unique:user_addresses,gstin' . ($request->id ? ",$request->id" : ''),
        ])->errors();
    }
}
