<?php

/**
 * Created By :Ankita Singh
 * Created On : 12 Apr 2022
 * Uses : This controller will be used to register a new user.
 */

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Response;

class RegisterApiController extends Controller
{
    /**
     * Register a new user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $msg_data = array();
        \Log::info("Initiating registeration process, starting at: " . Carbon::now()->format('H:i:s:u'));
        try {
            // Request Validation
            $validationErrors = $this->validateSignup($request);
            if (count($validationErrors)) {
                \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                errorMessage($validationErrors->all(), $validationErrors->all());
            }
            \Log::info("User creation started!");
            // Password Creation
            $password = md5(strtolower($request->email) . $request->password);
            unset($request->password);
            $request['password'] = $password;

            $checkUser = User::where('phone', $request->phone)->first();
            if (empty($checkUser)) {
                // Store a new user
                $userData = User::create($request->all());
                \Log::info("User registered successfully with email id: " . $request->email . " and phone number: " . $request->phone);
            } else {
                if ($checkUser->is_verified == 'Y') {
                    errorMessage(__('user.phone_already_exist'), $msg_data);
                }
                // Update existing user
                $checkUser->update($request->all());
                $userData = $checkUser;
                \Log::info("Existing user updated with email id: " . $request->email . " and phone number: " . $request->phone);
            }
            $user = $userData->toArray();
            $user['created_at'] = $userData->created_at->toDateTimeString();
            $user['updated_at'] = $userData->updated_at->toDateTimeString();
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
                User::find($user['id'])->update($input);
            }
            successMessage(__('user.registered_successfully'), $user);
        } catch (\Exception $e) {
            \Log::error("Registeration failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }

    /**
     * Validate request for registeration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validateSignup(Request $request)
    {
        return \Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|numeric|digits:10',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'visiting_card_front' => 'max:' . config('global.MAX_IMAGE_SIZE'),
            'visiting_card_back' => 'max:' . config('global.MAX_IMAGE_SIZE')
        ])->errors();
    }
}
