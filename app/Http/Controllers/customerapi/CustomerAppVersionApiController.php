<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Carbon\Carbon;
use Response;

class CustomerAppVersionApiController extends Controller
{
     /**
     * Created By : Pradyumn Dwivedi
     * Created at : 23/05/2022
     * Uses : Check Version of Customer App
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
            $platform = $request->header('platform');
            $version = $request->header('version');
            if ($platform == 'android') {
                $dbVersionData = GeneralSetting::select('value')->where([['type', 'customer_android_version']])->first();
                $update_url = GeneralSetting::select('value')->where([['type', 'customer_android_url']])->first();
            } elseif ($platform == 'ios') {
                $dbVersionData = GeneralSetting::select('value')->where([['type', 'customer_ios_version']])->first();
                $update_url = GeneralSetting::select('value')->where([['type', 'customer_ios_url']])->first();
            } elseif ($platform == 'web') {
                $dbVersionData = GeneralSetting::select('value')->where([['type', 'customer_web_version']])->first();
                $update_url = GeneralSetting::select('value')->where([['type', 'customer_web_url']])->first();
            }
            $dbversion = json_decode($dbVersionData->value, true);

            if (!in_array($version, $dbversion)) {
                $msg_data['update_url'] = $update_url->value;
                errorMessage(__('customer_version.update_app'), $msg_data);
            }
            successMessage(__('vendor_version.app_ok'), $vendor_msg_data);
        } catch (\Exception $e) {
            \Log::error("Version Check failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $vendor_msg_data);
        }
    }
}
