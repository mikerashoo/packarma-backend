<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\VendorQuotation;
use App\Models\CustomerEnquiry;
use App\Models\MessageNotification;
use Illuminate\Support\Facades\URL;
use Response;

class CustomerQuoteApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 19-05-2022
     * Uses : Display a listing of the Quotations in customer app.
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
                // Request Validation
                $validationErrors = $this->validateRequest($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage($validationErrors->all(), $validationErrors->all());
                }

                $user_id = $token['sub'];
                $page_no = 1;
                $limit = 10;
                $orderByArray = ['vendor_quotations.updated_at' => 'DESC',];
                $defaultSortByName = false;

                if (isset($request->page_no) && !empty($request->page_no)) {
                    $page_no = $request->page_no;
                }
                if (isset($request->limit) && !empty($request->limit)) {
                    $limit = $request->limit;
                }
                $offset = ($page_no - 1) * $limit;

                $data = DB::table('vendor_quotations')->select(
                    'vendor_quotations.id',
                    'vendor_quotations.vendor_id',
                    'vendors.vendor_name',
                    'vendor_quotations.vendor_warehouse_id',
                    'vendor_warehouses.warehouse_name',
                    'vendor_warehouses.city_name',
                    'states.state_name',
                    'vendor_quotations.mrp',
                    'vendor_quotations.freight_amount',
                    'vendor_quotations.delivery_in_days',
                    'vendor_quotations.gst_type',
                    'vendor_quotations.gst_amount',
                    'vendor_quotations.gst_percentage'
                )
                    ->leftjoin('vendors', 'vendor_quotations.vendor_id', '=', 'vendors.id')
                    ->leftjoin('vendor_warehouses', 'vendor_quotations.vendor_warehouse_id', '=', 'vendor_warehouses.id')
                    ->leftjoin('states', 'vendor_warehouses.state_id', '=', 'states.id')
                    ->where([['vendor_quotations.user_id', $user_id], ['vendor_quotations.customer_enquiry_id', $request->customer_enquiry_id]])->whereIn('vendor_quotations.enquiry_status', ['quoted', 'viewed']);

                $quotationData = VendorQuotation::whereRaw("1 = 1");
                if ($request->customer_enquiry_id) {
                    $quotationData = $quotationData->where('vendor_quotations.customer_enquiry_id', $request->customer_enquiry_id);
                    $data = $data->where('vendor_quotations.customer_enquiry_id', $request->customer_enquiry_id);
                }
                if ($request->product_id) {
                    $quotationData = $quotationData->where('vendor_quotations.product_id', $request->product_id);
                    $data = $data->where('vendor_quotations.product_id', $request->product_id);
                }
                if ($request->vendor_quotation_id) {
                    $quotationData = $quotationData->where('vendor_quotations.id', $request->vendor_quotation_id);
                    $data = $data->where('vendor_quotations.id', $request->vendor_quotation_id);
                }
                if ($request->vendor_id) {
                    $quotationData = $quotationData->where('vendor_quotations.vendor_id', $request->vendor_id);
                    $data = $data->where('vendor_quotations.vendor_id', $request->vendor_id);
                }
                if ($request->vendor_warehouse_id) {
                    $quotationData = $quotationData->where('vendor_quotations.vendor_warehouse_id', $request->vendor_warehouse_id);
                    $data = $data->where('vendor_quotations.vendor_warehouse_id', $request->vendor_warehouse_id);
                }
                if (empty($quotationData->first())) {
                    errorMessage(__('customer_quote.quotation_not_found'), $msg_data);
                }
                if (isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search, 'vendor_price');
                }
                if ($defaultSortByName) {
                    $orderByArray = ['products.product_name' => 'ASC'];
                }
                $data = allOrderBy($data, $orderByArray);
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                if (empty($data)) {
                    errorMessage(__('customer_quote.quotation_not_found'), $msg_data);
                }
                $i = 0;
                foreach ($data as $row) {
                    $data[$i]->cgst_amount = "0.00";
                    $data[$i]->sgst_amount = "0.00";
                    $data[$i]->igst_amount = "0.00";
                    if ($row->gst_type == 'cgst+sgst') {
                        $data[$i]->sgst_amount = $data[$i]->cgst_amount = number_format(($data[$i]->gst_amount / 2), 2, '.', '');
                        $data[$i]->gst_percentage = number_format(($row->gst_percentage / 2), 2, '.', '');
                    }
                    if ($row->gst_type == 'igst') {
                        $data[$i]->igst_amount = $data[$i]->gst_amount;
                    }
                    $i++;
                }
                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;
                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Quotation fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Validate request for Customer Quote .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'customer_enquiry_id' => 'required|integer'
        ])->errors();
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 23-05-2022
     * Uses : Display details of the accepted quote in customer  accept quotation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function accept_quotation(Request $request)
    {
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                // Request Validation
                $validationErrors = $this->validateAcceptQuotaion($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage($validationErrors->all(), $validationErrors->all());
                }
                $user_id = $token['sub'];
                if ($request->enquiry_status == "accept") {
                    $statusData = VendorQuotation::where('id', $request->vendor_quotation_id)->first();
                    if ($statusData->enquiry_status == "accept") {
                        errorMessage(__('customer_quote.quotation_already_accepted'), $msg_data);
                    }
                    $quotationEnquiryStatusData = VendorQuotation::find($request->vendor_quotation_id)->update($request->all());
                    // return "quotationEnquiryStatusData";
                    \Log::info("Customer Quotation Accepted Successfully");
                    if ($quotationEnquiryStatusData) {
                        $data = DB::table('vendor_quotations')->select(
                            'vendor_quotations.id',
                            'vendor_quotations.vendor_id',
                            'vendors.vendor_name',
                            'vendor_quotations.vendor_warehouse_id',
                            'vendor_quotations.freight_amount',
                            'vendor_quotations.delivery_in_days',
                            'vendor_warehouses.warehouse_name',
                            'vendor_warehouses.city_name',
                            'states.state_name',
                            'vendor_warehouses.pincode',
                            'vendor_quotations.gst_type',
                            'vendor_quotations.gst_amount',
                            'vendor_quotations.customer_enquiry_id'
                        )
                            ->leftjoin('vendors', 'vendor_quotations.vendor_id', '=', 'vendors.id')
                            ->leftjoin('vendor_warehouses', 'vendor_quotations.vendor_warehouse_id', '=', 'vendor_warehouses.id')
                            ->leftjoin('states', 'vendor_warehouses.state_id', '=', 'states.id')
                            ->where([['vendor_quotations.user_id', $user_id], ['vendor_quotations.id', $request->vendor_quotation_id]]);

                        $autoRejectQuotations = DB::table('vendor_quotations')->where([['vendor_quotations.user_id', $user_id], ['vendor_quotations.customer_enquiry_id', $request->customer_enquiry_id]])
                            ->whereIn('vendor_quotations.enquiry_status', ['quoted', 'viewed'])
                            ->update(['vendor_quotations.enquiry_status' => 'auto_reject']);

                        $customerEnquiryQuoteType = DB::table('customer_enquiries')->where([['customer_enquiries.user_id', $user_id], ['customer_enquiries.id', $request->customer_enquiry_id]])
                            ->update(['customer_enquiries.quote_type' => 'accept_cust']);

                        $data = $data->get()->toArray();
                        $proceed_button = false;
                        $customer_enq_data = CustomerEnquiry::where([['id', $data[0]->customer_enquiry_id], ['quote_type', 'accept_cust']])->get()->count();
                        if ($customer_enq_data) {
                            $data[0]->proceed_button = true;
                        } else {
                            $data[0]->proceed_button = false;
                        }

                        $data[0]->cgst_amount = "0.00";
                        $data[0]->sgst_amount = "0.00";
                        $data[0]->igst_amount = "0.00";
                        if ($data[0]->gst_type == 'cgst+sgst') {
                            $data[0]->sgst_amount = $data[0]->cgst_amount = number_format(($data[0]->gst_amount / 2), 2, '.', '');
                        }
                        if ($data[0]->gst_type == 'igst') {
                            $data[0]->igst_amount = $data[0]->gst_amount;
                        }
                        $responseData['result'] = $data;

                        // send fcm notification to vendor of accepted their quote
                        $can_send_fcm_notification =  DB::table('general_settings')->where('type', 'trigger_vendor_fcm_notification')->value('value');
                        if ($can_send_fcm_notification == 1) {
                            $this->callQuoteAcceptedFcmNotification($data[0]->vendor_id, $data[0]->id);
                        }
                        successMessage(__('success_msg.data_fetched_successfully'), $responseData);
                    }
                }
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Quotation fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /*
    *Created By: Pradyumn, 
    *Created At : 7-sept-2022, 
    *uses: customer accepted quote fcm notification send to vendor
    */
    private function callQuoteAcceptedFcmNotification($vendor_id, $vendor_quotation_id)
    {
        $landingPage = 'EnquiryDetails';
        if ((!empty($vendor_id) && $vendor_id > 0) && (!empty($vendor_quotation_id) && $vendor_quotation_id > 0)) {
            $notificationData = MessageNotification::where([['user_type', 'vendor'], ['notification_name', 'customer_accepted_quotation'], ['status', 1]])->first();

            if (!empty($notificationData)) {
                $notificationData['type_id'] = $vendor_quotation_id;

                if (!empty($notificationData['notification_image']) && file_exists(URL::to('/') . '/storage/app/public/uploads/notification/vendor' . $notificationData['notification_image'])) {
                    $notificationData['image_path'] = getFile($notificationData['notification_image'], 'notification/vendor');
                }

                if (empty($notificationData['page_name'])) {
                    $notificationData['page_name'] = $landingPage;
                }

                $formatted_id = getFormatid($vendor_quotation_id, 'vendor_quotations');
                $notificationData['title'] = str_replace('$$vendor_quotation_id$$', $formatted_id, $notificationData['title']);
                $notificationData['body'] = str_replace('$$vendor_quotation_id$$', $formatted_id, $notificationData['body']);
                $userFcmData = DB::table('vendors')->select('vendors.id', 'vendor_devices.fcm_id')
                    ->where([['vendors.id', $vendor_id], ['vendors.status', 1], ['vendors.fcm_notification', 1], ['vendors.approval_status', 'accepted'], ['vendors.deleted_at', NULL]])
                    ->leftjoin('vendor_devices', 'vendor_devices.vendor_id', '=', 'vendors.id')
                    ->get();
                if (!empty($userFcmData)) {
                    $device_ids = array();
                    foreach ($userFcmData as $key => $val) {
                        array_push($device_ids, $val->fcm_id);
                    }
                    // print_r($userFcmData);exit;
                    sendFcmNotification($device_ids, $notificationData);
                }
            }
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 26-05-2022
     * Uses : Update rejected quotation status in vendor quotation table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reject_quotation(Request $request)
    {
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                // Request Validation
                $validationErrors = $this->validateRejectQuotaion($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage($validationErrors->all(), $validationErrors->all());
                }
                $statusData = VendorQuotation::where('id', $request->vendor_quotation_id)->first();
                if ($statusData->enquiry_status == "reject") {
                    errorMessage(__('customer_quote.quotation_already_rejected'), $msg_data);
                }
                if ($statusData->enquiry_status == 'quoted') {
                    if ($request->enquiry_status == "reject") {
                        $quotationEnquiryStatusData = VendorQuotation::find($request->vendor_quotation_id)->update($request->all());
                        \Log::info("Customer Quotation Rejected Successfully");
                        successMessage(__('customer_quote.quotation_rejected_successfully', $msg_data));
                    }
                }
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Quotation Reject failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 26-05-2022
     * Uses : Showing Accepted Qutation details
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function accepted_quotation_details(Request $request)
    {
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];
                $page_no = 1;
                $limit = 10;

                if (isset($request->page_no) && !empty($request->page_no)) {
                    $page_no = $request->page_no;
                }
                if (isset($request->limit) && !empty($request->limit)) {
                    $limit = $request->limit;
                }
                $offset = ($page_no - 1) * $limit;
                $main_table  = 'vendor_quotations';
                $data = DB::table('vendor_quotations')->select(
                    'vendor_quotations.id',
                    'vendor_quotations.vendor_id',
                    'vendors.vendor_name',
                    'vendor_quotations.mrp',
                    'vendor_quotations.freight_amount',
                    'vendor_quotations.delivery_in_days',
                    'vendor_quotations.vendor_warehouse_id',
                    'vendor_warehouses.warehouse_name',
                    'vendor_warehouses.city_name',
                    'states.state_name',
                    'vendor_quotations.gst_type',
                    'vendor_quotations.gst_amount',
                    'vendor_quotations.gst_percentage',
                    'vendor_quotations.customer_enquiry_id'
                )
                    ->leftjoin('vendors', 'vendor_quotations.vendor_id', '=', 'vendors.id')
                    ->leftjoin('vendor_warehouses', 'vendor_quotations.vendor_warehouse_id', '=', 'vendor_warehouses.id')
                    ->leftjoin('states', 'vendor_warehouses.state_id', '=', 'states.id')
                    ->where([['vendor_quotations.user_id', $user_id], ['vendor_quotations.enquiry_status', 'accept']]);

                $acceptedQuotationData = VendorQuotation::whereRaw("1 = 1");
                if ($request->customer_enquiry_id) {
                    $acceptedQuotationData = $acceptedQuotationData->where($main_table . '' . '.customer_enquiry_id', $request->customer_enquiry_id);
                    $data = $data->where($main_table . '' . '.customer_enquiry_id', $request->customer_enquiry_id);
                }
                if ($request->quotation_id) {
                    $acceptedQuotationData = $acceptedQuotationData->where($main_table . '' . '.id', $request->quotation_id);
                    $data = $data->where($main_table . '' . '.id', $request->quotation_id);
                }
                if ($request->product_id) {
                    $acceptedQuotationData = $acceptedQuotationData->where($main_table . '' . '.product_id', $request->product_id);
                    $data = $data->where($main_table . '' . '.product_id', $request->product_id);
                }
                if ($request->vendor_id) {
                    $acceptedQuotationData = $acceptedQuotationData->where($main_table . '' . '.vendor_id', $request->vendor_id);
                    $data = $data->where($main_table . '' . '.vendor_id', $request->vendor_id);
                }
                if (empty($acceptedQuotationData->first())) {
                    errorMessage(__('customer_quote.quotation_not_found'), $msg_data);
                }
                $total_records = $data->get()->count();
                $data = $data->limit($limit)->offset($offset)->get()->toArray();
                if (empty($data)) {
                    errorMessage(__('customer_quote.quotation_not_found'), $msg_data);
                }

                $i = 0;
                foreach ($data as $row) {
                    $proceed_button = false;
                    $customer_enq_data = CustomerEnquiry::where([['id', $row->customer_enquiry_id], ['quote_type', 'accept_cust']])->first();
                    if ($customer_enq_data->quote_type == 'accept_cust') {
                        $data[$i]->proceed_button = true;
                    } else {
                        $data[$i]->proceed_button = false;
                    }
                    $data[$i]->cgst_amount = "0.00";
                    $data[$i]->sgst_amount = "0.00";
                    $data[$i]->igst_amount = "0.00";
                    if ($row->gst_type == 'cgst+sgst') {
                        $data[$i]->sgst_amount = $data[$i]->cgst_amount = number_format(($data[$i]->gst_amount / 2), 2, '.', '');
                        $data[$i]->gst_percentage = number_format(($row->gst_percentage / 2), 2, '.', '');
                    }
                    if ($row->gst_type == 'igst') {
                        $data[$i]->igst_amount = $data[$i]->gst_amount;
                    }
                    $i++;
                }
                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;
                successMessage(__('success_msg.data_fetched_successfully'), $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Accepted Quotation Details fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 25/05/2022
     * Uses : Validate request for Customer accept Quotation .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateAcceptQuotaion(Request $request)
    {
        return \Validator::make($request->all(), [
            'vendor_quotation_id' => 'required|integer',
            'enquiry_status' => 'required|string',
            'customer_enquiry_id' => 'required|integer'
        ])->errors();
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created on : 26/05/2022
     * Uses : To Validate request for Customer Reject Quotation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateRejectQuotaion(Request $request)
    {
        return \Validator::make($request->all(), [
            'vendor_quotation_id' => 'required|integer',
            'enquiry_status' => 'required|string'
        ])->errors();
    }
}
