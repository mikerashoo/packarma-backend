<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterAddressController extends Controller
{
    /**
     * To Register Vendor Adress.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        try {
            $vendor_token = readVendorHeaderToken();
            if ($vendor_token) {
                $vendor_id = $vendor_token['sub'];












                successMessage(__('success_msg.data_fetched_successfully'), $msg_data);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Quotation fetching failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Validate request for vendor address registeration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateSignup(Request $request)
    {
        return \Validator::make($request->all(), [
            'vendor_name' => 'required|string',
            'vendor_company_name' => 'required|string',
            'phone_country_id' => 'required|numeric',
            'phone' => 'required|numeric',
            'vendor_email' => 'required|email',
            'vendor_password' => 'required|string|min:8'

        ])->errors();
    }
}
