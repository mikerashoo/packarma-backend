<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSubscriptionPayment;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class UserSubscriptionPaymentController extends Controller
{
     /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 10-Mar-2022
       *   Uses :  To show user subscription paymennt listing page  
    */
    public function index() 
    {   
        $data['user'] = User::all();
        $data['subscriptionType'] = subscriptionType();
        $data['user_subscription_payment_view'] = checkPermission('user_subscription_payment_view');
        return view('backend/user_subscription_payment/index',['data' =>$data] ); 
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 10-Mar-2022
       *   Uses :  display dynamic data in datatable for review page  
       *   @param Request request
       *   @return Response    
    */
    public function fetch(Request $request){
        if ($request->ajax()) {
        	try {
	            $query = UserSubscriptionPayment::with('user')->orderBy('updated_at','desc');                
	            return DataTables::of($query) 
                    ->filter(function ($query) use ($request) {                        
                        if ($request['search']['search_user_name'] && ! is_null($request['search']['search_user_name'])) {
                            $query->where('user_id', $request['search']['search_user_name']);
                        }
                        if (isset($request['search']['search_subscription_type']) && ! is_null($request['search']['search_subscription_type'])) {
                            $query->where('subscription_type', 'like', "%" . $request['search']['search_subscription_type'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('name', function ($event) {
	                    return $event->user->name;                        
	                }) 
                    ->editColumn('subscription_type', function ($event) {
	                    return subscriptionType($event->subscription_type);                        
	                }) 
                    ->editColumn('payment_mode', function ($event) {
	                    return onlinePaymentMode($event->payment_mode);
	                })
                    ->editColumn('payment_status', function ($event) {
	                    return subscriptionPaymentStatus($event->payment_status);
	                })
                    ->editColumn('subscription_start', function ($event) {
	                    return date('d-m-Y', strtotime($event->user->subscription_start));                        
	                })
                    ->editColumn('subscription_end', function ($event) {
	                    return date('d-m-Y', strtotime($event->user->subscription_end));                        
	                })
                    ->editColumn('action', function ($event) {
                        $user_subscription_payment_view = checkPermission('user_subscription_payment_view');
	                    $actions = '<span style="white-space:nowrap;">';
                        if($user_subscription_payment_view) {
                            $actions .= '<a href="user_subscription_payment_view/'.$event->id.'" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        $actions .= '</span>';
                        return $actions;
	                })   
                ->addIndexColumn()                
                ->rawColumns(['name','subscription_type','payment_mode','payment_status','subscription_start','subscription_end','action'])->setRowId('id')->make(true);
	        }
	        catch (\Exception $e) {
	    		\Log::error("Something Went Wrong. Error: " . $e->getMessage());
	    		return response([
	                'draw'            => 0,
	                'recordsTotal'    => 0,
	                'recordsFiltered' => 0,
	                'data'            => [],
	                'error'           => 'Something went wrong',
	            ]);
	    	}
        }
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 28-Feb-2022
       *   Uses :  To view review  
       *   @param int $id
       *   @return Response
    */
    public function view($id) {
        $data['data'] = UserSubscriptionPayment::with('user')->find($id);
        return view('backend/user_subscription_payment/user_subscription_payment_view',$data);
    }
}
