<?php
/*
    *	Developed by : Pradyumn Dwivedi - Mypcot Infotech 
    *	Project Name : Packult 
    *	File Name : VendorController.php
    *	File Path : app\Http\Controllers\Backend\VendorController.php
    *	Created On : 25-03-2022
    *	http ://www.mypcot.com
*/
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\VendorMaterialMapping;
use App\Models\PackagingMaterial;
use Yajra\DataTables\DataTables;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Support\Facades\Crypt;


class VendorController extends Controller
{
    public $emptyDate = '0000-00-00 00:00:00';
    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 25-Mar-2022
       *   Uses :  To show vendor listing page
    */
    public function index(){
        $data['vendor_add'] = checkPermission('vendor_add');
        $data['vendor_view'] = checkPermission('vendor_view');
        $data['vendor_edit'] = checkPermission('vendor_edit');
        $data['vendor_status'] = checkPermission('vendor_status');
        $data['vendor_material_map'] = checkPermission('vendor_material_map');
        return view('backend/vendor/index',["data"=>$data]);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 25-Mar-2022
       *   Uses :  display dynamic data in datatable for vendor page
       *   @param Request request
       *   @return Response
    */
    public function fetch(Request $request){
        if ($request->ajax()) {
        	try {
	            $query = Vendor::select('*')->orderBy('updated_at','desc');
	            return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if ($request['search']['search_vendor_name'] && ! is_null($request['search']['search_vendor_name'])) {
                            $query->where('vendor_name', 'like', "%" . $request['search']['search_vendor_name'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('mark_featured', function ($event) {
                        $vendor_edit = checkPermission('vendor_edit');
                        $featured = '';
                        if($vendor_edit) {
                            if($event->is_featured == '1') {
                                $featured .= ' <input type="checkbox" data-url="featuredVendor" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery" checked>';
                            } else {
                                $featured .= ' <input type="checkbox" data-url="featuredVendor" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery">';
                            }
                        }else{
                            $db_featured = $event->is_featured;
                            $bg_class = 'bg-light-danger';
                            if($db_featured == '1'){
                                $bg_class = 'bg-light-success';
                            }
                            $displayFeaturedStatus = displayFeatured($db_featured);
                            $featured = '<span class=" badge badge-pill '.$bg_class.' mb-2 mr-2">'. $displayFeaturedStatus.'</span>'; 
                        }
	                    return $featured;
	                })
                    ->editColumn('vendor_name', function ($event) {
	                    return $event->vendor_name;
	                })
                    ->editColumn('vendor_approval_status', function ($event) {
                        $db_approval_status = $event->approval_status;
                        $bg_class = 'bg-danger';
                        if($db_approval_status == 'accepted'){
                            $bg_class = 'bg-success';
                        }else if($db_approval_status == 'rejected'){
                            $bg_class = 'bg-danger';
                        }else{
                            $bg_class = 'bg-warning';
                        }
                        $displayStatus = approvalStatusArray($db_approval_status);
                        $approvalStatus = '<span class="'.$bg_class.' text-center rounded p-1 text-white">'. $displayStatus.'</span>';
                        return $approvalStatus;                        
    
                    })
	                ->editColumn('action', function ($event) {
                        $vendor_view = checkPermission('vendor_view');
                        $vendor_edit = checkPermission('vendor_edit');
	                    $vendor_status = checkPermission('vendor_status');
	                    $vendor_material_map = checkPermission('vendor_material_map');
                        $actions = '<span style="white-space:nowrap;">';
                        if($vendor_view) {
                            $actions .= '<a href="vendor_view/'.$event->id.'" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if($vendor_edit) {
                            $actions .= ' <a href="vendor_edit/'.$event->id.'" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                            $actions .= ' <a href="vendor_approval/'.$event->id.'" class="btn btn-info btn-sm src_data" title="Approval"><i class="fa fa-check"></i></a>';
                        }
                        if ($vendor_material_map) {
                            $actions .= ' <a href="vendor_material_map?id='.Crypt::encrypt($event->id).'" class="btn btn-secondary btn-sm" title="Map Material"><i class="fa ft-zap"></i></a>';
                        }
                        $actions .= '</span>';
                        return $actions;
	                }) 
	                ->addIndexColumn()
	                ->rawColumns(['vendor_name','vendor_approval_status','mark_featured','action'])->setRowId('id')->make(true);
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
       *   Created On : 25-mar-2022
       *   Uses : To load Add vendor page
    */
    public function add() {
        $data['country'] = Country::all();
        $data['currency'] = Currency::all();
        return view('backend/vendor/vendor_add',$data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 25-Mar-2022
       *   Uses :  
       *   @param int $id
       *   @return Response
    */
    public function edit($id) {
        $data['data'] = Vendor::find($id);
        $data['country'] = Country::all();
        $data['currency'] = Currency::all();
        return view('backend/vendor/vendor_edit',$data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 25-Mar-2022
       *   Uses :  To load view vendor page
       *   @param int $id
       *   @return Response
    */
    public function view($id) {
        $data['data'] = Vendor::with('country')->find($id);
        $data['vendorGradeMapping'] = VendorMaterialMapping::with('material')->where('vendor_id', '=', $id)->get();
        return view('backend/vendor/vendor_view',$data);
        
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 25-Mar-2022
       *   Uses : to save add|Edit Vendor details
       *   @param Request request
       *   @return Response
    */
    public function saveFormData(Request $request)
    {
    	$msg_data=array();
        $msg = "";
        $validationErrors = $this->validateRequest($request);
		if (count($validationErrors)) {
            \Log::error("Vendor Validation Exception: " . implode(", ", $validationErrors->all()));
        	errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isEditFlow = false;
        if(isset($_GET['id'])) {
            $isEditFlow = true;
            $response = Vendor::where([['vendor_name', strtolower($request->vendor_name)],['id', '<>', $_GET['id']]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('Vendor Name Already Exist', $msg_data);
            }
            $CheckPhoneresponse = Vendor::where([['country_id',$request->country_phone_code],['phone', $request->phone],['id', '<>', $_GET['id']]])->get()->toArray();
            if(isset($CheckPhoneresponse[0])){
                errorMessage('Vendor Phone Already Exist', $msg_data);
            }
            $tblObj = Vendor::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tblObj = new Vendor;
            $response = Vendor::where([['vendor_name',strtolower($request->vendor_name)]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('Vendor Name Already Exist', $msg_data);
            }
            $CheckPhoneresponse = Vendor::where([['phone_country_id',($request->country_phone_code)],['phone',$request->phone]])->get()->toArray();
            if(isset($CheckPhoneresponse[0])){
                errorMessage('Vendor Phone Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        $maxPhoneCodeLength = Country::where('id', $request->phone_country_code)->get()->toArray();
        $allowedPhoneLength = $maxPhoneCodeLength[0]['phone_length'];
        if(strlen($request->phone) != $allowedPhoneLength){
            errorMessage("Phone Number Should be $allowedPhoneLength digit long.", $msg_data);
        }
        if(empty($request->whatsapp_phone_code)){
            $maxPhoneCodeLength = Country::where('id', $request->whatsapp_country_code)->get()->toArray();
            $allowedPhoneLength = $maxPhoneCodeLength[0]['phone_length'];
            if(strlen($request->whatsapp_no) != $allowedPhoneLength){
                errorMessage("Whatsapp Number Should be $allowedPhoneLength digit long.", $msg_data);
            }
            $tblObj->whatsapp_country_id = $request->whatsapp_country_code;
            $tblObj->whatsapp_no = $request->whatsapp_no;
        }
        $tblObj->vendor_name = $request->vendor_name;
        $tblObj->vendor_email = $request->vendor_email;
        $tblObj->vendor_address = $request->vendor_address;
        $tblObj->pincode = $request->pincode;
        $tblObj->phone_country_id = $request->phone_country_code;
        $tblObj->phone = $request->phone;
        $tblObj->currency_id = $request->currency;
        if($isEditFlow){
            $tblObj->updated_by = session('data')['id'];
        }else{
            $tblObj->approved_on = date('Y-m-d H:i:s');
            $tblObj->approved_by = session('data')['id'];
            $tblObj->approval_status = 'accepted';
            $tblObj->created_by = session('data')['id'];
        }
        $tblObj->save();
        $last_inserted_id = $tblObj->id;
        successMessage($msg , $msg_data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 25-Mar-2022
       *   Uses :  To publish or unpublish Vendor records
       *   @param Request request
       *   @return Response
    */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = Vendor::find($request->id);
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
       *   Created On : 25-Mar-2022
       *   Uses :  To load vendor approval flow
       *   @param int $id
       *   @return Response
    */
    public function vendorApproval($id) {
        $data['data'] = Vendor::find($id);       
        $data['approvalArray'] = approvalStatusArray();
        return view('backend/vendor/vendor_approval',$data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 25-Mar-2022
       *   Uses :  To update Vendor Approval Status
       *   @param Request request
       *   @return Response
    */
    public function updateApprovalStatus(Request $request)
    {
    	$msg_data=array();
        $msg = "";
        $validationErrors = $this->validateRequest($request,'approval');
		if (count($validationErrors)) {
            \Log::error("Vendor Approval Validation Exception: " . implode(", ", $validationErrors->all()));
        	errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        if(isset($_GET['id'])) {
            $getKeys = true;
            $approvalStatusArray = approvalStatusArray('',$getKeys);
            if (in_array( $request->approval_status, $approvalStatusArray)){
                $tableObject = Vendor::find($_GET['id']);            
                $msg = "Approval Status Updated Successfully";
            }else{
                errorMessage('Approval Status Does not Exists.', $msg_data);
            }
        } 
        $tableObject->approval_status = $request->approval_status;
        $tableObject->approved_on = date('Y-m-d H:i:s');
        $tableObject->approved_by =  session('data')['id'];
        $tableObject->admin_remark = '';
        if($request->approval_status ==  'rejected' && !empty($request->admin_remark)) {
            $tableObject->admin_remark = $request->admin_remark;
        }
        $tableObject->save();
        successMessage($msg , $msg_data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 25-Mar-2022
       *   Uses :  Vendor Add|Edit Form Validation part will be handle by below function
       *   @param Request request
       *   @return Response
    */
    private function validateRequest(Request $request,$flow='normal')
    {
        if($flow ==  'approval'){
            return \Validator::make($request->all(), [
                'approval_status' => 'required',
            ])->errors();
        } else{
            return \Validator::make($request->all(), [
                'vendor_name' => 'required|string',
                'vendor_email' => 'required|string',
                'phone_country_code' => 'required|integer',
                'phone' => 'required|integer',
                'currency' => 'required|integer',
            ])->errors();
        }
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 25-Mar-2022
       *   Uses :  To Mark Featured as Vendor
       *   @param Request request
       *   @return Response
    */
    public function markFeatured(Request $request)
    {
        $msg_data = array();
        $recordData = Vendor::find($request->id);
        $recordData->is_featured = $request->status;
        $recordData->save();
        if($request->status == 1) {
        	successMessage('Vendor mark as Featured', $msg_data);
        }
        else {
        	successMessage('Vendor unmark as Featured', $msg_data);
        }
    }
}
