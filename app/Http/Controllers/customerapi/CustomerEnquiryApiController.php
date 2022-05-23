<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerEnquiry;
use App\Models\UserAddress;
use Carbon\Carbon;
use Response;

class CustomerEnquiryApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 17-05-2022
     * Uses : Display a listing of the customer enquiry listing.
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
                $data = CustomerEnquiry::with('user','category','sub_category','product','measurement_unit','storage_condition','packaging_machine','product_form','packing_type','packaging_treatment','recommendation_engine','user_address')
                                ->where([['user_id', $user_id]]);
                $customerEnquiryData = CustomerEnquiry::whereRaw("1 = 1");
                if($request->enquiry_id)
                {
                    $customerEnquiryData = $customerEnquiryData->where('id',$request->enquiry_id);
                    $data = $data->where('id',$request->enquiry_id);
                }
                if($request->product_id)
                {
                    $customerEnquiryData = $customerEnquiryData->where('product_id',$request->product_id);
                    $data = $data->where('product_id',$request->product_id);
                }
                if(empty($customerEnquiryData->first()))
                {
                    errorMessage(__('customer_enquiry.customer_enquiry_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'description');
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
            \Log::error("Customer Enquiry fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 16-05-2022
     * Uses : Store newly created customer enquiry data in table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $msg_data = array();
        \Log::info("Initiating Customer Enquiry process, starting at: " . Carbon::now()->format('H:i:s:u'));
        try
        {
            $token = readHeaderToken();
            if($token)
            {
                // Request Validation
                $validationErrors = $this->validateEnquiry($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage(__('auth.validation_failed'), $validationErrors->all());
                }
                //getting user address details from userAddress and putting value to request to store in cumstomer enquiry table
                $userAddress = UserAddress::find($request->user_address_id);
                $request['country_id'] = $userAddress->country_id;
                $request['state_id'] = $userAddress->state_id;
                $request['city_id'] = $userAddress->city_id;
                $request['address'] = $userAddress->address;
                $request['pincode'] = $userAddress->pincode;
                // Store a new enquiry
                $enquiryData = CustomerEnquiry::create($request->all());
                \Log::info("Customer Enquiry Created successfully");
                successMessage(__('customer_enquiry.customer_enquiry_placed_successfully'), $enquiryData->toArray());
             }
            else
            {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        }
        catch(\Exception $e)
        {
            \Log::error("Customer enquiry creation failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Validate request for Customer Enquiry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    private function validateEnquiry(Request $request)
    {
        return \Validator::make($request->all(), [
            'description' => 'required|string',
            'user_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'sub_category_id' => 'required|numeric',
            'product_id' => 'required|numeric',
            'shelf_life' => 'required|numeric',
            'product_weight' => 'required|numeric',
            'measurement_unit_id' => 'required|numeric',
            'product_quantity' => 'required|numeric',
            'storage_condition_id' => 'required|numeric',
            'packaging_machine_id' => 'required|numeric',
            'product_form_id' => 'required|numeric',
            'packing_type_id' => 'required|numeric',
            'packaging_treatment_id' => 'required|numeric',
            'recommendation_engine_id' => 'required|numeric',
            'user_address_id' => 'required|numeric'
        ])->errors();
    }
}
