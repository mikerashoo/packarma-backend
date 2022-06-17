<?php
/**
 * Created By :Ankita Singh
 * Created On : 12 Apr 2022
 * Uses : This controller will be used to send and verify OTP.
*/
namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Otp;
use Carbon\Carbon;
use Response;

class OtpApiController extends Controller
{
    /**
     * This API will be used to get OTP.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function requestOtp(Request $request)
    {
        $msg_data = array();
        try
        {
            \Log::info("OTP sending process starts, starting at: " . Carbon::now()->format('H:i:s:u'));
            // Request Validation
            $validationErrors = $this->validateRequestOtp($request);
            if (count($validationErrors)) {
                \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                errorMessage($validationErrors->all(), $validationErrors->all());
            }
            $otp_data = array();
            $otp_data['otp_code'] = $otp = generateRandomOTP();
            $otp_data['expiry_time'] = Carbon::now()->addMinutes(5);
            $otp_data['workflow'] = $request->workflow;
            $checkOtp = Otp::where('mobile_no',$request->phone)
                            ->where('workflow',$request->workflow)
                            ->first();
            $otp_data['mobile_no'] = $otp_data['mobile_no_with_code'] = $request->phone;
            if(empty($checkOtp))
            {
                $otp_data['verify_count'] = "1";
                \Log::info("OTP saved.");
                Otp::create($otp_data);
            }
            else
            {
                $last_count = $checkOtp->verify_count;
                $last_hitting_time = $checkOtp->updated_at->toDateTimeString();
                $next_1_hour_time = (strtotime("$last_hitting_time +  1 hour"));
                $current_time =  time();
                $new_count = 0;
                if($current_time > $next_1_hour_time || $last_count < 3)
                {
                    $new_count = $last_count+1;
                    if($new_count > 3)
                    {
                        $new_count=1;
                    }
                }
                // ************************Commented for testing only************************
                // else
                // {
                //     \Log::info("OTP sending limit reached");
                //     errorMessage(__('auth.number_blocked'), $msg_data);
                // }
                $otp_data['verify_count'] = $new_count;
                \Log::info("OTP updated.");
                Otp::find($checkOtp->id)->update($otp_data);
            }
            User::where('phone',$request->phone)->update(array("fpwd_flag" => "Y"));
            \Log::info("OTP sent successfully to mobile number: ".$request->phone);
            successMessage(__('user.otp_sent'), $msg_data);
        }
        catch(\Exception $e)
        {
            \Log::error("OTP sending failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * This API will be used to verify OTP.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function verifyOtp(Request $request)
    {
        $msg_data = array();
        try
        {
            \Log::info("OTP verification process starts, starting at: " . Carbon::now()->format('H:i:s:u'));
            // Request Validation
            $validationErrors = $this->validateVerifyOtp($request);
            if (count($validationErrors)) {
                \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                errorMessage($validationErrors->all(), $validationErrors->all());
            }

            $checkOtpUser = User::where('phone',$request->phone)->first();

            if($checkOtpUser->fpwd_flag != 'Y')
            {
                errorMessage(__('auth.invalid_otp'), $msg_data);
            }

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
            if($request->workflow == 'register')
            {
                $updateUserData['fpwd_flag'] = 'N';
                $updateUserData['is_verified'] = 'Y';
                User::where('phone',$request->phone)->update($updateUserData);
            }
            successMessage(__('auth.otp_verified'), $msg_data);
        }
        catch(\Exception $e)
        {
            \Log::error("OTP verification failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Validate request parameters for request otp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    private function validateRequestOtp(Request $request)
    {
        return \Validator::make($request->all(), [
            'phone' =>  [                                                                  
                            'required',
                            'numeric',
                            Rule::exists('users', 'phone')->where('phone', $request->phone)
                        ],
            'workflow' => 'required'
        ])->errors();
    }

    /**
     * Validate request parameters for verify otp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    private function validateVerifyOtp(Request $request)
    {
        return \Validator::make($request->all(), [
            'phone' =>  [                                                                  
                            'required',
                            'numeric',
                            Rule::exists('users', 'phone')->where('phone', $request->phone)
                        ],
            'workflow' => 'required',
            'otp_code' => 'required'
        ])->errors();
    }
}
