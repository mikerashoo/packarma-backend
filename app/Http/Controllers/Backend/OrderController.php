<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Grade;
use App\Models\Vendor;
use App\Models\OrderPayment;
use Yajra\DataTables\DataTables;


class OrderController extends Controller
{
    // public $emptyDate = null; //'0000-00-00 00:00:00';
    /**
     *  created by : Shiram Mishra
     *   Created On : 23-Feb-2022
     *   Uses :  To show Contactus  listing page
     */
    public function index()
    {
        $data['data'] = Grade::all();
        $data['user'] = User::all();
        $data['vendor'] = Vendor::all();
        $data['paymentStatus'] = paymentStatus();
        $data['deliveryStatus'] = deliveryStatus();
        $data['order_edit'] = checkPermission('order_edit');
        $data['order_view'] = checkPermission('order_view');
        $data['order_payment_update'] = checkPermission('order_payment_update');
        return view('backend/order/index',["data"=>$data]);
    }

      /**
     *   created by : Shriram Mishra
     *   Created On : 23-Feb-2022
     *   Uses :  display dynamic data in datatable for Contactus  page
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Order::with('grade','user','vendor')->orderBy('updated_at','desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_user_id']) && ! is_null($request['search']['search_user_id'])) {
                            $query->where('user_id', $request['search']['search_user_id']);                           
                        }
                        if (isset($request['search']['search_vendor_id']) && ! is_null($request['search']['search_vendor_id'])) {
                            $query->where('vendor_id', $request['search']['search_vendor_id']);                           
                        }
                        if (isset($request['search']['search_delivery_status']) && ! is_null($request['search']['search_delivery_status'])) {
                            $query->where('order_delivery_status', 'like', "%" . $request['search']['search_delivery_status'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('user_name', function ($event) {
                        return $event->user->name;
                     })
                    ->editColumn('vendor_name', function ($event) {
                        return $event->vendor->vendor_name;
                    })
                    ->editColumn('grand_total', function ($event) {
                        return $event->grand_total;
                    })
                    ->editColumn('order_delivery_status', function ($event) {
                        return deliveryStatus($event->order_delivery_status);
                    })
                    ->editColumn('payment_status', function ($event) {
                        return paymentStatus($event->payment_status);
                    })
                    ->editColumn('updated_at', function ($event) {
	                    return date('d-m-Y H:i A', strtotime($event->updated_at));                        
	                })
                    ->editColumn('action', function ($event) {
                        $order_edit = checkPermission('order_edit');
                        $order_view = checkPermission('order_view');
                        $order_payment_update = checkPermission('order_payment_update');
                        $actions = '<span style="white-space:nowrap;">';
                        if($order_view) {
                            $actions .= '<a href="order_view/'.$event->id.'" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if($event->order_delivery_status != "delivered"){
                            if ($order_edit) {
                                $actions .= '  <a href="orderEdit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update Delivery"><i class="fa fa-edit"></i></a>';
                            }
                        }
                        if($event->pending_payment != 0){
                            if ($order_payment_update) {
                                $actions .= '  <a href="orderPaymentUpdate/' . $event->id . '" class="btn btn-secondary btn-sm src_data" title="Update Payment"><i class="fa fa-money"></i></a>';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['user_name','	vendor_name','grand_total', 'order_delivery_status','payment_status','updated_at','action'])->setRowId('id')->make(true);
                }
            catch (\Exception $e) {
                \Log::error("Something Went Wrong. Error: " . $e->getMessage());
                return response([
                    'draw' => 0,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'Something went wrong',
                ]);
            }
        }
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 03-Mar-2022
       *   Uses :  To load update order delivery status page
       *   @param int $id
       *   @return Response
    */
    public function editOrder($id) {
        $data['data'] = Order::with('user','grade')->find($id);
        $data['deliveryStatus'] = deliveryStatus();
        $data['paymentStatus'] = paymentStatus();
        return view('backend/order/order_edit',$data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 04-Mar-2022
       *   Uses :  To store order delivery status in table
       *   @param Request request
       *   @return Response
    */
    public function updateDeliveryStatus(Request $request)
    {
    	$msg_data=array();
        $msg = "";
        $validationErrors = $this->validateRequest($request);
		if (count($validationErrors)) {
            \Log::error("Delivery Status Validation Exception: " . implode(", ", $validationErrors->all()));
        	errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        if(isset($_GET['id'])) {
            $getKeys = true;
            $deliveryStatus = deliveryStatus('',$getKeys);
            if (in_array( $request->order_delivery_status, $deliveryStatus))
             {
                $tableObject = Order::find($_GET['id']);            
                $msg = "Delivery Status Updated Successfully";
            }
            else{
                errorMessage('Delivery Status Does not Exists.', $msg_data);
            }
        } 
        $tableObject->order_delivery_status = $request->order_delivery_status;
        if($request->order_delivery_status ==  'processing') {
            $tableObject->processing_datetime = date('Y-m-d H:i:s');
        }
        if($request->order_delivery_status ==  'out_for_delivery') {
            $tableObject->out_for_delivery_datetime = date('Y-m-d H:i:s');
        }
        if($request->order_delivery_status ==  'delivered') {
            $tableObject->delivery_datetime = date('Y-m-d H:i:s');
        }
        $tableObject->updated_at = date('Y-m-d H:i:s');
        $tableObject->updated_by =  session('data')['id'];
        $tableObject->save();
        successMessage($msg , $msg_data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 08-Mar-2022
       *   Uses :  To load update order payment status page
       *   @param int $id
       *   @return Response
    */
    public function updateOrderPayment($id) {
        $data['data'] = Order::with('user','grade','vendor')->find($id);
        $data['deliveryStatus'] = deliveryStatus();
        $data['paymentStatus'] = paymentStatus();
        $data['paymentMode'] = paymentMode();
        return view('backend/order/order_payment_status_update',$data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 08-Mar-2022
       *   Uses :  To store order delivery status in table
       *   @param Request request
       *   @return Response
    */
    public function updatePaymentStatus(Request $request)
    {
    	$msg_data=array();
        $msg = "";
        $validationErrors = $this->validatePaymentRequest($request);
		if (count($validationErrors)) {
            \Log::error("Payment Status Validation Exception: " . implode(", ", $validationErrors->all()));
        	errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $orderData = Order::find($_GET['id']);
        if(isset($_GET['id'])) {
            $getKeys = true;
            $paymentStatus = paymentStatus('',$getKeys);
            $paymentMode = paymentMode('',$getKeys);
            if (in_array( $request->payment_status, $paymentStatus) && in_array( $request->payment_mode, $paymentMode))
             {
                $tableObject = OrderPayment::find($_GET['id']);
                if($request->payment_status == 'pending'){
                    errorMessage('Order is Already in Pending Status, Please Select Another Status', $msg_data);  
                } elseif($request->amount == 0){
                    errorMessage('Entered Amount Should be Greater Than Zero', $msg_data);
                } elseif(($request->payment_status == 'fully_paid') && ($request->amount != $orderData->pending_payment )){
                    errorMessage('Please Enter Proper Amount for Seletcted Status.', $msg_data);
                } elseif(($request->payment_status == 'semi_paid') && ($request->amount == $orderData->pending_payment )){
                    errorMessage('Please Select Proper Status for Entered Amount.', $msg_data);
                } elseif($orderData->pending_payment >= $request->amount){
                    $msg = "Payment Status Updated Successfully";
                } else{
                    errorMessage('Amount Should be Less Than or Equal To Pending Payment', $msg_data);
                } 
            }
            else{
                errorMessage('Payment Status Does not Exists.', $msg_data);
            }
        }
        $tableObject  = new OrderPayment;            
        $tableObject->user_id = $request->user_id;
        $tableObject->order_id = $_GET['id'];
        $tableObject->grade_id = $request->grade_id;
        $tableObject->vendor_id = $request->vendor_id;
        $tableObject->payment_mode = $request->payment_mode;
        $tableObject->payment_status = $request->payment_status;
        $tableObject->amount = $request->amount;
        $tableObject->payment_status = $request->payment_status;
        if($request->hasfile('order_image')) {
            $file=$request->file('order_image');
            $extention=$file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('storage/app/public/uploads/order_payment/', $filename);
            $tableObject->order_image = $filename;
        }
        $tableObject->created_at = date('Y-m-d H:i:s');
        $tableObject->created_by =  session('data')['id'];
        $tableObject->save();
        //decreasing pending_payment by amount in order table
        Order::where('id',  $_GET['id'])->decrement('pending_payment', $request->amount);
        if(($request->payment_status == 'fully_paid') && ($request->amount == $orderData->pending_payment )){
            Order::where("id", '=',  $_GET['id'])->update(['payment_status'=> 'fully_paid']);
            successMessage($msg , $msg_data);
        }
        successMessage($msg , $msg_data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 28-Feb-2022
       *   Uses :  To view review  
       *   @param int $id
       *   @return Response
    */
    public function viewOrder($id) {
        $data['data'] = Order::with('user','grade','vendor')->find($id);
        return view('backend/order/order_view',$data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 04-Mar-2022
       *   Uses :  delivery status Form Validation part will be handle by below function
       *   @param Request request
       *   @return Response
    */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'order_delivery_status' => 'string|required', 
        ])->errors();
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 04-Mar-2022
       *   Uses :  payment status Form Validation part will be handle by below function
       *   @param Request request
       *   @return Response
    */
    private function validatePaymentRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'payment_status' => 'required|string',
            'payment_mode' => 'required|string',
            'amount' => 'required|numeric',
        ])->errors();
    }
}
