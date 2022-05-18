<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\VendorOtp;
use Carbon\Carbon;

class ForgotPasswordApiController extends Controller
{
    /**
     * This API will be used to forgot vendor password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        \Log::info("Forgot Password process, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            $verify_count = 3;

            // Request Validation
            $validationErrors = $this->validateForgotPassword($request);
            if (count($validationErrors)) {
                \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                errorMessage(__('auth.validation_failed'), $validationErrors->all());
            }
            $vendorData = Vendor::where('phone', $request->phone)->first();
            // verify mobile number
            if ($vendorData->fpwd_flag != 'Y') {
                errorMessage(__('auth.verify_mobile_to_reset_password'), $msg_data);
            }
            //cheching otp in otp table
            $checkVendorOtp = VendorOtp::where('otp_code', $request->otp_code)
                ->where('workflow', $request->workflow)
                ->where('mobile_no', $request->phone)
                ->where('verify_count', '<=', $verify_count)
                ->first();
            if (!empty($checkVendorOtp)) {
                if (Carbon::now() > $checkVendorOtp->expiry_time) {
                    errorMessage(__('auth.otp_expired'), $msg_data);
                }
            } else {
                errorMessage(__('auth.invalid_otp'), $msg_data);
            }
            if ($request->new_password != $request->confirm_password) {
                errorMessage(__('passwords.password_mismatch'), $msg_data);
            }
            $updateVendorData['vendor_password'] = md5($vendorData->vendor_email . $request->new_password);
            $updateVendorData['fpwd_flag'] = 'N';
            Vendor::where('phone', $request->phone)->update($updateVendorData);
            successMessage(__('passwords.reset'), $updateVendorData);
        } catch (\Exception $e) {
            \Log::error("Forgot Password failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Validate request for forgot password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateForgotPassword(Request $request)
    {
        return \Validator::make($request->all(), [
            'phone' => 'required|numeric',
            'workflow' => 'required',
            'otp_code' => 'required',
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8',
        ])->errors();
    }
}
