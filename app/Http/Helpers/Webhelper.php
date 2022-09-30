<?php

use App\Models\DisplayMsg;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorDevice;
use App\Models\CustomerDevice;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Storage;
use Image as thumbimage;
use PhpParser\Node\Stmt\Foreach_;

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
            errorMessage(__('auth.please_login_and_try_again'), $msg_data, 4);
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
 *   created by : Maaz
 *   Created On : 29-Jun-2022
 *   Uses : To display globally records deleted or no
 *   @param $key
 *   @return Response
 */
if (!function_exists('isRecordDeleted')) {
    function isRecordDeleted($value = NULL)
    {

        if ($value == NULL) {
            $isDeleted = false;
        } else {
            $isDeleted = true;
        }

        return $isDeleted;
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
            'order' => 'Order Placed',
            'closed' => 'Closed',
            'auto_reject' => 'Auto Reject'
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
            'auto_reject' => 'Auto Reject'
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
 *   Created On : 16-Sept-2022
 *   Uses :  To fetch value in packaging solution structure type
 */
if (!function_exists('solutionStructureType')) {
    function solutionStructureType($displayValue = "", $allKeys = false)
    {
        $returnArray = array('Economical Solution','Advance Solution','Sustainable Solution');
        if (!empty($displayValue)) {
            $returnArray = $returnArray[$displayValue];
        }
        // if (empty($displayValue) && $allKeys) {
        //     $returnArray = array_keys($returnArray);
        // }
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
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled'
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
 *   Created by : Maaz
 *   Created On : 05-july-2022
 *   Uses: This function will be used to order data in api.
 */
if (!function_exists('allOrderBy')) {
    function allOrderBy($query, $params)
    {
        foreach ($params as $key => $value) {
            $query->orderBy($key, $value);
        }
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

/**
 *   created by : Maaz Ansari
 *   Created On : 14-June-2022
 *   Uses :  to get pin code details   
 */


if (!function_exists('getPincodeDetails')) {
    function getPincodeDetails($pincode)
    {
        $msg_data = array();

        $data = Http::get('https://api.postalpincode.in/pincode/' . $pincode)->json();
        if (empty($data[0]['PostOffice'])) {
            errorMessage(__('pin_code.not_found'), $msg_data);
        }

        $msg_data['city'] = $data[0]['PostOffice'][0]['District'];
        $msg_data['state'] = $data[0]['PostOffice'][0]['State'];
        $msg_data['pin_code'] = $data[0]['PostOffice'][0]['Pincode'];
        return $msg_data;
    }
}

if (!function_exists('getFormatid')) {
    function getFormatid($id, $from_table = '')
    {
        switch ($from_table) {
            case 'orders':
                $prefix = '#PAC';
                break;

            case 'vendor_quotations':
                $prefix = '#PEQ';
                break;

            default:
                $prefix = '#PCK';
                break;
        }
        $formatId = str_pad($id, 6, 0, STR_PAD_LEFT);
        $formatId = $prefix . $formatId;
        return $formatId;
    }
}


if (!function_exists('convertNumberToWord')) {
    function convertNumberToWord($num = false)
    {
        $num = str_replace(array(',', ' '), '', trim($num));
        if (!$num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array(
            '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array(
            '', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ($tens < 20) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }

        return ucwords(implode(' ', $words));
    }
}


/**
 *   created by : Maaz Ansari
 *   Created On : 21-july-2022
 *   Uses :  to disply message  
 */


if (!function_exists('displayMessage')) {
    function displayMessage($msg, $value = '')
    {
        switch ($msg) {
            case 'qoutation_accepted_by_customer':
                $final_msg = 'Customer has already accepted the qoutation for vendor  ' . $value;
                break;

            case 'enquiry_order':
                $final_msg = 'Customer already placed order';
                break;

            case 'enquiry_closed':
                $final_msg = 'Enquiry is closed';
                break;

            default:
                # code...
                break;
        }
        echo $final_msg;
        exit();
    }
}



/**
 *   created by : Maaz Ansari
 *   Created On : 27-july-2022
 *   Uses :  calculate subscription  
 */


if (!function_exists('calcCustomerSubscription')) {
    function calcCustomerSubscription($user_id, $subscription_id)
    {
        $user = User::find($user_id);
        $subscription = Subscription::find($subscription_id);
        if ($subscription->subscription_type == 'monthly') {
            $currentDateTime = Carbon::now()->toArray();
            $subscription_start_date = $currentDateTime['formatted'];

            $newDateTime = Carbon::now()->addDays(30)->toArray();
            $subscription_end_date =  $newDateTime['formatted'];
        }
        if ($subscription->subscription_type == 'quarterly') {
            $currentDateTime = Carbon::now()->toArray();
            $subscription_start_date = $currentDateTime['formatted'];

            $newDateTime = Carbon::now()->addDays(90)->toArray();
            $subscription_end_date =  $newDateTime['formatted'];
        }
        if ($subscription->subscription_type == 'semi_yearly') {
            $currentDateTime = Carbon::now()->toArray();
            $subscription_start_date = $currentDateTime['formatted'];

            $newDateTime = Carbon::now()->addDays(180)->toArray();
            $subscription_end_date =  $newDateTime['formatted'];
        }
        if ($subscription->subscription_type == 'yearly') {
            $currentDateTime = Carbon::now()->toArray();
            $subscription_start_date = $currentDateTime['formatted'];

            $newDateTime = Carbon::now()->addDays(360)->toArray();
            $subscription_end_date =  $newDateTime['formatted'];
        }
        if ($user->subscription_end != null && $user->subscription_end > $subscription_start_date) {
            $diff_days = strtotime($user->subscription_end) - strtotime($subscription_start_date);
            // 1 day = 24 hours
            // 24 * 60 * 60 = 86400 seconds
            $interval = abs(round($diff_days / 86400));
            $subscription_end_date = Carbon::createFromFormat('Y-m-d H:i:s', $subscription_end_date);
            $subscription_end_date = $subscription_end_date->addDays($interval);
        }
        //data to enter in user table of selected user id
        $subscription_request_data = array();
        $subscription_request_data['subscription_id'] = $subscription->id;
        $subscription_request_data['subscription_start'] = $subscription_start_date;
        $subscription_request_data['subscription_end'] = $subscription_end_date;
        $subscription_request_data['type'] = 'premium';

        //update subscription data of user
        $user->update($subscription_request_data);
        $subscription_data = $user;
        $subscribed = $subscription_data->toArray();
        $subscription_data->created_at->toDateTimeString();
        $subscription_data->updated_at->toDateTimeString();
        \Log::info("Subscription, user subscribed successfully!");

        return true;
    }
}

/**
 * Created by : Pradyumn Dwivedi
 * Created at : 30-Sept-2022
 * Uses : To send otp setting message content body
 */
if (!function_exists('sendOTPSms')) {
    function sendOTPSms($randomNumber = '', $mobile_number = '', $lang = 'en')
    {
        if (isset($mobile_number) && !empty($mobile_number) && !empty($randomNumber)) {
            $message = DB::table('message_sms')
                ->leftjoin('languages', 'languages.id', '=', 'message_sms.language_id')
                ->where([['operation', 'otp_request'], ['language_code', $lang]])->pluck('message')->first();
            if ($message) {
                $sms_body_content = $message;
                $sms_body_content = str_replace('$$OTP$$', $randomNumber, $sms_body_content);
                smsGetCall($sms_body_content, $mobile_number);
            }
        }
    }
}


/**
 * Created by : Pradyumn Dwivedi
 * Created at : 29-Sept-2022
 * Uses : To send sms call clickatell url
 */
if (!function_exists('smsGetCall')) {
    function
    smsGetCall($sms_body = '', $mobile_number = '')
    {
        if (strlen($mobile_number) == 10) {
            $mobile_number =  '91' . $mobile_number;
        }
        $sms_body = urlencode($sms_body);

        $apiKey = config('global.TEST_SMS_API');

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://platform.clickatell.com/messages/http/send?apiKey=' . $apiKey . '&to=' . $mobile_number . '&content=' . $sms_body,
        ]);
        $resp = curl_exec($curl);
        $err = curl_error($curl);
        $err_no_curl = curl_errno($curl);
        curl_close($curl);
        if ($err || $err_no_curl) {
            \Log::error("smsGetCall Curl Call has Error number " . $err_no_curl . " and Error is ::" . $err);
        } else {
            \Log::error("smsGetCall Curl Call Success has :: " . $resp);
            return $resp;
        }
    }
}


if (!function_exists('sendEmail')) {
    function sendEmail($action_link, $email)
    {

        $msg_data = DB::table('message_emails')->select('*')->where('mail_key', 'USER_FORGOT_PASS')->first();
        $body = $msg_data->content;

        Mail::send('backend/auth/email-forgot', ['link' => $action_link, 'body' => $body], function ($message) use ($email) {
            $message->from('crm2@mypcot.com', 'PACKARMA');
            $message->to($email, 'PACKARMA')->subject('Reset Password');
        });
    }
}


if (!function_exists('sendFcmNotification')) {
    function sendFcmNotification($fcm_ids = array(), $notification_data = array(), $for = 'vendor')
    {

        $auth_key = config('global.TEST_VENDOR_FCM_SERVER_KEY');
        if ($for == 'customer') {
            $auth_key = config('global.TEST_CUSTOMER_FCM_SERVER_KEY');
        }
        if (is_array($fcm_ids) && !empty($fcm_ids[0])) {
            $auth_token = array(
                'Authorization: key=' . $auth_key,
                'Content-Type: application/json'
            );

            if (is_array($fcm_ids)) {
                $auth_token = $auth_token;

                //FCM MSG DATA
                $data_array['title']        = $notification_data['title'];
                $data_array['body']         = $notification_data['body'];
                $data_array['image']        = $notification_data['image_path'];
                $data_array['type']         = $notification_data['page_name'];
                $data_array['type_id']         = $notification_data['type_id'];
                $data_array['sound']        = "default";

                $device_array = $fcm_ids;

                $array_chunk_length = 500;
                $deviceArrayChunk = array_chunk($device_array, $array_chunk_length, true);
                $is_post = true;
                foreach ($deviceArrayChunk as $deviceArray) {
                    $fields = array(
                        'registration_ids' => $deviceArray,
                        'notification' => $data_array,
                        'data'             => $data_array,
                    );
                    $postdata = json_encode($fields);

                    $result =  fcmCallingToCurl($auth_token, $is_post, $postdata);
                }
            }
        }
    }
}

if (!function_exists('fcmCallingToCurl')) {
    function fcmCallingToCurl($auth_token, $is_post = false, $post_data = array())
    {
        $url = "https://fcm.googleapis.com/fcm/send";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if (is_array($auth_token)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $auth_token);
        }
        if ($is_post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        $result = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        $err_no_curl = curl_errno($ch);
        curl_close($ch);

        if ($err || $err_no_curl) {
            \Log::error("Fcm Curl Call has Error number " . $err_no_curl . " and Error is ::" . $err);
        } else {
            \Log::error("Fcm Curl Call Success has :: " . $result);

            return $result;
        }

        // return $result;
    }
}

/**
 * Created by : Pradyumn Dwivedi
 * Created at : 29-sept-2022
 * Uses: To show only first 2 and last 4 character
 */
if (!function_exists('maskPhoneNumber')) {
    function maskPhoneNumber($phoneNumber = ''){
        $phoneNumberLength = strlen($phoneNumber);
        $returnP1 = substr($phoneNumber, 0,1);
        $returnP2 = substr($phoneNumber, -4);

        $paddingLength = $phoneNumberLength - 4;
        $finalP1 = str_pad($returnP1, $paddingLength, 'X');
        $result = $finalP1.$returnP2;
        return $result;
    }
}

/**
 * Created by : Pradyumn Dwivedi
 * Created at : 29-sept-2022
 * Uses: To show only first 6 and last 4 character
 */
if (!function_exists('maskCardNumber')) {
    function maskCardNumber($cardNumber = '',$isWallet=false) {
        $cardNumberLength = strlen($cardNumber);
        $returnP1 = substr($cardNumber, 0, 6);
        $returnP2 = substr($cardNumber, -4);
        $paddingLength = $cardNumberLength - 6;
        if($isWallet){ 
        $returnP1 = substr($cardNumber, 0,0);
        $paddingLength = $cardNumberLength - 4;
        }
        $finalP1 = str_pad($returnP1, $paddingLength, 'X');
        $result = $finalP1 . $returnP2;
        return $result;
    }
}

/**
 * Created by : Pradyumn Dwivedi
 * Created at : 29-sept-2022
 * Uses: To show only first 6 and last 4 character
 */
if (!function_exists('maskVendorName')) {
    function maskVendorName($vendorName = '') {
        $vendorNameLength = strlen($vendorName);
        $returnP1 = substr($vendorName, 0,2);

        $returnP2 = substr($vendorName, -2);
        $paddingLength = $vendorNameLength - 2;

        $finalP1 = str_pad($returnP1, $paddingLength, '*');
        $result = $finalP1 . $returnP2;
        return $result;
    }
}