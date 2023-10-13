<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
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

                if ($request->info_type) {
                    $type = array($request->info_type);
                } else {
                    $type = array('vendor_about_us', 'vendor_terms_condition', 'vendor_privacy_policy', 'vendor_help_and_support');
                }
                $data = GeneralSetting::select('type', 'value')->whereIn('type', $type)->get();
                if (count($data) == 0) {
                    errorMessage(__('general_info.not_found'), $msg_data);
                }
                // print_r($data[0]);
                // die;

                successMessage(__('general_info.info_fetch'), $data);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Fetching Info failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * This API will be used to get General Info without login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generalInforAll(Request $request)
    {
        $msg_data = array();
        \Log::info("Fetch General Info process, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {

            if ($request->info_type) {
                $type = array($request->info_type);
            } else {
                $type = array('vendor_about_us', 'vendor_terms_condition', 'vendor_privacy_policy', 'vendor_help_and_support');
            }
            $data = GeneralSetting::select('type', 'value')->whereIn('type', $type)->get();
            if (count($data) == 0) {
                errorMessage(__('general_info.not_found'), $msg_data);
            }
            successMessage(__('general_info.info_fetch'), $data);
        } catch (\Exception $e) {
            \Log::error("Fetching Info failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }




    /**
     * Validate request for General Info.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateForgotPassword(Request $request)
    {
        return \Validator::make($request->all(), [
            'info_type' => 'required',
        ])->errors();
    }
}
