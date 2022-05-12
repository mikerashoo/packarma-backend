<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Session;
use Response;

class LoginApiController extends Controller
{
    /**
     * This API will be used to login user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        \Log::info("Logging in vendor, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            // Request Validation
            $validationErrors = $this->validateLogin($request);
            if (count($validationErrors)) {
                \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                errorMessage(__('auth.validation_failed'), $validationErrors->all());
            }
            $vendorData = Vendor::where([['vendor_email', $request->vendor_email], ['vendor_password', md5($request->vendor_email . $request->vendor_password)], ['status', '1'], ['is_verified', 'Y']])->first();
            if (empty($vendorData)) {
                errorMessage(__('vendor.login_failed'), $msg_data);
            }

            $vendor_token = JWTAuth::fromUser($vendorData);
            $vendors = Vendor::find($vendorData->id);
            $vendorData->last_login = $vendors->last_login = Carbon::now();
            $vendorData->remember_token = $vendors->remember_token = $vendor_token;
            $vendors->save();
            successMessage(__('vendor.logged_in_successfully'), $vendorData->toArray());
        } catch (\Exception $e) {
            \Log::error("Login failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Validate request for login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateLogin(Request $request)
    {
        return \Validator::make($request->all(), [
            'vendor_email' => 'required|email',
            'vendor_password' => 'required|string|min:8'
        ])->errors();
    }
}
