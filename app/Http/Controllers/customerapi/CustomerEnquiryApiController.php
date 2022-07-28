<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\CustomerEnquiry;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\VendorQuotation;
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
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];
                $page_no = 1;
                $limit = 10;
                $orderByArray = ['customer_enquiries.id' => 'DESC',];
                $defaultSortByName = false;

                if (isset($request->page_no) && !empty($request->page_no)) {
                    $page_no = $request->page_no;
                }
                if (isset($request->limit) && !empty($request->limit)) {
                    $limit = $request->limit;
                }
                $offset = ($page_no - 1) * $limit;
                $main_table = 'customer_enquiries';
                $data = DB::table('customer_enquiries')->select(
                    'customer_enquiries.id',
                    'customer_enquiries.category_id',
                    'categories.category_name',
                    'customer_enquiries.sub_category_id',
                    'sub_categories.sub_category_name',
                    'customer_enquiries.product_id',
                    'products.product_name',
                    'customer_enquiries.product_weight',
                    'customer_enquiries.measurement_unit_id',
                    'measurement_units.unit_symbol',
                    'customer_enquiries.shelf_life',
                    'customer_enquiries.storage_condition_id',
                    'storage_conditions.storage_condition_title',
                    'customer_enquiries.packaging_machine_id',
                    'packaging_machines.packaging_machine_name',
                    'customer_enquiries.product_form_id',
                    'product_forms.product_form_name',
                    'customer_enquiries.packing_type_id',
                    'packing_types.packing_name',
                    'customer_enquiries.packaging_treatment_id',
                    'packaging_treatments.packaging_treatment_name',
                    'customer_enquiries.user_address_id',
                    'user_addresses.address',
                    'user_addresses.pincode',
                    'customer_enquiries.recommendation_engine_id',
                    'recommendation_engines.engine_name',
                    'recommendation_engines.structure_type',
                    'recommendation_engines.display_shelf_life',
                    'customer_enquiries.packaging_material_id',
                    'customer_enquiries.quote_type',
                    'customer_enquiries.created_at'
                )
                    ->leftjoin('categories', 'categories.id', '=', 'customer_enquiries.category_id')
                    ->leftjoin('sub_categories', 'sub_categories.id', '=', 'customer_enquiries.sub_category_id')
                    ->leftjoin('products', 'products.id', '=', 'customer_enquiries.product_id')
                    ->leftjoin('measurement_units', 'measurement_units.id', '=', 'customer_enquiries.measurement_unit_id')
                    ->leftjoin('storage_conditions', 'storage_conditions.id', '=', 'customer_enquiries.storage_condition_id')
                    ->leftjoin('packaging_machines', 'packaging_machines.id', '=', 'customer_enquiries.packaging_machine_id')
                    ->leftjoin('product_forms', 'product_forms.id', '=', 'customer_enquiries.product_form_id')
                    ->leftjoin('packing_types', 'packing_types.id', '=', 'customer_enquiries.packing_type_id')
                    ->leftjoin('packaging_treatments', 'packaging_treatments.id', '=', 'customer_enquiries.packaging_treatment_id')
                    ->leftjoin('user_addresses', 'user_addresses.id', '=', 'customer_enquiries.user_address_id')
                    ->leftjoin('recommendation_engines', 'recommendation_engines.id', '=', 'customer_enquiries.recommendation_engine_id')
                    ->where('customer_enquiries.user_id', $user_id)
                    ->whereIn('customer_enquiries.quote_type', ['enquired', 'map_to_vendor', 'accept_cust']);

                $customerEnquiryData = CustomerEnquiry::whereRaw("1 = 1");
                if ($request->enquiry_id) {
                    $customerEnquiryData = $customerEnquiryData->where($main_table . '' . '.id', $request->enquiry_id);
                    $data = $data->where($main_table . '' . '.id', $request->enquiry_id);
                }
                if ($request->product_id) {
                    $customerEnquiryData = $customerEnquiryData->where($main_table . '' . '.product_id', $request->product_id);
                    $data = $data->where($main_table . '' . '.product_id', $request->product_id);
                }
                if (empty($customerEnquiryData->first())) {
                    errorMessage(__('customer_enquiry.customer_enquiry_not_found'), $msg_data);
                }
                if (isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search, 'description');
                }
                if ($defaultSortByName) {
                    $orderByArray = ['products.product_name' => 'ASC'];
                }
                $data = allOrderBy($data, $orderByArray);
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                $i = 0;
                foreach ($data as $row) {
                    $data[$i]->enquiry_id = getFormatid($row->id, 'customer_enquiries');
                    $quotationCount = VendorQuotation::where([['user_id', $user_id], ['customer_enquiry_id', $row->id]])
                        ->whereIn('enquiry_status', ['quoted', 'viewed'])->get()->count();
                    $data[$i]->quotation_count = $quotationCount;
                    $i++;
                }
                if (empty($data)) {
                    errorMessage(__('customer_enquiry.customer_enquiry_not_found'), $msg_data);
                }
                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;
                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
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
        $isSubscribed = true;

        \Log::info("Initiating Customer Enquiry process, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];
                // Request Validation

                $userSubscriptionCheck = User::find($user_id);
                $subscriptionEndDate = $userSubscriptionCheck->subscription_end;
                $todaysDate = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now());

                if (($userSubscriptionCheck->subscription_id == 0) || ($subscriptionEndDate < $todaysDate)) {
                    $isSubscribed = false;
                    $msg_data['is_subscribed'] = $isSubscribed;
                    errorMessage(__('user.no_active_subscription'), $msg_data);
                }


                $validationErrors = $this->validateEnquiry($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage($validationErrors->all(), $validationErrors->all());
                }




                //getting user address details from userAddress and putting value to request to store in cumstomer enquiry table
                $userAddress = UserAddress::find($request->user_address_id);
                $request['country_id'] = $userAddress->country_id;
                $request['state_id'] = $userAddress->state_id;
                $request['city_name'] = $userAddress->city_name;
                // $request['address'] = $userAddress->address;
                $request['flat'] = $userAddress->flat;
                $request['area'] = $userAddress->area;
                $request['land_mark'] = $userAddress->land_mark;
                $request['pincode'] = $userAddress->pincode;
                $request['user_id'] = $user_id;
                // Store a new enquiry
                $enquiryData = CustomerEnquiry::create($request->all());
                $enquiryData->enquiry_id = getFormatid($enquiryData->id, 'customer_enquiries');
                $enquiryData->is_subscribed = $isSubscribed;
                \Log::info("Customer Enquiry Created successfully");
                successMessage(__('customer_enquiry.customer_enquiry_placed_successfully'), $enquiryData->toArray());
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
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
            'category_id' => 'required|numeric',
            'sub_category_id' => 'required|numeric',
            'product_id' => 'required|numeric',
            'shelf_life' => 'required|integer|between:1,10000',
            'product_weight' => 'required|numeric',
            'measurement_unit_id' => 'required|numeric',
            'storage_condition_id' => 'required|numeric',
            'packaging_machine_id' => 'required|numeric',
            'product_form_id' => 'required|numeric',
            'packing_type_id' => 'required|numeric',
            'packaging_treatment_id' => 'required|numeric',
            'recommendation_engine_id' => 'required|numeric',
            'packaging_material_id' => 'required|numeric',
            'user_address_id' => 'required|numeric'
        ])->errors();
    }
}
