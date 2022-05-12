<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Vendor;
use App\Models\VendorOtp;
use Carbon\Carbon;
use Response;

class OtpApiController extends Controller
{
    /**
     * This API will be used to get OTP.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function requestOtp(Request $request)
    {
        $msg_data = array();
        try {
            \Log::info("OTP sending process starts, starting at: " . Carbon::now()->format('H:i:s:u'));
            // Request Validation
            $vendorValidationErrors = $this->validateRequestOtp($request);
            if (count($vendorValidationErrors)) {
                \Log::error("Auth Exception: " . implode(", ", $vendorValidationErrors->all()));
                errorMessage(__('auth.validation_failed'), $vendorValidationErrors->all());
            }
            $vendor_otp_data = array();
            $vendor_otp_data['otp_code'] = $otp = generateRandomOTP();
            $vendor_otp_data['expiry_time'] = Carbon::now()->addMinutes(5);
            $vendor_otp_data['workflow'] = $request->workflow;
            $checkOtp = VendorOtp::where('mobile_no', $request->phone)
                ->where('workflow', $request->workflow)
                ->first();
            $vendor_otp_data['mobile_no'] = $vendor_otp_data['mobile_no_with_code'] = $request->phone;
            if (empty($checkOtp)) {
                $vendor_otp_data['verify_count'] = "1";
                \Log::info("OTP saved.");
                VendorOtp::create($vendor_otp_data);
            } else {
                $last_count = $checkOtp->verify_count;
                $last_hitting_time = $checkOtp->updated_at->toDateTimeString();
                $next_1_hour_time = (strtotime("$last_hitting_time +  1 hour"));
                $current_time =  time();
                $new_count = 0;
                if ($current_time > $next_1_hour_time || $last_count < 3) {
                    $new_count = $last_count + 1;
                    if ($new_count > 3) {
                        $new_count = 1;
                    }
                }
                // ************************Commented for testing only************************
                // else
                // {
                //     \Log::info("OTP sending limit reached");
                //     errorMessage(__('auth.number_blocked'), $msg_data);
                // }
                $vendor_otp_data['verify_count'] = $new_count;
                \Log::info("OTP updated.");
                VendorOtp::find($checkOtp->id)->update($vendor_otp_data);
            }
            Vendor::where('phone', $request->phone)->update(array("fpwd_flag" => "Y"));
            \Log::info("OTP sent successfully to mobile number: " . $request->phone);
            successMessage(__('vendor.otp_sent'), $msg_data);
        } catch (\Exception $e) {
            \Log::error("OTP sending failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * This API will be used to verify OTP.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyOtp(Request $request)
    {
        $msg_data = array();
        try {
            \Log::info("OTP verification process starts, starting at: " . Carbon::now()->format('H:i:s:u'));
            // Request Validation
            $vendorValidationErrors = $this->validateVerifyOtp($request);
            if (count($vendorValidationErrors)) {
                \Log::error("Auth Exception: " . implode(", ", $vendorValidationErrors->all()));
                errorMessage(__('auth.validation_failed'), $vendorValidationErrors->all());
            }

            $checkOtpVendor = Vendor::where('phone', $request->phone)->first();

            if ($checkOtpVendor->fpwd_flag != 'Y') {
                errorMessage(__('auth.invalid_otp'), $msg_data);
            }

            $checkOtp = VendorOtp::where('otp_code', $request->otp_code)
                ->where('workflow', $request->workflow)
                ->where('mobile_no', $request->phone)
                ->where('verify_count', '<=', 3)
                ->first();
            if (!empty($checkOtp)) {
                if (Carbon::now() > $checkOtp->expiry_time) {
                    errorMessage(__('auth.otp_expired'), $msg_data);
                }
            } else {
                errorMessage(__('auth.invalid_otp'), $msg_data);
            }
            if ($request->workflow == 'register') {
                $updateVendorData['fpwd_flag'] = 'N';
                $updateVendorData['is_verified'] = 'Y';
                Vendor::where('phone', $request->phone)->update($updateVendorData);
            }
            successMessage(__('auth.otp_verified'), $msg_data);
        } catch (\Exception $e) {
            \Log::error("OTP verification failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Validate request parameters for request otp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateRequestOtp(Request $request)
    {
        return \Validator::make($request->all(), [
            'phone' =>  [
                'required',
                'numeric',
                Rule::exists('vendors', 'phone')->where('phone', $request->phone)
            ],
            'workflow' => 'required'
        ])->errors();
    }

    /**
     * Validate request parameters for verify otp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateVerifyOtp(Request $request)
    {
        return \Validator::make($request->all(), [
            'phone' =>  [
                'required',
                'numeric',
                Rule::exists('vendors', 'phone')->where('phone', $request->phone)
            ],
            'workflow' => 'required',
            'otp_code' => 'required'
        ])->errors();
    }
}
