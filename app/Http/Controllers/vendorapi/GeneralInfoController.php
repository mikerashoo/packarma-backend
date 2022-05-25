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
                $validationErrors = $this->validateForgotPassword($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage(__('auth.validation_failed'), $validationErrors->all());
                }
                if ($request->info_type) {
                    $type = 'vendor_' . $request->info_type;
                } else {
                    $type = '';
                }
                $data = GeneralSetting::select('value')->where([['type', $type]])->get();
                if (count($data) == 0) {
                    errorMessage(__('general_info.not_found'), $msg_data);
                }
                $msg_data['value'] = $data[0]['value'];
                successMessage(__('general_info.info_fetch'), $msg_data);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
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
