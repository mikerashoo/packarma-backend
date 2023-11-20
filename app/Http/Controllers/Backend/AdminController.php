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
use App\Models\GeneralSetting;
use App\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Str;

class AdminController extends Controller
{


    public function __construct()
    {

         $creditPrice = config('constants.CREDIT_PRICE');
     $crditDiscount = config('constants.CREDIT_DISCOUNT_PRICE');

        $isCreditPrice = GeneralSetting::where("type",$creditPrice)->first();
        if(!$isCreditPrice){
            GeneralSetting::create([
                "type" =>$creditPrice,
                'value' => '100'
            ]);

            GeneralSetting::create([
                "type" => $crditDiscount,
                'value' => '10'
            ]);
        }
    }

    /**
     *   created by : Sagar Thokal
     *   Created On : 08-Feb-2022
     *   Uses :  To load admin profile page
     */
    public function profile()
    {
        $id = session('data')['id'];
        $data = Admin::find($id);
        return view('backend/dashboard/profile', ["data" => $data]);
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
        return view('backend/role/index', ["data" => $data]);
    }

    /**
     *   created by : Sagar Thokal
     *   Created On : 08-Feb-2022
     *   Uses :  Fetch Role list data dynamically in datatable
     *   @param Request request
     *   @return Response
     */
    public function roleData(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Role::select('*');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {

                        if ($request['search']['search_name'] && !is_null($request['search']['search_name'])) {
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
                        if ($event->id != 1) {
                            if ($role_permission) {
                                $actions .= ' <a href="role_permission/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Edit Permissions"><i class="fa fa-edit"></i></a>';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['role_name', 'action'])->setRowId('id')->make(true);
            } catch (\Exception $e) {
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
    public function assignRolePermission($id)
    {
        $roleData = Role::find($id)->toArray();

        $data['roleData'] = $roleData;
        $permissions = json_decode($roleData['permission'], TRUE);
        $data['role_permissions'] = $permissions;
        $permissionArr = Permission::where([['to_be_considered', 'Yes']])->get()->toArray();
        // print_r($permissionArr);exit;
        $formatedPermissions = array();
        $permisstion_type = array('List', 'Add', 'Edit', 'View', 'Status', 'Map To Vendor', 'Material Map', 'Delivery Status Update', 'Customer Payment Update', 'Vendor Payment Update');
        foreach ($permissionArr as $key => $value) {
            if ($value['parent_status'] == 'parent') {
                if (!isset($formatedPermissions[$value['id']])) {
                    $formatedPermissions[$value['id']]['permission']['id'] = $value['id'];
                    $formatedPermissions[$value['id']]['permission']['label'] = $value['name'];
                    $formatedPermissions[$value['id']]['permission']['parent_status'] = $value['parent_status'];
                    //For List permission
                    $formatedPermissions[$value['id']][$permisstion_type[0]]['id']  = $value['id'];
                    $formatedPermissions[$value['id']][$permisstion_type[0]]['codename']  = $value['codename'];
                    $formatedPermissions[$value['id']][$permisstion_type[0]]['parent_status']  = $value['parent_status'];
                }
            } else {
                foreach ($permisstion_type as $k => $v) {
                    if ($v == $value['name']) {
                        $formatedPermissions[$value['parent_status']][$v]['id']  = $value['id'];
                        $formatedPermissions[$value['parent_status']][$v]['codename']  = $value['codename'];
                        $formatedPermissions[$value['parent_status']][$v]['parent_status']  = $value['parent_status'];
                    } else {
                        if (!isset($formatedPermissions[$value['parent_status']][$v])) {
                            $formatedPermissions[$value['parent_status']][$v]['id']  = '';
                            $formatedPermissions[$value['parent_status']][$v]['codename']  = '';
                        }
                    }
                }
            }
        }

        $data['permissions'] = array_values($formatedPermissions);
        $data['permission_types'] = $permisstion_type;
        return view('backend/role/assignRole', ["data" => $data]);
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
        $id = $_GET['id'];
        $roleData = Role::find($id)->toArray();
        $permissions = json_decode($roleData['permission'], TRUE);
        $permission_id = $request->id;
        $status = $request->status;
        if ($request->status) {
            array_push($permissions, $permission_id);
        } else {
            if (($key = array_search($permission_id, $permissions)) !== false) {
                unset($permissions[$key]);
            }
            // $permissions = explode(',', implode(',', $permissions));
        }

        $msg_data = array();
        $roles = Role::find($id);
        $roles->permission = $permissions;
        $roles->save();
        successMessage('Permission Updated Successfully', $msg_data);
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
        // $this->validate($request, [
        //     'admin_name' => 'required|string',
        //     'email' => 'required|email',
        //     'phone' => 'required|numeric|digits:10',
        // ]);
        $msg_data = array();
        $validationErrors = $this->validateUpdateProfile($request);
        if (count($validationErrors)) {
            \Log::error("User Approval List Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }

        $msg_data = array();
        $id = session('data')['id'];
        $admins = Admin::find($id);
        $admins->admin_name = $request->admin_name;
        // $admins->email = $request->email;
        $admins->phone = $request->phone;
        $admins->save();
        successMessage('Profile updated successfully', $msg_data);
        //return back()->with('success','Profile updated successfully!');
    }
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 10-06-2022
     *   Uses :  To update Admin Profile details
     *   @param Request $request
     *   @return Response
     */
    private function validateUpdateProfile(Request $request)
    {
        return \Validator::make($request->all(), [
            'admin_name' => 'required|string',
            // 'email' => 'required|email',
            'phone' => 'required|numeric|digits:10',
        ])->errors();
    }

    /**
     *   created by : Sagar Thokal
     *   Created On : 14-Feb-2022
     *   Uses :  To load admin update password page
     */
    public function updatePassword()
    {
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
        $response = Admin::where([['id', $id], ['password', md5($email . $request->old_password)]])->get();
        if (count($response) == 0) {
            errorMessage('Old password is incorrect!', $msg_data);
            //return redirect()->back()->withErrors(array("msgOldPass"=>"Old password is incorrect!"));
        }

        if ($request->new_password != $request->confirm_password) {
            errorMessage('Password not matched!', $msg_data);
            //return redirect()->back()->withErrors(array("msgMatchPass"=>"Password not matched!"));
        }
        $admins = Admin::find($id);

        if ($admins->password == md5($admins->email . $request->new_password)) {
            errorMessage(__('change_password.new_password_cannot_same_current_password'), $msg_data);
        }

        $admins->password = md5($email . $request->new_password);
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


    /**
     *   created by : Maaz Ansari
     *   Created On : 16-july-2022
     *   Uses :  Validate gst details for customer
     */
    private function validateCreditValue(Request $request)
    {

        $creditPrice = config('constants.CREDIT_PRICE');
        $crditDiscount = config('constants.CREDIT_DISCOUNT_PRICE');


        return Validator::make($request->all(), [
            $creditPrice => 'numeric|min:0',
            $crditDiscount => 'numeric|min:0|max:100'
        ])->errors();
    }

     /**
     *   created by : Maaz Ansari
     *   Created On : 16-july-2022
     *   Uses :  Validate gst details for customer
     */
    private function validateCustomerInvoiceDetails(Request $request)
    {
        return \Validator::make($request->all(), [
            'customer_gst_no' => 'sometimes|required|regex:' . config('global.GST_NO_VALIDATION'),
        ])->errors();
    }

    // General settings:-F
    /**
     *   created by : Sagar Thokal
     *   Created On : 18-Feb-2022
     *   Uses :  To load general setting page
     */
    public function fetchSetting()
    {
        $data['data'] = GeneralSetting::pluck('value', 'type')->toArray();
        return view('backend/dashboard/general_setting', $data);
    }


    /**
     *   created by : Maaz
     *   Created On : 30-May-2022
     *   Uses :  To load vendor general setting page
     */
    public function fetchVendorSetting()
    {
        $data['data'] = GeneralSetting::pluck('value', 'type')->toArray();
        return view('backend/dashboard/vendor_general_setting', $data);
    }

    /**
     *   created by : Sagar Thokal
     *   Created On : 20-Feb-2022
     *   Uses :  To update general setting details
     *   @param Request request
     *   @return Response
     */
    public function updateSetting(Request $request)
    {
        $paramCase = $_GET['param'];
        $msg_data = array();
        $msg = "Data Saved Successfully";
        $creditPrice = config('constants.CREDIT_PRICE');
        $crditDiscount = config('constants.CREDIT_DISCOUNT_PRICE');


        if (isset($paramCase) && !empty($paramCase)) {
            try {
                switch ($paramCase) {
                    case 'general':
                        $validationErrors = $this->validateCreditValue($request);
                        if (count($validationErrors)) {
                            \Log::error("Customer General setting Validation Exception: " . implode(", ", $validationErrors->all()));
                            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
                        }

                        GeneralSetting::where("type", $creditPrice)->update(["value" =>  $request->$creditPrice]);
                        GeneralSetting::where("type", $crditDiscount)->update(["value" =>  $request->$crditDiscount]);
                        GeneralSetting::where("type", 'system_email')->update(["value" => $request->system_email]);
                        GeneralSetting::where("type", 'meta_title')->update(["value" => $request->meta_title]);
                        GeneralSetting::where("type", 'meta_keywords')->update(["value" => $request->meta_keywords]);
                        GeneralSetting::where("type", 'meta_description')->update(["value" => $request->meta_description]);
                        break;

                    case 'aboutus':
                        GeneralSetting::where("type", 'about_us')->update(["value" => $request->editiorData]);
                        break;

                    case 'tnc':
                        GeneralSetting::where("type", 'terms_condition')->update(["value" => $request->editiorData]);
                        break;

                    case 'privacy':
                        GeneralSetting::where("type", 'privacy_policy')->update(["value" => $request->editiorData]);
                        break;

                    case 'social':
                        GeneralSetting::where("type", 'fb_link')->update(["value" => $request->fb_link]);
                        GeneralSetting::where("type", 'insta_link')->update(["value" => $request->insta_link]);
                        GeneralSetting::where("type", 'twitter_link')->update(["value" => $request->twitter_link]);
                        GeneralSetting::where("type", 'youtube_link')->update(["value" => $request->youtube_link]);
                        break;

                    case 'vendorSocial':
                        GeneralSetting::where("type", 'vendor_youtube_link')->update(["value" => $request->vendor_youtube_link]);
                        break;


                    case 'customerAppLink':
                        GeneralSetting::where("type", 'customer_android_url')->update(["value" => $request->customer_android_url]);
                        GeneralSetting::where("type", 'customer_ios_url')->update(["value" => $request->customer_ios_url]);
                        break;


                    case 'customerAppVersion':
                        GeneralSetting::where("type", 'customer_android_version')->update(["value" => $request->customer_android_version]);
                        GeneralSetting::where("type", 'customer_ios_version')->update(["value" => $request->customer_ios_version]);
                        break;

                    case 'customerInvoiceDetails':
                        $validationErrors = $this->validateCustomerInvoiceDetails($request);
                        if (count($validationErrors)) {
                            \Log::error("Customer Gst details Validation Exception: " . implode(", ", $validationErrors->all()));
                            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
                        }
                        GeneralSetting::where("type", 'customer_gst_name')->update(["value" => $request->customer_gst_name]);
                        GeneralSetting::where("type", 'customer_gst_no')->update(["value" => $request->customer_gst_no]);
                        GeneralSetting::where("type", 'customer_gst_address')->update(["value" => $request->customer_gst_address]);
                        GeneralSetting::where("type", 'admin_bank_name')->update(["value" => ucwords(strtolower($request->admin_bank_name))]);
                        GeneralSetting::where("type", 'admin_account_no')->update(["value" => $request->admin_account_no]);
                        GeneralSetting::where("type", 'admin_ifsc')->update(["value" => strtoupper($request->admin_ifsc)]);
                        GeneralSetting::where("type", 'admin_benificiary_name')->update(["value" => ucwords(strtolower($request->admin_benificiary_name))]);
                        break;

                    default:
                        throw new \Exception("Invalid Paramter passed");
                }
                successMessage($msg, $msg_data);
                //return redirect('webadmin/generalSetting');
                ///return redirect()->back()->withErrors(array("msg"=>$msg));
            } catch (\Exception $e) {
                \Log::error("General Setting Submit. Error: " . $e->getMessage());
                errorMessage('Something Went Wrong', $msg_data);
            }
        } else {
            errorMessage('Something Went Wrong', $msg_data);
        }
    }


    /**
     *   created by : Maaz Ansari
     *   Created On : 30-May-2022
     *   Uses :  To update general setting details
     *   @param Request request
     *   @return Response
     */
    public function updateVendorSetting(Request $request)
    {
        $paramCase = $_GET['param'];
        $msg_data = array();
        $msg = "Data Saved Successfully";
        if (isset($paramCase) && !empty($paramCase)) {
            try {
                switch ($paramCase) {
                    case 'vendorGeneral':
                        GeneralSetting::where("type", 'vendor_system_email')->update(["value" => $request->vendor_system_email]);
                        GeneralSetting::where("type", 'vendor_meta_title')->update(["value" => $request->vendor_meta_title]);
                        GeneralSetting::where("type", 'vendor_meta_keywords')->update(["value" => $request->vendor_meta_keywords]);
                        GeneralSetting::where("type", 'vendor_meta_description')->update(["value" => $request->vendor_meta_description]);
                        break;

                    case 'vendorAboutus':
                        GeneralSetting::where("type", 'vendor_about_us')->update(["value" => $request->editiorData]);
                        break;

                    case 'vendorTnc':
                        GeneralSetting::where("type", 'vendor_terms_condition')->update(["value" => $request->editiorData]);
                        break;

                    case 'vendorPrivacy':
                        GeneralSetting::where("type", 'vendor_privacy_policy')->update(["value" => $request->editiorData]);
                        break;

                    case 'vendorAppLink':
                        GeneralSetting::where("type", 'vendor_android_url')->update(["value" => $request->vendor_android_url]);
                        GeneralSetting::where("type", 'vendor_ios_url')->update(["value" => $request->vendor_ios_url]);
                        break;


                    case 'vendorAppVersion':
                        GeneralSetting::where("type", 'vendor_android_version')->update(["value" => $request->vendor_android_version]);
                        GeneralSetting::where("type", 'vendor_ios_version')->update(["value" => $request->vendor_ios_version]);
                        break;

                    default:
                        throw new \Exception("Invalid Paramter passed");
                }
                successMessage($msg, $msg_data);
                //return redirect('webadmin/generalSetting');
                ///return redirect()->back()->withErrors(array("msg"=>$msg));
            } catch (\Exception $e) {
                \Log::error("Vendor General Setting Submit. Error: " . $e->getMessage());
                errorMessage('Something Went Wrong', $msg_data);
            }
        } else {
            errorMessage('Something Went Wrong', $msg_data);
        }
    }

    /**
     *   created by : Maaz Ansari
     *   Created On : 30-May-2022
     *   Uses :  To enable vendor email notification
     *   @param Request request
     *   @return Response
     */
    public function updateVendorEmailNotification(Request $request)
    {
        $msg_data = array();
        GeneralSetting::where("type", 'trigger_vendor_email_notification')->update(["value" => $request->status]);
        if ($request->status == 1) {
            successMessage('Published', $msg_data);
        } else {
            successMessage('Unpublished', $msg_data);
        }
    }

    /**
     *   created by : Maaz Ansari
     *   Created On : 30-May-2022
     *   Uses :  To enable vendor whatsapp notification
     *   @param Request request
     *   @return Response
     */
    public function updateVendorWhatsappNotification(Request $request)
    {
        $msg_data = array();
        GeneralSetting::where("type", 'trigger_vendor_whatsapp_notification')->update(["value" => $request->status]);
        if ($request->status == 1) {
            successMessage('Published', $msg_data);
        } else {
            successMessage('Unpublished', $msg_data);
        }
    }

    /**
     *   created by : Maaz Ansari
     *   Created On : 30-May-2022
     *   Uses :  To enable vendor SMS notification
     *   @param Request request
     *   @return Response
     */
    public function updateVendorSMSNotification(Request $request)
    {
        $msg_data = array();
        GeneralSetting::where("type", 'trigger_vendor_sms_notification')->update(["value" => $request->status]);
        if ($request->status == 1) {
            successMessage('Published', $msg_data);
        } else {
            successMessage('Unpublished', $msg_data);
        }
    }




    /**
     *   created by : Sagar Thokal
     *   Created On : 14-March-2022
     *   Uses :  To enable email notification
     *   @param Request request
     *   @return Response
     */
    public function updateEmailNotification(Request $request)
    {
        $msg_data = array();
        GeneralSetting::where("type", 'trigger_email_notification')->update(["value" => $request->status]);
        if ($request->status == 1) {
            successMessage('Published', $msg_data);
        } else {
            successMessage('Unpublished', $msg_data);
        }
    }

    /**
     *   created by : Sagar Thokal
     *   Created On : 14-March-2022
     *   Uses :  To enable whatsapp notification
     *   @param Request request
     *   @return Response
     */
    public function updateWhatsappNotification(Request $request)
    {
        $msg_data = array();
        GeneralSetting::where("type", 'trigger_whatsapp_notification')->update(["value" => $request->status]);
        if ($request->status == 1) {
            successMessage('Published', $msg_data);
        } else {
            successMessage('Unpublished', $msg_data);
        }
    }

    /**
     *   created by : Sagar Thokal
     *   Created On : 14-March-2022
     *   Uses :  To enable SMS notification
     *   @param Request request
     *   @return Response
     */
    public function updateSMSNotification(Request $request)
    {
        $msg_data = array();
        GeneralSetting::where("type", 'trigger_sms_notification')->update(["value" => $request->status]);
        if ($request->status == 1) {
            successMessage('Published', $msg_data);
        } else {
            successMessage('Unpublished', $msg_data);
        }
    }




    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 19-May-2022
     *   Uses :  To check device platform and version
     *   @param Request request
     *   @return Response
     */
    public function checkVersion(Request $request)
    {
        if (isset($request->platform) && !empty($request->platform)) {
            $platform = $request->platform;
        } else {
            echo json_encode(array('success' => "0"));
            exit();
        }

        if (isset($request->version) && !empty($request->version)) {
            $version = $request->version;
        } else {
            echo json_encode(array('success' => "0"));
            exit();
        }

        if ($platform == 'android') {
            $dbVersionData = GeneralSetting::select('value')->where([['type', 'android_version']])->get();
        } else {
            $dbVersionData = GeneralSetting::select('value')->where([['type', 'ios_version']])->get();
        }
        $dbversion = json_decode($dbVersionData[0]['value'], true);

        if (!in_array($version, $dbversion)) {
            echo json_encode(array('success' => "0"));
            exit();
        } else {
            echo json_encode(array('success' => "1"));
            exit();
        }
    }
}
