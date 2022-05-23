<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GeneralInfoController extends Controller
{
    /**
     * This API will be used to get General Info .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        \Log::info("Fetch General Info process, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            $vendor_token = readVendorHeaderToken();

            if ($vendor_token) {
                $vendor_id = $vendor_token['sub'];
                successMessage(__('change_password.changed'), $msg_data);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Change Password failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }
}
