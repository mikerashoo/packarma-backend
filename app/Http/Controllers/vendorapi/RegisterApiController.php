<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Carbon\Carbon;
use Response;

class RegisterApiController extends Controller
{
    /**
     * Register a new Vendor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vendor_msg_data = array();
        \Log::info("Initiating registeration process, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            // Request Validation
            $vendorValidationErrors = $this->validateSignup($request);
            if (count($vendorValidationErrors)) {
                \Log::error("Auth Exception: " . implode(", ", $vendorValidationErrors->all()));
                errorMessage(__('auth.validation_failed'), $vendorValidationErrors->all());
            }
            \Log::info("Vendor creation started!");
            // Password Creation
            $vendor_password = md5(strtolower($request->vendor_email) . $request->vendor_password);
            unset($request->vendor_password);
            $request['vendor_password'] = $vendor_password;

            $checkVendor = Vendor::where('phone', $request->phone)->first();
            if (empty($checkVendor)) {
                // Store a new vendor
                $vendorData = Vendor::create($request->all());
                \Log::info("Vendor registered successfully with email id: " . $request->vendor_email . " and phone number: " . $request->phone);
            } else {
                if ($checkVendor->is_verified == 'Y') {
                    errorMessage(__('vendor.phone_already_exist'), $vendor_msg_data);
                }
                // Update existing vendor
                $checkVendor->update($request->all());
                $vendorData = $checkVendor;
                \Log::info("Existing vendor updated with email id: " . $request->vendor_email . " and phone number: " . $request->phone);
            }
            $vendor = $vendorData->toArray();
            $vendorData->created_at->toDateTimeString();
            $vendorData->updated_at->toDateTimeString();
            // $input = array();

            // if (!empty($input)) {
            //     Vendor::find($vendor['id'])->update($input);
            // }
            successMessage(__('vendor.registered_successfully'), $vendor);
        } catch (\Exception $e) {
            \Log::error("Registeration failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $vendor_msg_data);
        }
    }

    /**
     * Validate request for registeration.
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
            'phone' => 'required|numeric|digits:10',
            'vendor_email' => 'required|email|unique:vendors,deleted_at,NULL',
            'vendor_password' => 'required|string|min:8'

        ])->errors();
    }
}
