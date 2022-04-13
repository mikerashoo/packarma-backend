<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\City;
use App\Models\User;
use App\Models\State;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductForm;
use App\Models\PackingType;
use App\Models\CustomerEnquiry;
use App\Models\VendorQuotation;
use App\Models\VendorWarehouse;
use Yajra\DataTables\DataTables;
use App\Models\PackagingMachine;
use App\Models\StorageCondition;
use App\Models\PackagingTreatment;

class CustomerEnquiryController extends Controller
{
    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 04-April-2022
       *   Uses :  To show customer Enquiry listing page
    */
    public function index(){ 
        $data['user'] = User::all();    
        $data['enquiryType'] = customerEnquiryType();
        $data['quoteType'] = customerEnquiryQuoteType();
        $data['customer_enquiry_add'] = checkPermission('customer_enquiry_add');
        $data['customer_enquiry_edit'] = checkPermission('customer_enquiry_edit');  
        $data['customer_enquiry_view'] = checkPermission('customer_enquiry_view');        
        $data['customer_enquiry_map_to_vendor'] = checkPermission('customer_enquiry_map_to_vendor');          
        return view('backend/customer_section/customer_enquiry/index',["data"=>$data]);
    }
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses : To load  customer enquiry data using datatables in custuomer enquiry listing page
     */
    public function fetch(Request $request){
        if ($request->ajax()) {
        	try {
	            $query = CustomerEnquiry::with('product','state','city','user','country','vendor_quotation')->orderBy('updated_at','desc');                
	            return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_description']) && ! is_null($request['search']['search_description'])) {
                            $query->where('description', 'like', "%" . $request['search']['search_description'] . "%");
                        }
                        if (isset($request['search']['search_user_name']) && ! is_null($request['search']['search_user_name'])) {
                            $query->where('user_id', $request['search']['search_user_name']);                           
                        }
                        if (isset($request['search']['search_enquiry_type']) && ! is_null($request['search']['search_enquiry_type'])) {
                            $query->where('enquiry_type', 'like', "%" . $request['search']['search_enquiry_type'] . "%");
                        }                        
                        if (isset($request['search']['search_quote_type']) && ! is_null($request['search']['search_quote_type'])) {
                            $query->where('quote_type', 'like', "%" . $request['search']['search_quote_type'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('description', function ($event) {
	                    return $event->description;                        
	                })
                    ->editColumn('name', function ($event) {
	                    return $event->user->name;                        
	                })
                    ->editColumn('order_id', function ($event) {
	                    return $event->order_id;                        
	                })
                    ->editColumn('enquiry_type', function ($event) {
	                    return customerEnquiryType($event->enquiry_type);
	                })
                    ->editColumn('quote_type', function ($event) {
	                    return customerEnquiryQuoteType($event->quote_type);
	                })
                    ->editColumn('updated_at', function ($event) {
	                    return date('d-m-Y H:i A', strtotime($event->updated_at));                        
	                })                                     
	                ->editColumn('action', function ($event) {
                        $customer_enquiry_edit = checkPermission('customer_enquiry_edit');
	                    $customer_enquiry_view = checkPermission('customer_enquiry_view');
                        $customer_enquiry_map_to_vendor = checkPermission('customer_enquiry_map_to_vendor');
                        $actions = '<span style="white-space:nowrap;">';
                        if($customer_enquiry_view) {
                            $actions .= '<a href="customer_enquiry_view/'.$event->id.'" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        //finding vendor enquiry count
                        $vendor_quotation_count = VendorQuotation::where('customer_enquiry_id', '=', $event->id)->get()->count();
                        if($vendor_quotation_count == 0){
                            if($customer_enquiry_map_to_vendor) {
                                $actions .= ' <a href="customer_enquiry_map_to_vendor/'.$event->id.'" class="btn btn-info btn-sm src_data" title="Map Vender"><i class="fa ft-zap"></i></a>';
                            }
                        }                             
                        $actions .= '</span>';                 
                        return $actions;
	                }) 
	                ->addIndexColumn()
	                ->rawColumns(['description','name','order_id','enquiry_type','enquiry_quote','updated_at','action'])->setRowId('id')->make(true);
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
     *   Uses : To load add customer enquiry page
     */
    public function addCustomerEnquiry()
    {
        $data['city'] = City::all();
        $data['user'] = User::all();
        $data['state'] = State::all();
        $data['vendor'] = Vendor::all();
        $data['country'] = Country::all();
        $data['product'] = Product::all();
        $data['category'] = Category::all();
        $data['sub_category'] = SubCategory::all();
        $data['product_form'] = ProductForm::all();
        $data['packing_type'] = PackingType::all();
        $data['packaging_machine'] = PackagingMachine::all();
        $data['storage_condition'] =StorageCondition::all();
        $data['packaging_treatment'] =PackagingTreatment::all();
        $data['quote_type'] = customerEnquiryQuoteType();
        return view('backend/customer_section/customer_enquiry/customer_enquiry_add', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  To store add new customer enquiry in table
     *   @param Request request
     *   @return Response
     */

    public function saveCustomerEnquiryFormData(Request $request)
    {
        $msg_data=array();
        $msg = "";
        $validationErrors = $this->validateRequest($request,'addEnquiry');
		if (count($validationErrors)) {
            \Log::error("Customer Enquiry Validation Exception: " . implode(", ", $validationErrors->all()));
        	errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $tblObj = new CustomerEnquiry;
        $msg = "Data Saved Successfully";
        $tblObj->description = $request->description;
        $tblObj->user_id = $request->user;
        $tblObj->order_id = $request->order_id;
        $tblObj->category_id = $request->category;
        $tblObj->sub_category_id = $request->sub_category;
        $tblObj->product_id = $request->product;
        $tblObj->shelf_life = $request->shelf_life;
        $tblObj->product_weight = $request->product_weight;
        $tblObj->storage_condition_id = $request->storage_condition;
        $tblObj->packaging_machine_id = $request->packaging_machine;
        $tblObj->product_form_id = $request->product_form;
        $tblObj->packing_type_id = $request->packing_type;
        $tblObj->packaging_treatment_id = $request->packaging_treatment;
        $tblObj->quantity = $request->quantity;
        $tblObj->quote_type = $request->quote_type;
        $tblObj->country_id = $request->country;
        $tblObj->state_id = $request->state;
        $tblObj->city_id = $request->city;
        $tblObj->pincode = $request->pincode;
        $tblObj->address = $request->address;
        $tblObj->save();
        successMessage($msg , $msg_data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 04-April-2022
       *   Uses :  To load customer enquiry map to vender
       *   @param int $id
       *   @return Response
    */
    public function customerEnquiryMapToVendor($id) {
        $data['data'] = CustomerEnquiry::find($id);       
        $data['customerEnquiryType'] = customerEnquiryType();
        $data['vendorEnquiryStatus'] = vendorEnquiryStatus();
        $data['customerEnquiryQuoteType'] = customerEnquiryQuoteType();
        $data['vendor'] = Vendor::all()->toArray();
        $data['warehouse'] = VendorWarehouse::all()->toArray();        
        $data['city'] = City::all();
        $data['state'] = State::all(); 
        return view('backend/customer_section/customer_enquiry/customer_enquiry_map_to_vendor', $data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 04-April-2022
       *   Uses :  To store customer enquiry map to vendor 
       *   @param Request request
       *   @return Response
    */
    public function saveFormDataVendor(Request $request)
    {
    	$msg_data=array();
        $msg = "";
        $validationErrors = $this->validateRequest($request,'vendor');
		if (count($validationErrors)) {
            \Log::error("Cutomer Enquire Map To Vendor Validation Exception: " . implode(", ", $validationErrors->all()));
        	errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $msg = "Vendor Mapped successfully to Enquiry";
        $vendorArr = $request->vendor;
        foreach($vendorArr as $k => $val){
            $tblObj = new VendorQuotation;
            $tblObj->user_id = $request->user[$k];
            $tblObj->customer_enquiry_id = $request->customer_enquiry_id[$k];
            $tblObj->product_id = $request->product[$k];
            $tblObj->vendor_id = $val;
            $tblObj->vendor_warehouse_id= $request->warehouse[$k];
            $tblObj->vendor_price =  $request->vendor_price[$k];
            $tblObj->commission_amt =  $request->commission_rate[$k];
            //storing quotation validity in variable for increasing current time with validity hours
            $validity_hours =  $request->quotation_validity[$k];
            $currentDateTime = Carbon::now();
            $newDateTime = Carbon::now()->addHours($validity_hours)->toArray();
            $tblObj->quotation_expiry_datetime =  $newDateTime['formatted'];
            $tblObj->etd =  $request->etd[$k];
            $tblObj->etd =  $request->etd[$k];
            $tblObj->created_by = session('data')['id'];
            $tblObj->save();
        }
        successMessage($msg , $msg_data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 02-Feb-2022
       *   Uses :  To view customer enquiry  
       *   @param int $id
       *   @return Response
    */
    public function view($id) {
        $data['data'] = CustomerEnquiry::find($id);
        $data['vendor_id'] = VendorQuotation::where('customer_enquiry_id', '=', $data['data']->id)->pluck('vendor_id')->toArray();
        $data['vendor'] = Vendor::whereIn('id', $data['vendor_id'])->get();
        return view('backend/customer_section/customer_enquiry/customer_enquiry_view', $data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 02-mar-2022
       *   Uses :  Form Validation part will be handle by below function
       *   @param Request request
       *   @return Response
    */
    private function validateRequest(Request $request, $for="")
    {
        if( $for == 'vendor'){
            return \Validator::make($request->all(), [    
                'vendor.*' => 'required',
                'warehouse.*' => 'required',
                'vendor_price.*' => 'required',
                'commission_rate.*' => 'required',
                'freight_price.*' => 'required',
                'quotation_validity.*' => 'required',
                'etd.*' => 'required',
            ])->errors(); 
        } elseif( $for == 'addEnquiry'){
            return \Validator::make($request->all(), [    
                'description' => 'required|string',
                'user' => 'required|integer',
                'order_id' => 'required|integer',
                'category' => 'required|integer',
                'sub_category' => 'required|integer',
                'product' => 'required|integer',
                'shelf_life' => 'required|integer',
                'product_weight' => 'required|numeric',
                'storage_condition' => 'required|integer',
                'packaging_machine' => 'required|integer',
                'product_form' => 'required|integer',
                'packing_type' => 'required|integer',
                'packaging_treatment' => 'required|integer',
                'quantity' => 'required|integer',
                'quote_type' => 'required|string',
                'country' => 'required|integer',
                'state' => 'required|integer',
                'city' => 'required|integer',
                'pincode' => 'required|integer',
                'address' => 'required|string'
                ])->errors(); 
        }
        else{
            return \Validator::make($request->all(), [])->errors();
        }
    }
}