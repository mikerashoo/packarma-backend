<?php
/*
    *	Developed by : Sagar Thokal - Mypcot Infotech 
    *	Project Name : Packult 
    *	File Name : AdminController.php
    *	File Path : app\Http\Controllers\Backend\AdminController.php
    *	Created On : 08-02-2022
    *	http ://www.mypcot.com
*/
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\Admin;
use App\Models\Role;
use Yajra\DataTables\DataTables;
use App\Models\Permission;
use Str;

class AdminController extends Controller
{
    /**
       *   created by : Sagar Thokal
       *   Created On : 08-Feb-2022
       *   Uses :  To load admin profile page
    */
	public function profile() {
        $id = session('data')['id'];
        $data = Admin::find($id);
        return view('backend/dashboard/profile',["data"=>$data]);
    }


    /**
       *   created by : Sagar Thokal
       *   Created On : 08-Feb-2022
       *   Uses :  To load admin role list
    */
    public function roles() 
    {
        $data['role_permission'] = checkPermission('role_permission');
        $data['roles'] = Role::all();
        return view('backend/role/index',["data"=>$data]);
    }

    /**
       *   created by : Sagar Thokal
       *   Created On : 08-Feb-2022
       *   Uses :  Fetch Role list data dynamically in datatable
       *   @param Request request
       *   @return Response
    */
    public function roleData(Request $request){
        if ($request->ajax()) {
        	try {
	            $query = Role::select('*');
	            return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        
                        if ($request['search']['search_name'] && ! is_null($request['search']['search_name'])) {
                            $query->where('role_name', 'like', "%" . $request['search']['search_name'] . "%");
                        }
                        $query->get();
                    })
	                ->editColumn('role_name', function ($event) {
	                    return $event->role_name;
	                })
	                ->editColumn('action', function ($event) {
                        $role_permission = checkPermission('role_permission');
	                    $actions = '<span style="white-space:nowrap;">';
	                    if($event->id != 1) {
	                        if($role_permission) {
                                $actions .= ' <a href="role_permission/'.$event->id.'" class="btn btn-success btn-sm src_data" title="Edit Permissions"><i class="fa fa-edit"></i></a>';
	                        }
	                    }
                        $actions .= '</span>';
	                    return $actions;
	                }) 
	                ->addIndexColumn()
	                ->rawColumns(['role_name','action'])->setRowId('id')->make(true);
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
       *   created by : Sagar Thokal
       *   Created On : 08-Feb-2022
       *   Uses :  Assign permission to role who can access what from panel
       *   @param int $id
       *   @return Response
    */
    public function assignRolePermission($id){
        $roleData = Role::find($id)->toArray();
       
        $data['roleData'] = $roleData;
        $permissions = json_decode($roleData['permission'], TRUE);
        $data['role_permissions'] = $permissions;
        $permissionArr= Permission::where([['to_be_considered','Yes']])->get()->toArray();
        
        $formatedPermissions = array();
        $permisstion_type = array('List','Add','Edit','View','Status','Delete');
        foreach($permissionArr as $key => $value){
            if($value['parent_status'] == 'parent') {
                if(!isset($formatedPermissions[$value['id']])){
                    $formatedPermissions[$value['id']]['permission']['id'] = $value['id'];
                    $formatedPermissions[$value['id']]['permission']['label'] = $value['name'];
                    $formatedPermissions[$value['id']]['permission']['parent_status'] = $value['parent_status'];
                    //For List permission
                    $formatedPermissions[$value['id']][$permisstion_type[0]]['id']  = $value['id'];
                    $formatedPermissions[$value['id']][$permisstion_type[0]]['codename']  = $value['codename'];
                    $formatedPermissions[$value['id']][$permisstion_type[0]]['parent_status']  = $value['parent_status'];
                }
            }else{
                foreach($permisstion_type as $k => $v){
                    if($v == $value['name']){
                        $formatedPermissions[$value['parent_status']][$v]['id']  = $value['id'];
                        $formatedPermissions[$value['parent_status']][$v]['codename']  = $value['codename'];
                        $formatedPermissions[$value['parent_status']][$v]['parent_status']  = $value['parent_status'];
                        
                    }else{
                        if(!isset($formatedPermissions[$value['parent_status']][$v])){
                            $formatedPermissions[$value['parent_status']][$v]['id']  = '';
                            $formatedPermissions[$value['parent_status']][$v]['codename']  = '';
                        }
                    }
                }
            }
        }
        $data['permissions'] = array_values($formatedPermissions);
        $data['permission_types'] = $permisstion_type;
        return view('backend/role/assignRole',["data"=>$data]);
    }

     /**
       *   created by : Sagar Thokal
       *   Created On : 08-Feb-2022
       *   Uses :  Submit permission for roles
       *   @param Request $request
       *   @return Response
    */
    public function publishPermission(Request $request)
    {
        $id=$_GET['id'];
        $roleData = Role::find($id)->toArray();
        $permissions = json_decode($roleData['permission'], TRUE);
        $permission_id = $request->id;
        $status = $request->status;
        if($request->status) {
            array_push($permissions,$permission_id);
        } else {
            if (($key = array_search($permission_id, $permissions)) !== false) {
                unset($permissions[$key]);
            }
            $permissions = explode(',',implode(',', $permissions));
        }

        $msg_data = array();
        $roles = Role::find($id);
        $roles->permission = $permissions;
        $roles->save();
        successMessage('permission_updated_successfully', $msg_data);
    }


     /**
       *   created by : Sagar Thokal
       *   Created On : 14-Feb-2022
       *   Uses :  To update Admin Profile details
       *   @param Request $request
       *   @return Response
    */
    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'admin_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        $msg_data = array();
        $id = session('data')['id'];
        $admins = Admin::find($id);
        $admins->admin_name = $request->admin_name;
        $admins->email = $request->email;
        $admins->phone = $request->phone;
        $admins->save();
        successMessage('Profile updated successfully', $msg_data);
        //return back()->with('success','Profile updated successfully!');
    }
    
    /**
       *   created by : Sagar Thokal
       *   Created On : 14-Feb-2022
       *   Uses :  To load admin update password page
    */
    public function updatePassword() {
        return view('backend/dashboard/changePassword');
    }

    /**
       *   created by : Sagar Thokal
       *   Created On : 14-Feb-2022
       *   Uses :  To load admin update password page
       *   @param Request $request
       *   @return Response
    */
    public function resetPassword(Request $request)
    {
        $msg_data = array();
        $validationErrors = $this->validatePwdRequest($request);
		if (count($validationErrors)) {
            \Log::error("change Pwd Exception: " . implode(", ", $validationErrors->all()));
        	errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }

        $id = session('data')['id'];
        $email = session('data')['email'];
        $response = Admin::where([['id', $id],['password', md5($email.$request->old_password)]])->get();
        if(count($response) == 0) {
            errorMessage('Old password is incorrect!', $msg_data);
            //return redirect()->back()->withErrors(array("msgOldPass"=>"Old password is incorrect!"));
        }

        if($request->new_password != $request->confirm_password) {
            errorMessage('Password not matched!', $msg_data);
            //return redirect()->back()->withErrors(array("msgMatchPass"=>"Password not matched!"));
        }
        $admins = Admin::find($id);
        $admins->password = md5($email.$request->new_password);
        $admins->save();
        successMessage('Password updated successfully!', $msg_data);
        //return back()->with('success','Password updated successfully!');
    }

    private function validatePwdRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|min:5',
        ])->errors();
    }
}
