<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\vendorWarehouse;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Yajra\DataTables\DataTables;

class VendorWarehouseController extends Controller
{
    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 01-April-2022
       *   Uses :  To show vendor warehouse listing page
    */
    public function index(){
        $data['vendor'] = Vendor::all();
        $data['city'] = City::all();
        $data['vendor_warehouse_add'] = checkPermission('vendor_warehouse_add');
        $data['vendor_warehouse_view'] = checkPermission('vendor_warehouse_view');
        $data['vendor_warehouse_edit'] = checkPermission('vendor_warehouse_edit');
        $data['vendor_warehouse_status'] = checkPermission('vendor_warehouse_status');        
        return view('backend/vendors/vendor_warehouse/index',["data"=>$data]);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 01-April-2022
       *   Uses :  display dynamic data in datatable for vendor warehouse page
       *   @param Request request
       *   @return Response
    */
    public function fetch(Request $request){
        if ($request->ajax()) {
        	try {
	            $query = VendorWarehouse::with('vendor','city','state');              
	            return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                                            
                        if (isset($request['search']['search_warehouse_name']) && ! is_null($request['search']['search_warehouse_name'])) {
                            $query->where('warehouse_name', 'like', "%" . $request['search']['search_warehouse_name'] . "%");
                        }
                        if (isset($request['search']['search_vendor']) && ! is_null($request['search']['search_vendor'])) {
                            $query->where('vendor_id', $request['search']['search_vendor']);                           
                        }
                        if (isset($request['search']['search_city']) && ! is_null($request['search']['search_city'])) {
                            $query->where('city_id', $request['search']['search_city']);                           
                        }
                        $query->get();
                    })
	                ->editColumn('warehouse_name', function ($event) {
	                    return $event->warehouse_name;
	                })
                    ->editColumn('vendor_name', function ($event) {
	                    return $event->vendor->vendor_name;
	                })
                    ->editColumn('address', function ($event) {
	                    return $event->address;
	                })
                    ->editColumn('city', function ($event) {
	                    return $event->city->city_name;
	                })
                    ->editColumn('state', function ($event) {
	                    return $event->state->state_name;
	                })
	                ->editColumn('action', function ($event) {
                        $vendor_warehouse_view = checkPermission('vendor_warehouse_view');
	                    $vendor_warehouse_edit = checkPermission('vendor_warehouse_edit');
                        $vendor_warehouse_status = checkPermission('vendor_warehouse_status');
	                    $actions = '<span style="white-space:nowrap;">';
                        if ($vendor_warehouse_view) {
                            $actions .= '<a href="vendor_warehouse_view/' . $event->id . '" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if($vendor_warehouse_edit) {
                            $actions .= ' <a href="vendor_warehouse_edit/'.$event->id.'" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if($vendor_warehouse_status) {
                            if($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishVendorWarehouse" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishVendorWarehouse" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
	                }) 
	                ->addIndexColumn() 
	                ->rawColumns(['warehouse_name', 'vendor_name', 'address', 'city', 'state', 'action'])->setRowId('id')->make(true);
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
       *   Created On : 01-April-2022
       *   Uses : To load Add vendor warehouse page
    */
    public function add() {
        $data['vendor'] = Vendor::all();
        $data['city'] = City::all();
        $data['state'] = State::all();
        $data['country'] = Country::all();
        return view('backend/vendors/vendor_warehouse/vendor_warehouse_add',$data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 01-April-2022
       *   Uses :  To load Edit vendor warehouse page
       *   @param int $id
       *   @return Response
    */
    public function edit($id) {
        $data['data'] = VendorWarehouse::find($id);
        $data['city'] = City::all();
        $data['vendor'] = Vendor::all();
        $data['state'] = State::all();
        $data['country'] = Country::all();
        return view('backend/vendors/vendor_warehouse/vendor_warehouse_edit', $data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 22-Mar-2022
       *   Uses :  To store vendor warehouse details in table
       *   @param Request request
       *   @return Response
    */
    public function saveFormData(Request $request)
    {
    	$msg_data=array();
        $msg = "";
        $validationErrors = $this->validateRequest($request);
		if (count($validationErrors)) {
            \Log::error("Vendor Warehouse Validation Exception: " . implode(", ", $validationErrors->all()));
        	errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        
        $isEdit = false;
        $vendorData = Vendor::find($request->vendor);
        if(isset($_GET['id'])) {
            $isEdit = true;
            $response = VendorWarehouse::where([['warehouse_name',($request->warehouse_name)],['vendor_id',($request->vendor)],['id', '<>', $_GET['id']]]) ->get()->toArray();
            if(isset($response[0])){
                errorMessage($request->warehouse_name.' warehouse is Already Exist to Selected Vendor', $msg_data);
            }
            $tblObj = VendorWarehouse::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tblObj = new VendorWarehouse;
            $response = VendorWarehouse::where([['warehouse_name',($request->warehouse_name)],['vendor_id',($request->vendor)]]) ->get()->toArray();
            if(isset($response[0])){
                errorMessage($request->warehouse_name.' warehouse is Already Exist to Selected Vendor', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        $tblObj->warehouse_name = $request->warehouse_name;
        $tblObj->vendor_id = $request->vendor;
        $tblObj->city_id = $request->city;
        $tblObj->state_id = $request->state;
        $tblObj->country_id = $request->country;
        $tblObj->address = $request->address;
        $tblObj->pincode = $request->pincode;
        if($isEdit){
            $tblObj->updated_by = session('data')['id'];
        }else{
            $tblObj->created_by = session('data')['id'];
        }
        $tblObj->save();
        successMessage($msg , $msg_data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 01-April-2022
       *   Uses :  To load view vendor warehouse page
       *   @param int $id
       *   @return Response
    */
    public function view($id) {
        $data['data'] = VendorWarehouse::with('vendor','city','state','country')->find($id);
        return view('backend/vendors/vendor_warehouse/vendor_warehouse_view',$data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 01-April-2022
       *   Uses :  To publish or unpublish vendor warehouse records
       *   @param Request request
       *   @return Response
    */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = VendorWarehouse::find($request->id);
        $recordData->status = $request->status;
        $recordData->save();
        if($request->status == 1) {
        	successMessage('Published', $msg_data);
        }
        else {
        	successMessage('Unpublished', $msg_data);
        }
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 01-April-2022
       *   Uses :  Vendor warehouse Add|Edit Form Validation part will be handle by below function
       *   @param Request request
       *   @return Response
    */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'warehouse_name' => 'required|string',
	        'vendor' => 'required|integer',
            'city' => 'required|integer',
            'state' => 'required|integer',
            'country' => 'required|integer',
            'address' => 'required|string',
            'pincode' => 'required|integer'
        ])->errors();
    }
}
