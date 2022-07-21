<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
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

		$data['user'] = User::where('approval_status','=','accepted')->count(); 
		// $data['user'] = User::all()->count(); 
		$data['cust_reg_today'] = User::whereDate('created_at','=', date('Y-m-d'))->count();
		$data['vendor'] = Vendor::where('approval_status','=','accepted')->count(); 
		$data['vendor_reg_today'] = Vendor::whereDate('created_at','=', date('Y-m-d'))->count();
		$data['revenue'] = Order::all()->sum('grand_total');
		$data['today_sales'] = Order::whereDate('created_at','=', date('Y-m-d'))->count();
		$data['subs_renew_today'] = User::whereDate('subscription_start','=', date('Y-m-d'))->count();
		$data['subs_end_today'] = User::whereDate('subscription_end','=', date('Y-m-d'))->count();
		$data['total_orders'] = Order::all()->count();
		$data['pending_orders'] = Order::where('order_delivery_status','=','pending')->count();
		$data['processing_orders'] = Order::whereIn('order_delivery_status', ['processing', 'out_for_delivery'])->count();
		$data['completed_orders'] = Order::where('order_delivery_status','=','delivered')->count();  
    	return view('backend/dashboard/index',["data"=>$data]);
	}

}
