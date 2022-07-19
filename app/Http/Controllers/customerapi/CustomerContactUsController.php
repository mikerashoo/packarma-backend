<?php

namespace App\Http\Controllers\customerapi;

use App\Http\Controllers\Controller;
use App\Models\CustomerContactUs;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerContactUsController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $msg_data = array();
        try {
            $token = readHeaderToken();
            if ($token) {
                $user_contact_us_details = array();
                $load_page = $request->load_page;

                if (!$load_page) {
                    errorMessage(__('user.load_page_require'), $msg_data);
                }
                $user_contact_us_details = $request->all();

                if ($load_page == 'gst') {
                    $contactUsErrors = $this->validateContactUsForGstPage($request);
                    $user_id = 0;
                } else {
                    $contactUsErrors = $this->validateContactUsForHomePage($request);
                    $user_id = $token['sub'];
                    $userDetails = User::where([['id', $user_id]])->first();
                    $user_contact_us_details['email'] = $userDetails->email;
                    $user_contact_us_details['name'] = $userDetails->name;
                    $user_contact_us_details['mobile'] = $userDetails->phone;
                }
                // Request Validation
                if (count($contactUsErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $contactUsErrors->all()));
                    errorMessage(__('auth.validation_failed'), $contactUsErrors->all());
                }
                \Log::info("Store Contact us Details");

                $ip_address = request()->ip();
                $user_contact_us_details['ip_address'] = $ip_address;
                $user_contact_us_details['user_id'] = $user_id;

                $userContactUs = CustomerContactUs::create($user_contact_us_details);

                // Store Gst Details

                \Log::info("Contact Us Details Stored Successfully");
                $userContactUsData = $userContactUs;
                $customerContactUsDetails = $userContactUsData->toArray();
                $userContactUsData->created_at->toDateTimeString();
                $userContactUsData->updated_at->toDateTimeString();


                successMessage(__('user.contact_us_stored'), $msg_data);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Contact Us Details Store Failed: " . $e->getMessage());
            errorMessage(__('auth.something_went_wrong'), $msg_data);
        }
    }


    private function validateContactUsForGstPage(Request $request)
    {
        return \Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'mobile' => 'required|numeric|digits:10',
                'email' => 'required|email',
                'subject' => 'required|string',
                'details' => 'required|string',
                'call_from' => 'required|string',
            ],
            [
                'details.required' => 'Message is Require',
                'call_from.required' => 'Device Type is Require'
            ]
        )->errors();
    }

    private function validateContactUsForHomePage(Request $request)
    {
        return \Validator::make(
            $request->all(),
            [
                'subject' => 'required|string',
                'details' => 'required|string',
                'call_from' => 'required|string',
            ],
            [
                'details.required' => 'Message is Require',
                'call_from.required' => 'Device Type is Require'
            ]
        )->errors();
    }
}
