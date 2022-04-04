<?php
use App\Models\DisplayMsg;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Storage;
use Image as thumbimage;

if (! function_exists('errorMessage')) {
    function errorMessage($msg = '', $data = array(), $expireSessionCode="") {
        $return_array = array();
        $return_array['success'] = '0';
        if($expireSessionCode != "") {
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

if (! function_exists('successMessage')) {
    function successMessage($msg = '', $data = array()) {
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

if (! function_exists('generateRandomOTP')) {
    function generateRandomOTP(){
        // return (rand(1000,9999));
        return (1234);
    }
}

if (! function_exists('readHeaderToken')) {
    function readHeaderToken() {
        $msg_data=array();
        $tokenData = Session::get('tokenData');
        $token = JWTAuth::setToken($tokenData)->getPayload();
        $userChk = User::where([['id', $token['sub']]])->get();
        if(count($userChk) == 0 || $userChk[0]->access_token != $tokenData) {
            errorMessage('please_login_and_try_again', $msg_data, 4);
        }
        return $token;
    }
}

if (! function_exists('checkPermission')) {
    function checkPermission($name) {
        if(session('data')['role_id'] == 1) {
            return true;
        }
        $permissions = Session::get('permissions');
        $permission_array = array();
        for($i=0; $i<count($permissions); $i++) {
            $permission_array[$i] = $permissions[$i]->codename;
        }
        if(in_array($name, $permission_array)) {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists('generateSeoURL'))
{
   function generateSeoURL($string, $wordLimit = 0){
        $separator = '-';
        if($wordLimit != 0){
            $wordArr = explode(' ', $string);
            $string = implode(' ', array_slice($wordArr, 0, $wordLimit));
        }
        $quoteSeparator = preg_quote($separator, '#');
        $trans = array(
            '&.+?;'                    => '',
            '[^\w\d _-]'            => '',
            '\s+'                    => $separator,
            '('.$quoteSeparator.')+'=> $separator
        );
        $string = strip_tags($string);
        foreach ($trans as $key => $val){
            $UTF8_ENABLED = config('global.UTF8_ENABLED');
            $string = preg_replace('#'.$key.'#i'.( $UTF8_ENABLED ? 'u' : ''), $val, $string);
        }
        $string = strtolower($string);
        return trim(trim($string, $separator));
    }    
}

if (! function_exists('approvalStatusArray')) {
    function approvalStatusArray($displayValue="",$allKeys = false) {
        $returnArray = array(
            'pending' => 'Pending',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected'
        );
        if(!empty($displayValue)){
            $returnArray = $returnArray[$displayValue];
        }
        if(empty($displayValue) && $allKeys){
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
if (! function_exists('displayStatus')) {
    function displayStatus($displayValue="") {
        $returnArray = array(
            '1' => 'Active',
            '0' => 'In-Active'            
        );
        if(isset($displayValue)){
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
if (! function_exists('displayFeatured')) {
    function displayFeatured($displayValue="") {
        $returnArray = array(
            '1' => 'Featured',
            '0' => 'Un-Featured'            
        );
        if(isset($displayValue)){
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
if (! function_exists('customerEnquiryType')) {
    function customerEnquiryType($displayValue="",$allKeys = false) {
        $returnArray = array(
            'general' => 'General',
            'engine' => 'Engine'            
        );
        if(!empty($displayValue)){
            $returnArray = $returnArray[$displayValue];
        }
        if(empty($displayValue) && $allKeys){
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
    if (! function_exists('customerEnquiryQuoteType')) {
        function customerEnquiryQuoteType($displayValue="" ,$allKeys = false) {
            $returnArray = array(
                'enquired' => 'Enquired',
                'map_to_vendor' => 'Map To Vendor',
                'accept_cust' => 'Accept By Customer',
                'closed' => 'Closed'
                
            );
            if(!empty($displayValue)){
                $returnArray = $returnArray[$displayValue];
            }
            if(empty($displayValue) && $allKeys){
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
    if (! function_exists('vendorEnquiryStatus')) {
        function vendorEnquiryStatus($displayValue="" ,$allKeys = false) {
            $returnArray = array(
                'mapped' => 'Mapped',
                'quoted' => 'Quoted',
                'viewed' => 'Viewed',
                'accept' => 'Accept',
                'reject' => 'Reject',
                'requote' => 'Requote',
            );
            if(!empty($displayValue)){
                $returnArray = $returnArray[$displayValue];
            }
            if(empty($displayValue) && $allKeys){
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
    if (! function_exists('subscriptionType')) {
        function subscriptionType($displayValue="",$allKeys = false) {
            $returnArray = array(
                'monthly' => 'Monthly',
                'quarterly' => 'Quarterly',
                'semi_yearly' => 'Semi Yearly',
                'yearly' => 'Yearly'
            );
            if(!empty($displayValue)){
                $returnArray = $returnArray[$displayValue];
            }
            if(empty($displayValue) && $allKeys){
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
    if (! function_exists('deliveryStatus')) {
        function deliveryStatus($displayValue="",$allKeys = false) {
            $returnArray = array(
                'pending' => 'Pending',
                'processing' => 'Processing',
                'out_for_delivery' => 'Out For Delivery',
                'delivered' => 'Delivered'
            );
            if(!empty($displayValue)){
                $returnArray = $returnArray[$displayValue];
            }
            if(empty($displayValue) && $allKeys){
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
    if (! function_exists('paymentStatus')) {
        function paymentStatus($displayValue="",$allKeys = false) {
            $returnArray = array(
                'pending' => 'Pending',
                'semi_paid' => 'Semi paid',
                'fully_paid' => 'Fully Paid'
            );
            if(!empty($displayValue)){
                $returnArray = $returnArray[$displayValue];
            }
            if(empty($displayValue) && $allKeys){
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
    if (! function_exists('subscriptionPaymentStatus')) {
        function subscriptionPaymentStatus($displayValue="",$allKeys = false) {
            $returnArray = array(
                'pending' => 'Pending',
                'paid' => 'Paid',
                'failed' => 'Failed'
            );
            if(!empty($displayValue)){
                $returnArray = $returnArray[$displayValue];
            }
            if(empty($displayValue) && $allKeys){
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
    if (! function_exists('paymentMode')) {
        function paymentMode($displayValue="",$allKeys = false) {
            $returnArray = array(
                'cod' => 'Cash on Delivery',
                'bank_transfer' => 'Bank Transfer',
                'payment_gateway' => 'Payment Gateway',
                'cheque' => 'Cheque',
                'demand_draft' => 'Demand Draft',
            );
            if(!empty($displayValue)){
                $returnArray = $returnArray[$displayValue];
            }
            if(empty($displayValue) && $allKeys){
                $returnArray = array_keys($returnArray);
            }
            return $returnArray;
        }     
    }