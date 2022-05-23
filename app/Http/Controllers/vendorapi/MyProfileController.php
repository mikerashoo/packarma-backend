<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MyProfileController extends Controller
{
    /**
     * Use to show Vendor My profile.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $msg_data = array();
        \Log::info("My Profile Show, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            $vendor_token = readVendorHeaderToken();
            if ($vendor_token) {
                $vendor_id = $vendor_token['sub'];


                $data = Vendor::select('vendor_name', 'vendor_email', 'phone', 'whatsapp_no', 'phone_country_id', 'whatsapp_country_id')
                    ->with(['phone_country' => function ($query) {
                        $query->select('id', 'country_name', 'phone_code');
                    }])
                    ->with(['whatsapp_country' => function ($query) {
                        $query->select('id', 'country_name', 'phone_code');
                    }])
                    ->where([['id', $vendor_id]])->first();
                if (empty($data)) {
                    errorMessage(__('my_profile.not_found'), $msg_data);
                }

                $flags = array(
                    "my_address" => true,
                    "change_password" => true,
                    "about_us" => true,
                    "help_and_support" => true,
                    "terms_and_condition" => true,
                    "privacy_policy" => true,
                    "edit_vendor" => true,
                );

                $msg_data['result'] = $data;
                $msg_data['flags'] = $flags;

                successMessage(__('my_profile.info_fetch'), $msg_data);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Fetching Info failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }



    /**
     * Use to update vendor My profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $msg_data = array();
        \Log::info("My Profile Update, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            $vendor_token = readVendorHeaderToken();
            if ($vendor_token) {
                $vendor_id = $vendor_token['sub'];
                $vendorValidationErrors = $this->validateSignup($request);
                if (count($vendorValidationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $vendorValidationErrors->all()));
                    errorMessage(__('auth.validation_failed'), $vendorValidationErrors->all());
                }
                \Log::info("Vendor Update Start!");
                $checkVendor = Vendor::where([['phone', $request->phone], ['id', '!=', $vendor_id]])->first();
                if (!empty($checkVendor)) {
                    errorMessage(__('vendor.same_phone_exist'), $msg_data);
                }


                $checkVendor = Vendor::where('id', $vendor_id)->first();
                $checkVendor->update($request->all());
                $vendorData = $checkVendor;
                $vendor = $vendorData->toArray();

                $vendorData->created_at->toDateTimeString();
                $vendorData->updated_at->toDateTimeString();
                \Log::info("Existing vendor updated with email id: " . $request->vendor_email . " and phone number: " . $request->phone);
                successMessage(__('vendor.update_successfully'), $vendor);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("My Profile Update failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }


    private function validateSignup(Request $request)
    {
        return \Validator::make($request->all(), [
            'vendor_name' => 'required|string',
            'vendor_company_name' => 'required|string',
            'phone_country_id' => 'required|numeric',
            'phone' => 'required|numeric',
            'vendor_email' => 'required|email',

        ])->errors();
    }
}
