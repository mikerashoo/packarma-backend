<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;

class DashboardController extends Controller
{
	public function index_phpinfo()
    {
		$laravel = app();
      	echo 'Curl: ', function_exists('curl_version') ? 'Enabled' . "\xA" : 'Disabled' . "\xA";
		echo ' || Laravel Version is : '.$laravel::VERSION;
		phpinfo();
		exit;
    }

    public function index()	
	{
		// $generatedPwd = generateUserPwd('prad@gmail.com');
		// // $password =  md5($email.$generatedPwd);
		// print_r($generatedPwd);
		// exit;

		$data['subscription_end'] = User::whereDate('subscription_end','=', date('Y-m-d'))->count();
		$data['subscription_start'] = User::whereDate('subscription_start','=', date('Y-m-d'))->count();
		$data['user'] = User::all()->count();  
		$data['revenue'] = Order::all()->sum('grand_total');
		// if($data['rev'] == '50000'){
		// 	$data['revenue'] = '50K';
		// }elseif($data['rev'] == 100000){
		// 	$data['revenue'] = '1Lac';
		// }

    	return view('backend/dashboard/index',["data"=>$data]);
	}

}
