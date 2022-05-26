<?php

use App\Models\CustomerDevice;
use App\Models\DisplayMsg;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorDevice;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Storage;
use Image as thumbimage;

if (!function_exists('errorMessage')) {
    function errorMessage($msg = '', $data = array(), $expireSessionCode = "")
    {
        $return_array = array();
        $return_array['success'] = '0';
        if ($expireSessionCode != "") {
            $return_array['success'] = $expireSessionCode;
        }
        $return_array['message'] = $msg;
        if (isset($data) && count($data) > 0)
            $return_array['data'] = $data;
        if (isset($other_data) && !empty($other_data)) {
            foreach ($other_data as $key => $val)
                $return_array[$key] = $val;
        }
        echo json_encode($return_array);
        exit();
    }
}

if (!function_exists('successMessage')) {
    function successMessage($msg = '', $data = array())
    {
        $return_array = array();
        $return_array['success'] = '1';
        $return_array['message'] = $msg;
        if (isset($data) && count($data) > 0)
            $return_array['data'] = $data;
        if (isset($other_data) && !empty($other_data)) {
            foreach ($other_data as $key => $val)
                $return_array[$key] = $val;
        }
        echo json_encode($return_array);
        exit();
    }
}

if (!function_exists('generateRandomOTP')) {
    function generateRandomOTP()
    {
        // return (rand(1000,9999));
        return (1234);
    }
}

if (!function_exists('readHeaderToken')) {
    function readHeaderToken()
    {
        $msg_data = array();
        $tokenData = Session::get('tokenData');
        $customerImeiNoData = Session::get('customerImeiNoData');
        $token = JWTAuth::setToken($tokenData)->getPayload();
        // $userChk = User::where([['id', $token['sub']]])->get();
        $userChk = CustomerDevice::where([['user_id', $token['sub']], ['imei_no', $customerImeiNoData]])->get();
        if (count($userChk) == 0 || $userChk[0]->remember_token == '') {
            errorMessage('please_login_and_try_again', $msg_data, 4);
        }
        return $token;
    }
}

if (!function_exists('readVenderHeaderToken')) {
    function readVendorHeaderToken()
    {
        $vendor_msg_data = array();
        $vendorTokenData = Session::get('vendorTokenData');
        $vendorImeiNoData = Session::get('vendorImeiNoData');
        $vendor_token = JWTAuth::setToken($vendorTokenData)->getPayload();
        $vendorChk = VendorDevice::where([['vendor_id', $vendor_token['sub']], ['imei_no', $vendorImeiNoData]])->get();

        if (count($vendorChk) == 0 || $vendorChk[0]->remember_token == '') {
            errorMessage(__('auth.please_login_and_try_again'), $vendor_msg_data, 4);
        }
        return $vendor_token;
    }
}


if (!function_exists('getPincodeDetails')) {
    function getPincodeDetails($pincode = '1')
    {
        $vendor_msg_data = array();
        $data = Http::get('https://api.postalpincode.in/pincode/' . $pincode)->json();
        if (empty($data[0]['PostOffice'])) {
            errorMessage(__('pin_code.not_found'), $vendor_msg_data);
        }

        $vendor_msg_data['city'] = $data[0]['PostOffice'][0]['District'];
        $vendor_msg_data['state'] = $data[0]['PostOffice'][0]['State'];
        $vendor_msg_data['pin_code'] = $data[0]['PostOffice'][0]['Pincode'];
        return $vendor_msg_data;
    }
}

