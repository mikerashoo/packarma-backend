<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Otp;
use Carbon\Carbon;

class ForgotPasswordApiController extends Controller
{
    /**
     * This API will be used to forgot user password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        \Log::info("Forgot Password process, starting at: " . Carbon::now()->format('H:i:s:u'));
        try
        {
            // Request Validation
            $validationErrors = $this->validateForgotPassword($request);
            if (count($validationErrors)) {
                \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                errorMessage($validationErrors->all(), $validationErrors->all());
            }
            $userData = User::where('phone',$request->phone)->first();
            // verify mobile number
            if($userData->fpwd_flag != 'Y')
            {
                errorMessage(__('auth.verify_mobile_to_reset_password'), $msg_data);
            }
            //cheching otp in otp table
            $checkOtp = Otp::where('otp_code',$request->otp_code)
                            ->where('workflow',$request->workflow)
                            ->where('mobile_no',$request->phone)
                            ->where('verify_count','<=',3)
                            ->first();
            if(!empty($checkOtp))
            {
                if(Carbon::now() > $checkOtp->expiry_time)
                {
                    errorMessage(__('auth.otp_expired'), $msg_data);
                }
            }
            else
            {
                errorMessage(__('auth.invalid_otp'), $msg_data);
            }
            if($request->new_password != $request->confirm_password) {
                errorMessage(__('passwords.password_mismatch'), $msg_data);
            }
            $updateUserData['password'] = md5($userData->email.$request->new_password);
            $updateUserData['fpwd_flag'] = 'N';
            User::where('phone',$request->phone)->update($updateUserData);
            successMessage(__('passwords.reset'), $updateUserData);
        }
        catch(\Exception $e)
        {
            \Log::error("Forgot Password failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Validate request for forgot password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    private function validateForgotPassword(Request $request)
    {
        return \Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:10',
            'workflow' => 'required',
            'otp_code' => 'required',
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8',
        ])->errors();
    }
}
