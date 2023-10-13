<?php

/**
 * Created By :Ankita Singh
 * Created On : 12 Apr 2022
 * Uses : This controller will be used to login user.
 */

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\CustomerDevice;
use Illuminate\Support\Facades\DB;
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
        $default_home_page = 'home';
        \Log::info("Logging in user, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            // Request Validation
            $validationErrors = $this->validateLogin($request);
            if (count($validationErrors)) {
                \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                errorMessage($validationErrors->all(), $validationErrors->all());
            }
            $userData = User::with(['currency' => function ($query) {
                $query->select('id', 'currency_name', 'currency_symbol', 'currency_code');
            }])->with(['phone_country' => function ($query) {
                $query->select('id', 'phone_code', 'country_name');
            }])->where([['email', strtolower($request->email)], ['password', md5(strtolower($request->email) . $request->password)], ['is_verified', 'Y']])->first(); //, ['status', '1'], ['deleted_at', NULL]

            if (empty($userData)) {
                errorMessage(__('user.login_failed'), $msg_data);
            }
            if ($userData->approval_status == 'rejected') {
                errorMessage(__('user.rejected'), $msg_data);
            }
            if ($userData->approval_status == 'pending') {
                if (empty($userData->gstin)) {
                    $default_home_page = 'gst';
                } else {
                    errorMessage(__('user.approval_pending'), $msg_data);
                }
            }
            if ($userData->status == 0 && $userData->approval_status == 'accepted') {
                errorMessage(__('user.not_active'), $msg_data);
            }

            if (empty($userData->gst_certificate)) {
                $userData->gst_certificate =  getFile('default_user_gst_file.png', 'gst_certificate');
            }
            
            $fcm_id = NULL;
            if ($request->fcm_id && !empty($request->fcm_id)) {
                $fcm_id = $request->fcm_id;
            }

            $notification_icon_flag = true;
            $imei_no = $request->header('imei-no');
            $token = JWTAuth::fromUser($userData);
            $users = User::find($userData->id);
            $userData->last_login = $users->last_login = Carbon::now();
            $userData->remember_token = $token;
            $userData->load_page = $default_home_page;
            $userData->notification_icon = $notification_icon_flag;
            $users->save();
            CustomerDevice::updateOrCreate(
                ['user_id' => $userData->id, 'imei_no' => $imei_no],
                ['remember_token' => $token, 'fcm_id' => $fcm_id]
            );
            successMessage(__('user.logged_in_successfully'), $userData->toArray());
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
            'email' => 'required|email',
            'password' => 'required|string'
        ])->errors();
    }
}
