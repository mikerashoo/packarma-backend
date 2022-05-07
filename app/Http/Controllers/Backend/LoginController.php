<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoginController extends Controller
{
	/**
     * Created By :Ankita Singh
     * Created On : 31 Jan 2022
     * Uses : This will load login view.
    */
    public function index()	
	{
    	return view('backend/auth/login');
	}

	/**
     * Created By :Ankita Singh
     * Created On : 31 Jan 2022
     * Uses : This will login admin user.
     * @param Request $request
     * @return Response
    */
    public function login(Request $request)
    {
    	\Log::info("Logging in, starting at: " . Carbon::now()->format('H:i:s:u'));
    	try {
    		$validationErrors = $this->validateLogin($request);
    		if (count($validationErrors)) {
                \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
            	return redirect()->back()->withErrors(array("msg"=>implode("\n", $validationErrors->all())));
            }
            
            $response = Admin::with('role')->where([['email', $request->email],['password', md5($request->email.$request->password)]])->get();
            if(!count($response)) {
                \Log::error("User not found with this email id and password.");
                return redirect()->back()->withErrors(array("msg"=>"Invalid login credentials"));
            } else {
                if($response[0]['status'] == 1 ){
                    \Log::info("Login Successful!");
                    $data=array(
                        "id"=>$response[0]['id'],
                        "name"=>$response[0]['admin_name'],
                        "email"=>$request->email,
                        "role_id"=>$response[0]['role_id'],
                        "permissions"=>$response[0]['role']['permission']
                    );
                    $request->session()->put('data',$data);
                    return redirect('webadmin/dashboard');
                }else{
                    \Log::error("Account Suspended.");
                    return redirect()->back()->withErrors(array("msg"=>"Your account is deactivated."));
                }
                
            }
    	}
    	catch (\Exception $e) {
    		\Log::error("Login failed: " . $e->getMessage());
    		return redirect()->back()->withErrors(array("msg"=>"Something went wrong"));
    	}
    }
    /**
     * Validates input login
     *
     * @param Request $request
     * @return Response
     */
    public function validateLogin(Request $request)
    {
        return \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ])->errors();
    }
}
