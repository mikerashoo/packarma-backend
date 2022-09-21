<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Country;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Str;

class StaffController extends Controller
{
	/**
     * Created By :Ankita Singh
     * Created On : 01 Feb 2022
     * Uses : This will load staff view.
    */
    public function index() 
    {
        $data['roles'] = Role::all();
        $data['country'] = Country::all();
        $data['staff_add'] = checkPermission('staff_add');
        $data['staff_view'] = checkPermission('staff_view');
        $data['staff_edit'] = checkPermission('staff_edit');
        $data['staff_status'] = checkPermission('staff_status');
        return view('backend/staff/index',["data"=>$data]);
    }
    /**
     * Created By :Ankita Singh
     * Created On : 01 Feb 2022
     * Uses : This will fetch admin staff data.
     * @param Request $request 
     * @return Response
    */
    public function staffData(Request $request){
        if ($request->ajax()) {
        	try {
	            $query = Admin::with('role')->orderBy('updated_at','desc');
	            return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if ($request['search']['search_email'] && ! is_null($request['search']['search_email'])) {
                            $query->where('email', 'like', "%" . $request['search']['search_email'] . "%");
                        }

                        if ($request['search']['search_name'] && ! is_null($request['search']['search_name'])) {
                            $query->where('admin_name', 'like', "%" . $request['search']['search_name'] . "%");
                        }

                        if ($request['search']['search_phone'] && ! is_null($request['search']['search_phone'])) {
                            $query->where('phone', 'like', "%" . $request['search']['search_phone'] . "%");
                        }

                        if ($request['search']['search_role'] && ! is_null($request['search']['search_role'])) {
                            $query->where('role_id', $request['search']['search_role']);
                        }
                        $query->get();
                    })
	                ->editColumn('admin_name', function ($event) {
	                    return $event->admin_name;
	                })
	                ->editColumn('email', function ($event) {
	                    return $event->email;
	                })
                    ->editColumn('phone', function ($event) {
                        return '+' . $event->country->phone_code . ' ' . $event->phone;
                    })
	                ->editColumn('role', function ($event) {
	                    return $event->role->role_name;
	                }) 
	                ->editColumn('action', function ($event) {
                        $staff_view = checkPermission('staff_view');
	                    $staff_edit = checkPermission('staff_edit');
	                    $staff_status = checkPermission('staff_status');
	                    $actions = '<span style="white-space:nowrap;">';
                        if ($staff_view) {
                            $actions .= '<a href="staff_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View Staff Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
	                    if($event->id != 1) {
	                        if($staff_edit) {
	                            $actions .= ' <a href="staff_edit/'.$event->id.'" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
	                        }
	                        if($staff_status) {
	                            if($event->status == '1') {
	                                $actions .= ' <input type="checkbox" data-url="publishStaff" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery" checked>';
	                            } else {
	                                $actions .= ' <input type="checkbox" data-url="publishStaff" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery">';
	                            }
	                        }
	                    }
	                    return $actions;
	                }) 
	                ->addIndexColumn()
	                ->rawColumns(['admin_name','email','phone','role','action'])->setRowId('id')->make(true);
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
     * Created By :Ankita Singh
     * Created On : 01 Feb 2022
     * Uses : This will load add staff view.
    */
    public function addStaff(Request $request) {
        $data['role'] = Role::all();
        $data['country'] = Country::all();
        return view('backend/staff/staff_add',["data"=>$data]);
    }

    /**
     * Created By :Ankita Singh
     * Created On : 01 Feb 2022
     * Uses : This will load edit staff view.
     * @param int $id 
     * @return Response
    */
    public function editStaff($id) {
        $data['data'] = Admin::find($id);
        $data['roles'] = Role::all();
        $data['country'] = Country::all();
        return view('backend/staff/staff_edit',["data"=>$data]);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 05-April-2022
     *   Uses :  to load staff view
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data['data'] = Admin::find($id);
        $data['country'] = Country::all();
        return view('backend/staff/staff_view', $data);
    }

    /**
     * Created By :Ankita Singh
     * Created On : 01 Feb 2022
     * Uses : This will add a new admin staff.
     * @param Request $request
     * @return Response
    */
    public function saveStaff(Request $request)
    {
    	$msg_data=array();
    	if(isset($_GET['id'])) {
    		$validationErrors = $this->validateRequest($request);
    	} else {
    		$validationErrors = $this->validateNewRequest($request);
    	}
		if (count($validationErrors)) {
            \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
        	errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $msg_data = array();
        $isEditFlow = false;
        if(isset($_GET['id'])) {
            $isEditFlow = true;
            $email = trim(strtolower($request->email));
            $response = Admin::where([['email', $email],['id', '<>', $_GET['id']]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('Email Already Exist', $msg_data);
            }
            $response = Admin::where([['phone', $request->phone], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Phone Number Already Exist', $msg_data);
            }
            $admins = Admin::find($_GET['id']);
        } else {
            $admins = new Admin;
            $response = Admin::where([['phone', $request->phone]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Phone Number Already Exist', $msg_data);
            }
            $email = trim(strtolower($request->email));
            $admins->password = md5($email.$request->password);
            $response = Admin::where([['email', $email]])->get();
        }

        $admins->role_id = $request->role_id;
        $admins->admin_name = $request->name;
        $admins->email = $email;
        $maxPhoneCodeLength = Country::where('id', $request->phone_country_code)->get()->toArray();
        $allowedPhoneLength = $maxPhoneCodeLength[0]['phone_length'];
        if(strlen($request->phone) != $allowedPhoneLength){
            errorMessage("Phone Number Should be $allowedPhoneLength digit long.", $msg_data);
        }
        $admins->country_id = $request->phone_country_code;
        $admins->phone = $request->phone;
        $admins->address = $request->address;
        if($isEditFlow){
            $admins->updated_by = session('data')['id'];
        }else{
            $admins->created_by = session('data')['id'];
        }
        $admins->save();
        successMessage('Data saved successfully', $msg_data);
    }

    /**
     * Created By :Ankita Singh
     * Created On : 01 Feb 2022
     * Uses : This will publish or unpublish admin staff.
     * @param Request $request
     * @return Response
    */
    public function publishStaff(Request $request)
    {
        $msg_data = array();
        $admins = Admin::find($request->id);
        $admins->status = $request->status;
        $admins->save();
        if($request->status == 1) {
        	successMessage('Published', $msg_data);
        }
        else {
        	successMessage('Unpublished', $msg_data);
        }
    }

    private function validateNewRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|numeric',
	        'email' => 'required|email|unique:admins',
	        'password' => 'required|string',
	        'role_id' => 'required|integer'
        ])->errors();
    }

    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|numeric',
	        'email' => 'required|email',
	        'role_id' => 'required|integer'
        ])->errors();
    }


}
