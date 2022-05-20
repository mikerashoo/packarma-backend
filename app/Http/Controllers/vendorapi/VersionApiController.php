<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Carbon\Carbon;
use Response;

class VersionApiController extends Controller
{
    /**
     * Check Version of App.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vendor_msg_data = array();
        $dbVersionData = array();
        \Log::info("Initiating Version check, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {

            \Log::info("Version Check started!");
            // Password Creation
            $platform = $request->header('platform');
            $version = $request->header('version');
            if ($platform == 'android' && $platform != 'ios' && $platform != 'web') {
                $dbVersionData = GeneralSetting::select('value')->where([['type', 'vendor_android_version']])->get()->toArray();
            } elseif ($platform == 'ios' && $platform != 'android' && $platform != 'web') {
                $dbVersionData = GeneralSetting::select('value')->where([['type', 'vendor_ios_version']])->get()->toArray();
            } elseif ($platform == 'web' && $platform != 'android' && $platform != 'ios') {
                $dbVersionData = GeneralSetting::select('value')->where([['type', 'vendor_web_version']])->get()->toArray();
            }
            print_r($dbVersionData);
            die();

            if (!in_array($version, $dbVersionData)) {
                errorMessage(__('vendor_version.phone_already_exist'), $vendor_msg_data);
            } else {
                echo json_encode(array('success' => "1"));
                exit();
            }

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
}
