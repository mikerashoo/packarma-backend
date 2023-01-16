<?php 

function testFunction(){
    return 'hi';
}


if (! function_exists('generateUserPwd')) {
    function generateUserPwd($emailId) {
        $emailString = explode('@',$emailId);
        $pwdString =  $emailString[0].time();
        $spaciaCharcters = "@#$&";
        $shufflePwdString = substr(str_shuffle($spaciaCharcters.$pwdString), 0, 10);
        //$userEncryptedPwdString = md5($emailId.$shufflePwdString);
        // echo '<pre>';
        // print_r($pwdString);
        // echo '<pre>';
        // print_r($shufflePwdString);
        // echo '<pre>';
        // print_r($userEncryptedPwdString);
        // exit();
        return $shufflePwdString;
    }
}

?>