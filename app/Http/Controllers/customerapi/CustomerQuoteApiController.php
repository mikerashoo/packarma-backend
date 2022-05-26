<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\VendorQuotation;
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
        try
        {
            $token = readHeaderToken();
            if($token)
            {
                // Request Validation
                $validationErrors = $this->validateRequest($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage(__('auth.validation_failed'), $validationErrors->all());
                }

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

                $data = DB::table('vendor_quotations')->select(
                    'vendor_quotations.id',
                    'vendors.vendor_name',
                    'vendor_warehouses.warehouse_name',
                    'vendor_quotations.mrp',
                )
                    ->leftjoin('vendors', 'vendor_quotations.vendor_id', '=', 'vendors.id')
                    ->leftjoin('vendor_warehouses', 'vendor_quotations.vendor_warehouse_id', '=', 'vendor_warehouses.id')
                    ->where([['vendor_quotations.user_id', $user_id],['vendor_quotations.customer_enquiry_id',$request->customer_enquiry_id]])->whereIn('vendor_quotations.enquiry_status', ['quoted', 'viewed']);

                $quotationData = VendorQuotation::whereRaw("1 = 1");
                if($request->customer_enquiry_id)
                {
                    $quotationData = $quotationData->where('vendor_quotations.customer_enquiry_id',$request->customer_enquiry_id);
                    $data = $data->where('vendor_quotations.customer_enquiry_id',$request->customer_enquiry_id);
                }
                if($request->product_id)
                {
                    $quotationData = $quotationData->where('vendor_quotations.product_id',$request->product_id);
                    $data = $data->where('vendor_quotations.product_id',$request->product_id);
                }
                if($request->vendor_id)
                {
                    $quotationData = $quotationData->where('vendor_quotations.vendor_id',$request->vendor_id);
                    $data = $data->where('vendor_quotations.vendor_id',$request->vendor_id);
                }
                if($request->vendor_warehouse_id)
                {
                    $quotationData = $quotationData->where('vendor_quotations.vendor_warehouse_id',$request->vendor_warehouse_id);
                    $data = $data->where('vendor_quotations.vendor_warehouse_id',$request->vendor_warehouse_id);
                }
                if(empty($quotationData->first()))
                {
                    errorMessage(__('customer_quote.quotation_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'vendor_price');
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
    public function accepted_quote_details(Request $request)
    {
        $msg_data = array();
        try
        {
            $token = readHeaderToken();
            if($token)
            {
                // Request Validation
                $validationErrors = $this->validateAcceptQuotaion($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage(__('auth.validation_failed'), $validationErrors->all());
                }

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

                $data = DB::table('vendor_quotations')->select(
                    'vendor_quotations.id',
                    'vendors.vendor_name',
                    'vendor_warehouses.warehouse_name',
                    'vendor_quotations.product_quantity',
                    'vendor_quotations.mrp',
                    'vendor_quotations.gst_amount',
                    'vendor_quotations.total_amount'
                )
                    ->leftjoin('vendors', 'vendor_quotations.vendor_id', '=', 'vendors.id')
                    ->leftjoin('vendor_warehouses', 'vendor_quotations.vendor_warehouse_id', '=', 'vendor_warehouses.id')
                    ->where([['vendor_quotations.user_id', $user_id],['vendor_quotations.customer_enquiry_id',$request->customer_enquiry_id],['vendor_quotations.id',$request->vendor_quotation_id]]);

                $quotationData = VendorQuotation::whereRaw("1 = 1");
                if($request->vendor_quotation_id)
                {
                    $quotationData = $quotationData->where('vendor_quotations.id',$request->vendor_quotation_id);
                    $data = $data->where('vendor_quotations.id',$request->vendor_quotation_id);
                }
                if($request->customer_enquiry_id)
                {
                    $quotationData = $quotationData->where('vendor_quotations.customer_enquiry_id',$request->customer_enquiry_id);
                    $data = $data->where('vendor_quotations.customer_enquiry_id',$request->customer_enquiry_id);
                }
                if($request->product_id)
                {
                    $quotationData = $quotationData->where('vendor_quotations.product_id',$request->product_id);
                    $data = $data->where('vendor_quotations.product_id',$request->product_id);
                }
                if($request->vendor_id)
                {
                    $quotationData = $quotationData->where('vendor_quotations.vendor_id',$request->vendor_id);
                    $data = $data->where('vendor_quotations.vendor_id',$request->vendor_id);
                }
                if($request->vendor_warehouse_id)
                {
                    $quotationData = $quotationData->where('vendor_quotations.vendor_warehouse_id',$request->vendor_warehouse_id);
                    $data = $data->where('vendor_quotations.vendor_warehouse_id',$request->vendor_warehouse_id);
                }
                if(empty($quotationData->first()))
                {
                    errorMessage(__('customer_quote.quotation_not_found'), $msg_data);
                }
                if(isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search,'vendor_price');
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
    private function validateAcceptQuotaion(Request $request)
    {
        return \Validator::make($request->all(), [
            'customer_enquiry_id' => 'required|integer',
            'vendor_quotation_id' => 'required|integer',
        ])->errors();
    }
}