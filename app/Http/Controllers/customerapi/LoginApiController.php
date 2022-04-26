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
        \Log::info("Logging in user, starting at: " . Carbon::now()->format('H:i:s:u'));
        try
        {
            // Request Validation
            $validationErrors = $this->validateLogin($request);
            if (count($validationErrors)) {
                \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                errorMessage(__('auth.validation_failed'), $validationErrors->all());
            }
            $userData = User::where([['email', $request->email],['password', md5($request->email.$request->password)],['status', '1'],['is_verified', 'Y']])->first();
            if(empty($userData))
            {
                errorMessage(__('user.login_failed'), $msg_data);
            }
            $token = JWTAuth::fromUser($userData);
            $users = User::find($userData->id);
            $userData->last_login = $users->last_login = Carbon::now();
            $userData->remember_token = $users->remember_token = $token;
            $users->save();
            successMessage(__('user.logged_in_successfully'), $userData->toArray());
        }
        catch(\Exception $e)
        {
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
            'password' => 'required|string|min:8'
        ])->errors();
    }
}