<?php
/*
 *	Developed by : Pradyumn Dwivedi - Mypcot Infotech 
 *	Project Name : Packult 
 *	File Name : UserController.php
 *	File Path : app\Http\Controllers\Backend\UserController.php
 *	Created On : 22-Mar-2022
 *	http ://www.mypcot.com */

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Models\Currency;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    //---------------------user list section------------------------
    /**
     *  created by : Pradyumn Dwivedi
     *   Created On : 22-Mar-2022
     *   Uses : to show users list for accepted user request
     */

    public function indexUserList($id = "")
    {
        $data['data'] = User::all();
        $data['add_user'] = checkPermission('add_user');
        $data['user_list_view'] = checkPermission('user_list_view');
        $data['user_list_edit'] = checkPermission('user_list_edit');
        $data['user_list_status'] = checkPermission('user_list_status');
        $data['user_list_add_address'] = checkPermission('user_list_add_address');
        return view('backend/customer_section/user_list/index', ["data" => $data]);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 22-Mar-2022
     *   Uses :  display dynamic data in datatable for accepted user
     *   @param Request request
     *   @return Response
     */
    public function fetchUserList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = User::with('phone_country','whatsapp_country','currency')->Where('approval_status', '=', 'accepted');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_name']) && !is_null($request['search']['search_name'])) {
                            $query->where('name', 'like', "%" . $request['search']['search_name'] . "%");
                        }
                        if (isset($request['search']['search_phone']) && !is_null($request['search']['search_phone'])) {
                            $query->where('phone', 'like', "%" . $request['search']['search_phone'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('name', function ($event) {
                        return $event->name;
                    })
                    ->editColumn('created_at', function ($event) {
                        return date('d-m-Y H:i A', strtotime($event->created_at));
                    })
                    ->editColumn('email', function ($event) {
                        return $event->email;
                    })
                    ->editColumn('phone', function ($event) {
                        return '+' . $event->phone_country->phone_code . ' ' . $event->phone;
                    })
                    ->editColumn('whatsapp', function ($event) {
                        return '+' . $event->whatsapp_country->phone_code . ' ' . $event->whatsapp_no;
                    })
                    ->editColumn('action', function ($event) {
                    $user_list_view = checkPermission('user_list_view');
                    $user_list_edit = checkPermission('user_list_edit');
                    $user_list_status = checkPermission('user_list_status');
                    $user_list_add_address = checkPermission('user_list_add_address');
                    $actions = '<span style="white-space:nowrap;">';
                    if ($user_list_view) {
                        $actions .= '<a href="userListView/' . $event->id . '" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                    }
                    if ($user_list_edit) {
                        $actions .= ' <a href="edit_user_list/' . $event->id.  '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                    }
                    if ($user_list_status) {
                        if ($event->status == '1') {
                            $actions .= ' <input type="checkbox" data-url=publishUser" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                        }
                        else {
                            $actions .= ' <input type="checkbox" data-url="publishUser" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                        }
                    }
                    if ($user_list_add_address) {
                        $actions .= ' <a href="user_address_list?id=' . Crypt::encrypt($event->id) . '" class="btn btn-warning btn-sm " title="User Address"><i class="fa ft-plus-square"></i></a>';
                    }
                    $actions .= '</span>';
                    return $actions;
                })
                    ->addIndexColumn()
                    ->rawColumns(['name', 'email', 'phone','whatsapp','action'])->setRowId('id')->make(true);
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
     *   Created On : 22-Mar-2022
     *   Uses : To load add new user in table
     */
    public function addUser()
    {
        $data['country'] = Country::all();
        $data['currency'] = Currency::all();
        return view('backend/customer_section/user_list/add_user', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 22-Mar-2022
     *   Uses :  To load Edit User list details
     *   @param int $id
     *   @return Response
     */
    public function editUserList($id)
    {
        $data['data'] = User::find($id);
        $msg_data = array();
        if (empty($data['data'])) {
            \Log::error("Edit user: user id not found");
            errorMessage('user id not found', $msg_data);
        }
        $data['country'] = Country::all();
        $data['currency'] = Currency::all();
        return view('backend/customer_section/user_list/edit_user', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 22-Mar-2022
     *   Uses :  To store/save user details in table
     *   @param Request request
     *   @return Response
     */
    public function saveUserListFormData(Request $request)
    {
        $msg_data = array();
        $msg = "";
        $validationErrors = $this->validateRequestUserList($request);
        if (count($validationErrors)) {
            \Log::error("User Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isUpdateFlow = false;
        if (isset($_GET['id'])) {
            $isUpdateFlow = true;
            $response = User::where([['name', strtolower($request->name)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Name Already Exist', $msg_data);
            }
            $response = User::where([['email', strtolower($request->email)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Email  Already Exist', $msg_data);
            }
            $response = User::where([['phone', $request->phone], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Phone Number Already Exist', $msg_data);
            }
            $response = User::where([['whatsapp_no', $request->whatsapp_no], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Whatsapp Number Already Exist', $msg_data);
            }
            $tableObject = User::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tableObject = new User;
            $response = User::where([['name', strtolower($request->name)]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Name  Already Exist', $msg_data);
            }
            $response = User::where([['email', strtolower($request->email)]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Email Already Exist', $msg_data);
            }
            $response = User::where([['phone', $request->phone]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Phone Number Already Exist', $msg_data);
            }
            $response = User::where([['whatsapp_no', $request->whatsapp_no]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Whatsapp Number Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        $maxPhoneCodeLength = Country::where('id', $request->phone_country_code)->get()->toArray();
        $allowedPhoneLength = $maxPhoneCodeLength[0]['phone_length'];
        if(strlen($request->phone) != $allowedPhoneLength){
            errorMessage("Phone Number Should be $allowedPhoneLength digit long.", $msg_data);
        }
        $maxPhoneCodeLength = Country::where('id', $request->whatsapp_country_code)->get()->toArray();
        $allowedPhoneLength = $maxPhoneCodeLength[0]['phone_length'];
        if(strlen($request->whatsapp_no) != $allowedPhoneLength){
            errorMessage("Whatsapp Number Should be $allowedPhoneLength digit long.", $msg_data);
        }
        $tableObject->name = $request->name;
        $tableObject->email = $request->email;
        $tableObject->phone_country_id = $request->phone_country_code;
        $tableObject->phone = $request->phone;
        $tableObject->whatsapp_country_id = $request->whatsapp_country_code;
        $tableObject->whatsapp_no = $request->whatsapp_no;
        $tableObject->currency_id = $request->currency;
        $tableObject->currency_id = $request->currency;
        $tableObject->approval_status = "accepted";
        $tableObject->approved_on = date('Y-m-d H:i:s');
        $tableObject->approved_by =  session('data')['id'];
        $tableObject->password = '';
        if($isUpdateFlow){
            $tableObject->updated_by = session('data')['id'];
        }else{
            $tableObject->created_by = session('data')['id'];
        }
        $tableObject->save();
        successMessage($msg, $msg_data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 22-Mar-2022
     *   Uses :  To publish or unpublish User records
     *   @param Request request
     *   @return Response
     */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = User::find($request->id);
        $recordData->status = $request->status;
        $recordData->save();
        if ($request->status == 1) {
            successMessage('Published', $msg_data);
        }
        else {
            successMessage('Unpublished', $msg_data);
        }
    }
    
    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 23-mar-2022
       *   Uses :  To view user list details  
       *   @param int $id
       *   @return Response
    */
    public function viewUserList($id)
    {
        $data['data'] = User::find($id);
        $data['userAddress'] = UserAddress::with('city', 'state', 'country','user')->where('user_id', '=', $id)->get();
        return view('backend/customer_section/user_list/view_user', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 22-March-2022
     *   Uses :  User List Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequestUserList(Request $request)
    {
        return \Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'phone_country_code' => 'required|integer',
            'phone' => 'required|integer',
            'whatsapp_country_code' =>'required|integer',
            'whatsapp_no' => 'required|integer',
            'currency' => 'required|integer',
        ])->errors();
    }
    
    //--------------------user approval list section--------------------------

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 23-Mar-2022 
     *   Uses :  To show Pending user listing for approval
     */
    public function indexApprovalList()
    {
        $data['data'] = User::all();
        $data['approval_list_view'] = checkPermission('approval_list_view');
        $data['approval_list_update'] = checkPermission('approval_list_update');
        return view('backend/customer_section/user_approval_list/index',["data"=>$data]);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 23-March-2022
     *   Uses :  display dynamic data in datatable for Pending user in user approval list  
     *   @param Request request
     *   @return Response
     */
    public function fetchUserApprovalList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = User::with('phone_country','whatsapp_country','currency')->where('approval_status', '!=', 'accepted');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_name']) && !is_null($request['search']['search_name'])) {
                            $query->where('name', 'like', "%" . $request['search']['search_name'] . "%");
                        }
                        if (isset($request['search']['search_phone']) && !is_null($request['search']['search_phone'])) {
                            $query->where('phone', 'like', "%" . $request['search']['search_phone'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('name', function ($event) {
                        return $event->name;
                    })
                    ->editColumn('email', function ($event) {
                        return $event->email;
                    })
                    ->editColumn('phone', function ($event) {
                        return '+' . $event->phone_country->phone_code . ' ' . $event->phone;
                    })
                    ->editColumn('approval_status', function ($event) {
                        $db_approval_status = $event->approval_status;
                        $bg_class = 'bg-danger';
                        if ($db_approval_status == 'accepted') {
                            $bg_class = 'bg-success';
                        }
                        else if ($db_approval_status == 'rejected') {
                            $bg_class = 'bg-danger';
                        }
                        else {
                            $bg_class = 'bg-warning';
                        }
                        $displayStatus = approvalStatusArray($db_approval_status);
                        $approvalStatus = '<span class="' . $bg_class . ' text-center rounded p-1 text-white">' . $displayStatus . '</span>';
                        return $approvalStatus;
                    })
                    ->editColumn('created_at', function ($event) {
                        return date('d-m-Y H:i A', strtotime($event->created_at));
                    })
                    ->editColumn('action', function ($event) {
                        $approval_list_view = checkPermission('approval_list_view');
                        $approval_list_update = checkPermission('approval_list_update');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($approval_list_view) {
                            $actions .= '<a href="approval_list_view/' . $event->id . '" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($approval_list_update) {
                            $actions .= ' <a href="approval_list_update/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update Approval"><i class="fa fa-edit"></i></a>';
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['name', 'email', 'phone', 'approval_status','created_at','action'])->setRowId('id')->make(true);
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
     *   Created On : 23-Mar-2022
     *   Uses :  To load Update User approval list page
     *   @param int $id
     *   @return Response
     */
    public function updateApproval($id)
    {
        $data['data'] = User::find($id);
        $data['approvalArray'] = approvalStatusArray();
        return view('backend/customer_section/user_approval_list/update_approval_list', $data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 23-Mar-2022
       *   Uses :  To store user approval list details in table
       *   @param Request request
       *   @return Response
    */
    public function saveApprovalListFormData(Request $request)
    {
    	$msg_data=array();
        $msg = "";
        $validationErrors = $this->validateRequestApprovalList($request);
		if (count($validationErrors)) {
            \Log::error("User Approval List Validation Exception: " . implode(", ", $validationErrors->all()));
        	errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        if(isset($_GET['id'])) {
            $getKeys = true;
            $approvalStatusArray = approvalStatusArray('',$getKeys);
            if (in_array( $request->approval_status, $approvalStatusArray))
             {
                $tableObject = User::find($_GET['id']);            
                $msg = "Approval Status Updated Successfully";
            }
            else{
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
       *   Created On : 23-mar-2022
       *   Uses :  To view user approval list details  
       *   @param int $id
       *   @return Response
    */
    public function viewApprovalList($id)
    {
        $data['data'] = User::find($id);
        $data['country'] = Country::all();
        $data['currency'] = Currency::all();
        $data['approvalArray'] = approvalStatusArray();
        return view('backend/customer_section/user_approval_list/view_approval_list', $data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 23-Mar-2022
       *   Uses :  User Approval List Form Validation part will be handle by below function
       *   @param Request request
       *   @return Response
    */
    private function validateRequestApprovalList(Request $request)
    {
        return \Validator::make($request->all(), [
            'approval_status' => 'required|string',
        ])->errors();
    }
}