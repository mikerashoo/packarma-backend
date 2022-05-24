<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CustomerDevice;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Session;

class ChangePasswordApiController extends Controller
{
    /**
     * Created By : pradyumn Dwivedi
     * Created at : 23/05/2022
     * Uses : This API will be used to forgot customer password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        \Log::info("Change Password process, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            $token = readHeaderToken();

            if ($token) {
                $user_id = $token['sub'];
                // Request Validation
                $validationErrors = $this->validateForgotPassword($request);
                if (count($validationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                    errorMessage(__('auth.validation_failed'), $validationErrors->all());
                }
                $userData = User::where('id', $user_id)->first();
                if ($userData->password != md5($userData->email . $request->current_password)) {
                    errorMessage(__('change_password.password_not_match'), $msg_data);
                }

                if ($userData->password == md5($userData->email . $request->new_password)) {
                    errorMessage(__('change_password.new_password_cannot_same_current_password'), $msg_data);
                }

                if ($request->new_password != $request->confirm_password) {
                    errorMessage(__('change_password.password_mismatch'), $msg_data);
                }

                $imei_no = $request->header('imei-no');
                $new_password = md5($userData->email . $request->new_password);
                User::where('id', $user_id)->update(['password' => $new_password]);

                CustomerDevice::where([['user_id', $user_id], ['imei_no', '!=', $imei_no]])->update(['remember_token' => NULL]);
                successMessage(__('change_password.changed'), $msg_data);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Change Password failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 23/05/2022
     * Uses : Validate request for forgot password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateForgotPassword(Request $request)
    {
        return \Validator::make($request->all(), [
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8',
        ])->errors();
    }
}
