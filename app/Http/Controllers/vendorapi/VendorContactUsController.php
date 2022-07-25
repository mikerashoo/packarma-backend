<?php

namespace App\Http\Controllers\vendorapi;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorContactUs;
use Illuminate\Http\Request;

class VendorContactUsController extends Controller
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
            $vendor_token = readVendorHeaderToken();
            if ($vendor_token) {
                $vendor_contact_us_details = array();
                $load_page = $request->load_page;

                if (!$load_page) {
                    errorMessage(__('vendor.load_page_require'), $msg_data);
                }
                $vendor_contact_us_details = $request->all();

                if ($load_page == 'gst') {
                    $contactUsErrors = $this->validateContactUsForGstPage($request);
                    $vendor_id = 0;
                } else {
                    $contactUsErrors = $this->validateContactUsForHomePage($request);
                    $vendor_id = $vendor_token['sub'];
                    $vendorDetails = Vendor::where([['id', $vendor_id]])->first();
                    $vendor_contact_us_details['email'] = $vendorDetails->vendor_email;
                    $vendor_contact_us_details['name'] = $vendorDetails->vendor_name;
                    $vendor_contact_us_details['mobile'] = $vendorDetails->phone;
                }
                // Request Validation
                if (count($contactUsErrors)) {
                    \Log::error("Auth Exception: " . implode(", ", $contactUsErrors->all()));
                    errorMessage(__('auth.validation_failed'), $contactUsErrors->all());
                }
                \Log::info("Store Contact us Details");

                $ip_address = request()->ip();
                $platform = $request->header('platform');
                $vendor_contact_us_details['call_from'] = $platform;
                $vendor_contact_us_details['ip_address'] = $ip_address;
                $vendor_contact_us_details['vendor_id'] = $vendor_id;

                $vendorContactUs = VendorContactUs::create($vendor_contact_us_details);

                // Store Gst Details

                \Log::info("Contact us Details Stored Successfully");
                $vendorContactUsData = $vendorContactUs;

                $vendorContactUsDetails = $vendorContactUsData->toArray();
                $vendorContactUsData->created_at->toDateTimeString();
                $vendorContactUsData->updated_at->toDateTimeString();


                successMessage(__('vendor.contact_us_stored'), $msg_data);
            } else {
                errorMessage(__('auth.authentication_failed'), $msg_data);
            }
        } catch (\Exception $e) {
            \Log::error("Contact us Details Store Failed: " . $e->getMessage());
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
            ],
            [
                'details.required' => 'Message is Require',
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
            ],
            [
                'details.required' => 'Message is Require',
            ]
        )->errors();
    }
}
