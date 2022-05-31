<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Response;

class CustomerVersionControlApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 30/05/2022
     * Uses : To check customer phone version 
     * 
     * Check Version of App.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customer_msg_data = array();
        $dbVersionData = array();
        \Log::info("Initiating Version check, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            \Log::info("Version Check started!");
            // Password Creation
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
                $customer_msg_data['update_url'] = $update_url->value;
                errorMessage(__('customer_version.update_app'), $customer_msg_data);
            }
            successMessage(__('customer_version.app_ok'), $customer_msg_data);
        } catch (\Exception $e) {
            \Log::error("Customer Version Check failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $customer_msg_data);
        }
    }
}
