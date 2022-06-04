<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use App\Models\CustomerDevice;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class MyProfileApiController extends Controller
{
    /**
     * Created By Pradyumn Dwivedi
     * Created at : 03/06/2022
     * Uses : To show customer(user) my profile
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $msg_data = array();
        \Log::info("My Profile Show, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];

                $data = User::select('name', 'email', 'phone_country_id', 'phone', 'whatsapp_country_id', 'whatsapp_no')
                    ->with(['phone_country' => function ($query) {
                        $query->select('id', 'country_name', 'phone_code');
                    }])
                    ->with(['whatsapp_country' => function ($query) {
                        $query->select('id', 'country_name', 'phone_code');
                    }])
                    ->where([['id', $user_id]])->first();
                if (empty($data)) {
                    errorMessage(__('my_profile.not_found'), $msg_data);
                }

                $flags = array(
                    "my_address" => true,
                    "change_password" => true,
                    "about_us" => true,
                    "help_and_support" => true,
                    "terms_and_condition" => true,
                    "privacy_policy" => true,
                    "edit_user" => true,
                    "delete_user" => true,
                );

                $msg_data['result'] = $data;
                $msg_data['flags'] = $flags;

                successMessage(__('my_profile.info_fetch'), $msg_data);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Fetching Info failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By Pradyumn Dwivedi
     * Created at : 03/06/2022
     * Uses : To update customer(user) my profile
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $msg_data = array();
        \Log::info("My Profile Update, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];
                $userValidationErrors = $this->validateUpdate($request);
                if (count($userValidationErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $userValidationErrors->all()));
                    errorMessage(__('auth.validation_failed'), $userValidationErrors->all());
                }
                \Log::info("User Update Start!");
                $checkUser = User::where([['phone', $request->phone], ['id', '!=', $user_id]])->first();
                if (!empty($checkUser)) {
                    errorMessage(__('user.same_phone_exist'), $msg_data);
                }

                $checkUser = User::where('id', $user_id)->first();
                $checkUser->update($request->all());
                $userData = $checkUser;
                $user = $userData->toArray();

                $userData->created_at->toDateTimeString();
                $userData->updated_at->toDateTimeString();
                \Log::info("Existing customer updated with email id: " . $request->email . " and phone number: " . $request->phone);
                successMessage(__('user.update_successfully'), $user);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("My Profile Update failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Created By Pradyumn Dwivedi
     * Created at : 03/06/2022
     * Uses : To delete customer(user) my profile
     * 
     */
    public function destroy()
    {
        $msg_data = array();
        \Log::info("Delete customer Account, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_id = $token['sub'];

                \Log::info("Delete Customer Start!");

                User::destroy($user_id);
                CustomerDevice::where('user_id', $user_id)->delete();
                \Log::info("Customer deleted successfully! ");
                successMessage(__('user.delete_successfully'), $msg_data);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Customer Delete failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }


    /**
     * Created By Pradyumn Dwivedi
     * Created at : 03/06/2022
     * Uses : To validate update request
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateUpdate(Request $request)
    {
        return \Validator::make($request->all(), [
            'name' => 'required|string',
            'phone_country_id' => 'required|numeric',
            'phone' => 'required|numeric',
            'email' => 'required|email',
        ])->errors();
    }
}
