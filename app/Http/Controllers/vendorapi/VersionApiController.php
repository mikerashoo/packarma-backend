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
            $server = $request->header('server');
            if ($server == 'P') {
                $platform = $request->header('platform');
                $version = $request->header('version');
                if ($platform == 'android') {
                    $dbVersionData = GeneralSetting::select('value')->where([['type', 'vendor_android_version']])->first();
                    $update_url = GeneralSetting::select('value')->where([['type', 'vendor_android_url']])->first();
                } elseif ($platform == 'ios') {
                    $dbVersionData = GeneralSetting::select('value')->where([['type', 'vendor_ios_version']])->first();
                    $update_url = GeneralSetting::select('value')->where([['type', 'vendor_ios_url']])->first();
                } elseif ($platform == 'web') {
                    $dbVersionData = GeneralSetting::select('value')->where([['type', 'vendor_web_version']])->first();
                    $update_url = GeneralSetting::select('value')->where([['type', 'vendor_web_url']])->first();
                }
                $dbversion = json_decode($dbVersionData->value, true);
                if (!in_array($version, $dbversion)) {
                    $vendor_msg_data['update_url'] = $update_url->value;
                    errorMessage(__('vendor_version.update_app'), $vendor_msg_data);
                }
            }
            successMessage(__('vendor_version.app_ok'), $vendor_msg_data);
        } catch (\Exception $e) {
            \Log::error("Version Check failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $vendor_msg_data);
        }
    }
}
