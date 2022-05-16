<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorQuotation;
use Response;

class EnquiryApiController extends Controller
{
    /**
     * Display a listing of the Enquiry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        try {
            // vendor token
            $vendor_token = readVendorHeaderToken();
            if ($vendor_token) {
                // print_r($vendor_token);
                // die();
                $vendor_id = $vendor_token['sub'];
                $page_no = 1;
                $limit = 10;

                if (isset($request->page_no) && !empty($request->page_no)) {
                    $page_no = $request->page_no;
                }
                if (isset($request->limit) && !empty($request->limit)) {
                    $limit = $request->limit;
                }
                $offset = ($page_no - 1) * $limit;

                $data = VendorQuotation::with('product', 'vendor', 'Enquiry')->where([['vendor_id', $vendor_id], ['enquiry_status', 'mapped']]);

                $enquiryData = VendorQuotation::whereRaw("1 = 1");

                if ($request->product_id) {
                    $enquiryData = $enquiryData->where('product_id', $request->product_id);
                    $data = $data->where('product_id', $request->product_id);
                }
                if (empty($enquiryData->first())) {
                    errorMessage(__('enquiry.enquiry_not_found'), $msg_data);
                }

                // if($request->id)
                // {
                //     $data = $data->where('id',$request->id);
                // }

                if (isset($request->search) && !empty($request->search)) {
                    $data = fullSearchQuery($data, $request->search, 'vendor_price|commission_amt');
                }

                $total_records = $data->get()->count();

                $data = $data->limit($limit)->offset($offset)->get()->toArray();

                $i = 0;
                // foreach ($data as $row) {
                //     $data[$i]['product_image'] = getFile($row['product_image'], 'product');
                //     $data[$i]['product_thumb_image'] = getFile($row['product_thumb_image'], 'product', false, 'thumb');
                //     $i++;
                // }
                $responseData['result'] = $data;
                $responseData['total_records'] = $total_records;
                successMessage('data_fetched_successfully', $responseData);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Enquiry fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
