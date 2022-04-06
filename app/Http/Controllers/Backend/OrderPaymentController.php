<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderPayment;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use Yajra\DataTables\DataTables;

class OrderPaymentController extends Controller
{
    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 04-April-2022
       *   Uses :  To show order paymennt listing page  
    */
    public function index() 
    {   
        $data['user'] = User::all();
        $data['vendor'] = Vendor::all();
        $data['paymentMode'] = paymentMode();
        $data['paymentStatus'] = paymentStatus();
        $data['order_payment_view'] = checkPermission('order_payment_view');
        return view('backend/order/order_payment_list/index',['data' =>$data] ); 
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 04-April-2022
       *   Uses :  display dynamic data in datatable for order payment page  
       *   @param Request request
       *   @return Response    
    */
    public function fetch(Request $request){
        if ($request->ajax()) {
        	try {
	            $query = OrderPayment::with('user','vendor')->orderBy('updated_at','desc');                
	            return DataTables::of($query) 
                    ->filter(function ($query) use ($request) {                        
                        if ($request['search']['search_user_name'] && ! is_null($request['search']['search_user_name'])) {
                            $query->where('user_id', $request['search']['search_user_name']);
                        }
                        if ($request['search']['search_vendor_name'] && ! is_null($request['search']['search_vendor_name'])) {
                            $query->where('vendor_id', $request['search']['search_vendor_name']);
                        }
                        $query->get();
                    })
                    ->editColumn('user_name', function ($event) {
	                    return $event->user->name;                        
	                })
                    ->editColumn('vendor_name', function ($event) {
	                    return $event->vendor->vendor_name;                        
	                })
                    ->editColumn('product_name', function ($event) {
	                    return $event->product->product_name;                        
	                }) 
                    ->editColumn('payment_mode', function ($event) {
	                    return paymentMode($event->payment_mode);
	                })
                    ->editColumn('payment_status', function ($event) {
	                    return paymentStatus($event->payment_status);
	                })
                    ->editColumn('updated_at', function ($event) {
	                    return date('d-m-Y H:i A', strtotime($event->updated_at));                        
	                })
                    ->editColumn('action', function ($event) {
                        $order_payment_view = checkPermission('order_payment_view');
	                    $actions = '<span style="white-space:nowrap;">';
                        if($order_payment_view) {
                            $actions .= '<a href="order_payment_view/'.$event->id.'" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        $actions .= '</span>';
                        return $actions;
	                })   
                ->addIndexColumn()                
                ->rawColumns(['user_name','vendor_name','product_name','payment_mode','payment_status','updated_at','action'])->setRowId('id')->make(true);
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
       *   Created On : 04-April-2022
       *   Uses :  To view order payment  
       *   @param int $id
       *   @return Response
    */
    public function view($id) {
        $data['data'] = OrderPayment::with('user','vendor','product')->find($id);
        return view('backend/order/order_payment_list/order_payment_view',$data);
    }
}
