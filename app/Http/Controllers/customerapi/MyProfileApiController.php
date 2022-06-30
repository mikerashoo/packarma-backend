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
                $data = User::select('name', 'email', 'phone_country_id','approval_status', 'phone', 'whatsapp_country_id', 'whatsapp_no','visiting_card_front','visiting_card_back')
                    ->with(['phone_country' => function ($query) {
                        $query->select('id', 'country_name', 'phone_code');
                    }])
                    ->with(['whatsapp_country' => function ($query) {
                        $query->select('id', 'country_name', 'phone_code');
                    }])
                    ->where('id', $user_id)->first();
                if (empty($data)) {
                    errorMessage(__('my_profile.not_found'), $msg_data);
                }
                if ($data->approval_status != 'accepted') {
                    $flags = array(
                        "my_address" => false,
                        "change_password" => false,
                        "about_us" => false,
                        "help_and_support" => false,
                        "terms_and_condition" => false,
                        "privacy_policy" => false,
                        "edit_user" => false,
                        "delete_user" => false,
                        "my_subscription" => false,
                        "my_order" => false,
                        "logout" => true,
                    );
                } else {
                    $flags = array(
                        "my_address" => true,
                        "change_password" => true,
                        "about_us" => true,
                        "help_and_support" => true,
                        "terms_and_condition" => true,
                        "privacy_policy" => true,
                        "edit_user" => true,
                        "delete_user" => true,
                        "my_subscription" => true,
                        "my_order" => true,
                        "logout" => true,
                    );
                }
                // $i=0;
                // // print_r($data);exit;
                // foreach($data as $row)
                // {
                    $data->visiting_card_front = getFile($data->visiting_card_front, 'visiting_card', false, 'front');
                    $data->visiting_card_back = getFile($data->visiting_card_back, 'visiting_card',false,'back');
                    // $i++;
                // }
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
                    errorMessage($userValidationErrors->all(), $userValidationErrors->all());
                }
                \Log::info("User Update Start!");
                $checkUser = User::select('approval_status', 'email', 'phone', 'status')->where([['id', $user_id]])->first();
                // if (isset($request->email) && (strtolower($request->email) != strtolower($checkUser->email))) {
                //     errorMessage(__('user.email_cant_update'), $msg_data);
                // }
                // if (isset($request->phone) && ($request->phone == $checkUser->phone)) {
                //     errorMessage(__('user.phone_already_registered'), $msg_data);
                // }
                if ($checkUser->approval_status == 'rejected') {
                    errorMessage(__('user.rejected'), $msg_data);
                }
                if ($checkUser->approval_status == 'pending') {
                    errorMessage(__('user.approval_pending'), $msg_data);
                }
                if ($checkUser->status == 0 && $checkUser->approval_status == 'accepted') {
                    errorMessage(__('user.not_active'), $msg_data);
                }
                $checkUser = User::where('id', $user_id)->first();
                $checkUser->update($request->all());
                $userData = $checkUser;
                $user = $userData->toArray();
                $userData->created_at->toDateTimeString();
                $userData->updated_at->toDateTimeString();
                $input = array();
                // Storing visiting card Front and Back
                if ($request->hasFile('visiting_card_front')) {
                    \Log::info("Storing visiting card front image.");
                    $visiting_card_front = $request->file('visiting_card_front');
                    $extension = $visiting_card_front->extension();
                    $imgname_front = $user['id'] . '_front_' . Carbon::now()->format('dmYHis') . '.' . $extension;
                    $visiting_card_front->storeAs('uploads/visiting_card/front', $imgname_front, 'public');
                    $user['visiting_card_front'] = $input['visiting_card_front'] = $imgname_front;
                }
                if ($request->hasFile('visiting_card_back')) {
                    \Log::info("Storing visiting card back image.");
                    $visiting_card_back = $request->file('visiting_card_back');
                    $extension = $visiting_card_back->extension();
                    $imgname_back = $user['id'] . '_back_' . Carbon::now()->format('dmYHis') . '.' . $extension;
                    $visiting_card_back->storeAs('uploads/visiting_card/back', $imgname_back, 'public');
                    $user['visiting_card_back'] = $input['visiting_card_back'] = $imgname_back;
                }
                if (!empty($input)) {
                    User::find($user_id)->update($input);
                }
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
        ])->errors();
    }
}