if (!function_exists('checkPermission')) {
    function checkPermission($name)
    {
        if (session('data')['role_id'] == 1) {
            return true;
        }
        $permissions = Session::get('permissions');
        $permission_array = array();
        for ($i = 0; $i < count($permissions); $i++) {
            $permission_array[$i] = $permissions[$i]->codename;
        }
        if (in_array($name, $permission_array)) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('generateSeoURL')) {
    function generateSeoURL($string, $wordLimit = 0)
    {
        $separator = '-';
        if ($wordLimit != 0) {
            $wordArr = explode(' ', $string);
            $string = implode(' ', array_slice($wordArr, 0, $wordLimit));
        }
        $quoteSeparator = preg_quote($separator, '#');
        $trans = array(
            '&.+?;'                    => '',
            '[^\w\d _-]'            => '',
            '\s+'                    => $separator,
            '(' . $quoteSeparator . ')+' => $separator
        );
        $string = strip_tags($string);
        foreach ($trans as $key => $val) {
            $UTF8_ENABLED = config('global.UTF8_ENABLED');
            $string = preg_replace('#' . $key . '#i' . ($UTF8_ENABLED ? 'u' : ''), $val, $string);
        }
        $string = strtolower($string);
        return trim(trim($string, $separator));
    }
}

if (!function_exists('approvalStatusArray')) {
    function approvalStatusArray($displayValue = "", $allKeys = false)
    {
        $returnArray = array(
            'pending' => 'Pending',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected'
        );
        if (!empty($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }
        if (empty($displayValue) && $allKeys) {
            $returnArray = array_keys($returnArray);
        }
        return $returnArray;
    }
}

/**
 *   created by : Sagar Thokal
 *   Created On : 03-Mar-2022
 *   Uses : To display globally status 0|1 as Active|In-Active in view pages
 *   @param $key
 *   @return Response
 */
if (!function_exists('displayStatus')) {
    function displayStatus($displayValue = "")
    {
        $returnArray = array(
            '1' => 'Active',
            '0' => 'In-Active'
        );
        if (isset($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }

        return $returnArray;
    }
}

/**
 *   created by : Sagar Thokal
 *   Created On : 04-Mar-2022
 *   Uses : To display globally Featured 0|1 as  Featured|Un-Featured in view pages
 *   @param $key
 *   @return Response
 */
if (!function_exists('displayFeatured')) {
    function displayFeatured($displayValue = "")
    {
        $returnArray = array(
            '1' => 'Featured',
            '0' => 'Un-Featured'
        );
        if (isset($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }

        return $returnArray;
    }
}

/**
 *   created by : Pradyumn Dwivedi
 *   Created On : 01-Mar-2022
 *   Uses :  To fetch value in customer enquiry type       
 */
if (!function_exists('customerEnquiryType')) {
    function customerEnquiryType($displayValue = "", $allKeys = false)
    {
        $returnArray = array(
            'general' => 'General',
            'engine' => 'Engine'
        );
        if (!empty($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }
        if (empty($displayValue) && $allKeys) {
            $returnArray = array_keys($returnArray);
        }
        return $returnArray;
    }
}

/**
 *   created by : Pradyumn Dwivedi
 *   Created On : 01-Mar-2022
 *   Uses :  To fetch value in customer enquiry quote type       
 */
if (!function_exists('customerEnquiryQuoteType')) {
    function customerEnquiryQuoteType($displayValue = "", $allKeys = false)
    {
        $returnArray = array(
            'enquired' => 'Enquired',
            'map_to_vendor' => 'Map To Vendor',
            'accept_cust' => 'Accept By Customer',
            'closed' => 'Closed'

        );
        if (!empty($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }
        if (empty($displayValue) && $allKeys) {
            $returnArray = array_keys($returnArray);
        }
        return $returnArray;
    }
}

/**
 *   created by : Pradyumn Dwivedi
 *   Created On : 01-Mar-2022
 *   Uses :  To fetch value in customer enquiry quote type       
 */
if (!function_exists('vendorEnquiryStatus')) {
    function vendorEnquiryStatus($displayValue = "", $allKeys = false)
    {
        $returnArray = array(
            'mapped' => 'Mapped',
            'quoted' => 'Quoted',
            'viewed' => 'Viewed',
            'accept' => 'Accept',
            'reject' => 'Reject',
            'requote' => 'Requote',
        );
        if (!empty($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }
        if (empty($displayValue) && $allKeys) {
            $returnArray = array_keys($returnArray);
        }
        return $returnArray;
    }
}

/**
 *   created by : Pradyumn Dwivedi
 *   Created On : 03-Mar-2022
 *   Uses :  To fetch value in subscription type       
 */
if (!function_exists('subscriptionType')) {
    function subscriptionType($displayValue = "", $allKeys = false)
    {
        $returnArray = array(
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'semi_yearly' => 'Semi Yearly',
            'yearly' => 'Yearly'
        );
        if (!empty($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }
        if (empty($displayValue) && $allKeys) {
            $returnArray = array_keys($returnArray);
        }
        return $returnArray;
    }
}

/**
 *   created by : Pradyumn Dwivedi
 *   Created On : 03-Mar-2022
 *   Uses :  To fetch value in order delivery status type       
 */
if (!function_exists('deliveryStatus')) {
    function deliveryStatus($displayValue = "", $allKeys = false)
    {
        $returnArray = array(
            'pending' => 'Pending',
            'processing' => 'Processing',
            'out_for_delivery' => 'Out For Delivery',
            'delivered' => 'Delivered'
        );
        if (!empty($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }
        if (empty($displayValue) && $allKeys) {
            $returnArray = array_keys($returnArray);
        }
        return $returnArray;
    }
}

/**
 *   created by : Pradyumn Dwivedi
 *   Created On : 03-Mar-2022
 *   Uses :  To fetch value in order payment status type       
 */
if (!function_exists('paymentStatus')) {
    function paymentStatus($displayValue = "", $allKeys = false)
    {
        $returnArray = array(
            'pending' => 'Pending',
            'semi_paid' => 'Semi Paid',
            'fully_paid' => 'Fully Paid'
        );
        if (!empty($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }
        if (empty($displayValue) && $allKeys) {
            $returnArray = array_keys($returnArray);
        }
        return $returnArray;
    }
}

/**
 *   created by : Pradyumn Dwivedi
 *   Created On : 03-Mar-2022
 *   Uses :  To fetch value in order payment status type       
 */
if (!function_exists('customerPaymentStatus')) {
    function customerPaymentStatus($displayValue = "", $allKeys = false)
    {
        $returnArray = array(
            'pending' => 'Pending',
            'fully_paid' => 'Fully Paid'
        );
        if (!empty($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }
        if (empty($displayValue) && $allKeys) {
            $returnArray = array_keys($returnArray);
        }
        return $returnArray;
    }
}

/**
 *   created by : Pradyumn Dwivedi
 *   Created On : 03-Mar-2022
 *   Uses :  To fetch value in order payment during payment status type       
 */
if (!function_exists('paymentStatusType')) {
    function paymentStatusType($displayValue = "", $allKeys = false)
    {
        $returnArray = array(
            'semi_paid' => 'Semi Paid',
            'fully_paid' => 'Fully Paid'
        );
        if (!empty($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }
        if (empty($displayValue) && $allKeys) {
            $returnArray = array_keys($returnArray);
        }
        return $returnArray;
    }
}

/**
 *   created by : Pradyumn Dwivedi
 *   Created On : 10-Mar-2022
 *   Uses :  To fetch value in user subscription payment status type       
 */
if (!function_exists('subscriptionPaymentStatus')) {
    function subscriptionPaymentStatus($displayValue = "", $allKeys = false)
    {
        $returnArray = array(
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed'
        );
        if (!empty($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }
        if (empty($displayValue) && $allKeys) {
            $returnArray = array_keys($returnArray);
        }
        return $returnArray;
    }
}

/**
 *   created by : Pradyumn Dwivedi
 *   Created On : 10-Mar-2022
 *   Uses :  To fetch value in user subscription payment mode type       
 */
if (!function_exists('paymentMode')) {
    function paymentMode($displayValue = "", $allKeys = false)
    {
        $returnArray = array(
            'cash' => 'Cash',
            'bank_transfer' => 'Bank Transfer',
            'cheque' => 'Cheque',
            'demand_draft' => 'Demand Draft',
        );
        if (!empty($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }
        if (empty($displayValue) && $allKeys) {
            $returnArray = array_keys($returnArray);
        }
        return $returnArray;
    }
}

/**
 *   created by : Pradyumn Dwivedi
 *   Created On : 06-may-2022
 *   Uses :  To fetch payment value in customer and subscription payment mode type       
 */
if (!function_exists('onlinePaymentMode')) {
    function onlinePaymentMode($displayValue = "", $allKeys = false)
    {
        $returnArray = array(
            'online' => 'Online Payment',
        );
        if (!empty($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }
        if (empty($displayValue) && $allKeys) {
            $returnArray = array_keys($returnArray);
        }
        return $returnArray;
    }
}

/**
 *   created by : Pradyumn Dwivedi
 *   Created On : 18-Mar-2022
 *   Uses :  To fetch value in user message user type       
 */
if (!function_exists('messageUserType')) {
    function messageUserType($displayValue = "", $allKeys = false)
    {
        $returnArray = array(
            'all' => 'All',
            'customer' => 'Customer',
            'vendor' => 'Vendor',
        );
        if (!empty($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }
        if (empty($displayValue) && $allKeys) {
            $returnArray = array_keys($returnArray);
        }
        return $returnArray;
    }
}

/**
 *   created by : Pradyumn Dwivedi
 *   Created On : 18-Mar-2022
 *   Uses :  To fetch value in user message message trigger       
 */
if (!function_exists('messageTrigger')) {
    function messageTrigger($displayValue = "", $allKeys = false)
    {
        $returnArray = array(
            'both' => 'Both',
            'admin' => 'Admin',
            'batch' => 'Batch',
        );
        if (!empty($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }
        if (empty($displayValue) && $allKeys) {
            $returnArray = array_keys($returnArray);
        }
        return $returnArray;
    }
}

/**
 *   created by : Pradyumn Dwivedi
 *   Created On : 29-April-2022
 *   Uses :  To fetch value in measurement  message trigger       
 */
if (!function_exists('measurementUnitForm')) {
    function measurementUnitForm($displayValue = "", $allKeys = false)
    {
        $returnArray = array(
            'A' => 'Aerosols',
            'S' => 'Solid',
            'L' => 'Liquid',
            'P' => 'Pump Spray',
            'SS' => 'Semi Solid',
            'O' => 'Other'
        );
        if (!empty($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }
        if (empty($displayValue) && $allKeys) {
            $returnArray = array_keys($returnArray);
        }
        return $returnArray;
    }
}

/**
 *   Created by : Pradyumn Dwivedi
 *   Created On : 11-May-2022
 *   Uses: This function will be used to full search data in api.
 */
if (!function_exists('fullSearchQuery')) {
    function fullSearchQuery($query, $word, $params)
    {
        $orwords = explode('|', $params);
        $query = $query->where(function ($query) use ($word, $orwords) {
            foreach ($orwords as $key) {
                $query->orWhere($key, 'like', '%' . $word . '%');
            }
        });
        return $query;
    }
}

/**
 *   created by : Pradyumn Dwivedi
 *   Created On : 11-May-2022
 *   Uses :  To fetch value in user address       
 */
if (!function_exists('addressType')) {
    function addressType($displayValue = "", $allKeys = false)
    {
        $returnArray = array(
            'shipping' => 'Shipping',
            'billing' => 'Billing'
        );
        if (!empty($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }
        if (empty($displayValue) && $allKeys) {
            $returnArray = array_keys($returnArray);
        }
        return $returnArray;
    }
}

/**
 *   created by : Pradyumn Dwivedi
 *   Created On : 21-May-2022
 *   Uses :  To fetch value in gst type dropdown in customer enquiry map to vendor       
 */
if (!function_exists('gstType')) {
    function gstType($displayValue = "", $allKeys = false)
    {
        $returnArray = array(
            'not_applicable' => 'Not Applicable',
            'cgst+sgst' => 'CGST+SGST',
            'igst' => 'IGST'
        );
        if (!empty($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }
        if (empty($displayValue) && $allKeys) {
            $returnArray = array_keys($returnArray);
        }
        return $returnArray;
    }
}
