<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use Carbon\Carbon;

class CustomerGeneralInfoApiController extends Controller
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 30/05/2022
     * Uses : To get General Info for customer app.
     * 
     * This API will be used to get General Info .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        \Log::info("Fetch Customer General Info process, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];
                if ($request->info_type) {
                    $type = array($request->info_type);
                } else {
                    $type = array('about_us', 'terms_condition', 'privacy_policy', 'help_and_support');
                }
                $data = GeneralSetting::select('type', 'value')->whereIn('type', $type)->get();
                if (count($data) == 0) {
                    errorMessage(__('customer_general_info.not_found'), $msg_data);
                }
                successMessage(__('customer_general_info.info_fetch'), $data);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Fetching Customer Info failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }


    /**
     * This API will be used to get General Info without login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customerGeneralInforAll(Request $request)
    {
        $msg_data = array();
        \Log::info("Fetch General Info process, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {

            if ($request->info_type) {
                $type = array($request->info_type);
            } else {
                $type = array('about_us', 'terms_condition', 'privacy_policy', 'help_and_support');
            }
            $data = GeneralSetting::select('type', 'value')->whereIn('type', $type)->get();
            if (count($data) == 0) {
                errorMessage(__('customer_general_info.not_found'), $msg_data);
            }
            successMessage(__('customer_general_info.info_fetch'), $data);
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
