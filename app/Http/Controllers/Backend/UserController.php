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
use Carbon\Carbon;
use App\Models\Currency;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Models\CustomerDevice;
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
        $data['data'] = User::withTrashed();
        $data['user_add'] = checkPermission('user_add');
        $data['user_view'] = checkPermission('user_view');
        $data['user_edit'] = checkPermission('user_edit');
        $data['user_status'] = checkPermission('user_status');
        $data['user_add_address'] = checkPermission('user_add_address');
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
                $query = User::with('phone_country', 'whatsapp_country', 'currency')->Where('approval_status', '=', 'accepted')->orderBy('updated_at', 'desc')->withTrashed();
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_name']) && !is_null($request['search']['search_name'])) {
                            $query->where('name', 'like', "%" . $request['search']['search_name'] . "%");
                        }
                        if (isset($request['search']['search_phone']) && !is_null($request['search']['search_phone'])) {
                            $query->where('phone', 'like', "%" . $request['search']['search_phone'] . "%");
                        }
                        if (isset($request['search']['search_email']) && !is_null($request['search']['search_email'])) {
                            $query->where('email', 'like', "%" . $request['search']['search_email'] . "%");
                        }
                        if ($request['search']['search_user_type'] && !is_null($request['search']['search_user_type'])) {
                            if ($request['search']['search_user_type'] == 'not-deleted') {
                                $query->where('deleted_at', NULL);
                            } else {
                                $query->where('deleted_at', '!=', NULL);
                            }
                        }
                        $query->get();
                    })
                    ->editColumn('name', function ($event) {
                        $isDeleted = isRecordDeleted($event->deleted_at);
                        if (!$isDeleted) {
                            return $event->name;
                        } else {
                            return '<span class="text-danger text-center">' . $event->name . '</span>';
                        }
                    })
                    ->editColumn('created_at', function ($event) {
                        return date('d-m-Y h:i A', strtotime($event->created_at));
                    })
                    ->editColumn('email', function ($event) {
                        return $event->email;
                    })
                    ->editColumn('phone', function ($event) {
                        return '+' . $event->phone_country->phone_code . ' ' . $event->phone;
                    })
                    ->editColumn('gstin', function ($event) {
                        return $event->gstin ?? '-';
                    })
                    ->editColumn('action', function ($event) {
                        $isDeleted = isRecordDeleted($event->deleted_at);
                        $user_view = checkPermission('user_list_view');
                        $user_edit = checkPermission('user_list_edit');
                        $user_status = checkPermission('user_list_status');
                        $user_add_address = checkPermission('user_list_add_address');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($user_view) {
                            $actions .= '<a href="user_list_view/' . $event->id . '" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if (!$isDeleted) {
                            if ($user_edit) {
                                $actions .= ' <a href="user_list_edit/' . $event->id .  '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                            }
                            if ($user_status) {
                                if ($event->status == '1') {
                                    $actions .= ' <input type="checkbox" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked data-url="publishUserList">';
                                } else {
                                    $actions .= ' <input type="checkbox" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" data-url="publishUserList">';
                                }
                            }
                            if ($user_add_address) {
                                $actions .= ' <a href="user_address_list?id=' . Crypt::encrypt($event->id) . '" class="btn btn-warning btn-sm " title="User Address"><i class="fa ft-plus-square"></i></a>';
                            }
                        } else {
                            $actions .= ' <span class="bg-danger text-center p-1 text-white" style="border-radius:20px !important;">Deleted</span>';
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['name', 'email', 'phone', 'gstin', 'action'])->setRowId('id')->make(true);
            } catch (\Exception $e) {
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
        return view('backend/customer_section/user_list/user_list_add', $data);
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
        return view('backend/customer_section/user_list/user_list_edit', $data);
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
            $response = User::where([['email', strtolower($request->email)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Email  Already Exist', $msg_data);
            }
            $response = User::where([['phone', $request->phone], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Phone Number Already Exist', $msg_data);
            }
            if (isset($request->whatsapp_no)) {
                $response = User::where([['whatsapp_no', $request->whatsapp_no], ['id', '!=', $_GET['id']]])->get()->toArray();
                if (isset($response[0])) {
                    // print_r($response[0]);exit;
                    errorMessage('Whatsapp Number Already Exist', $msg_data);
                }
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
            if (isset($request->whatsapp_no)) {
                $response = User::where([['whatsapp_no', $request->whatsapp_no]])->get()->toArray();
                if (isset($response[0])) {
                    errorMessage('Whatsapp Number Already Exist', $msg_data);
                }
            }
            $msg = "Data Saved Successfully";
        }
        $maxPhoneCodeLength = Country::where('id', $request->phone_country_code)->get()->toArray();
        $allowedPhoneLength = $maxPhoneCodeLength[0]['phone_length'];
        if (strlen($request->phone) != $allowedPhoneLength) {
            errorMessage("Phone Number Should be $allowedPhoneLength digit long.", $msg_data);
        }
        if ($request->whatsapp_country_code == '' && $request->whatsapp_no != '') {
            errorMessage("Please Select Country Code for Whatsapp Number", $msg_data);
        }
        if ($request->whatsapp_country_code != '') {
            $maxPhoneCodeLength = Country::where('id', $request->whatsapp_country_code)->get()->toArray();
            $allowedPhoneLength = $maxPhoneCodeLength[0]['phone_length'];
            if (strlen($request->whatsapp_no) != $allowedPhoneLength) {
                errorMessage("Whatsapp Number Should be $allowedPhoneLength digit long.", $msg_data);
            }
        }
        $tableObject->name = $request->name;
        $tableObject->phone_country_id = $request->phone_country_code;
        $tableObject->phone = $request->phone;
        if ($request->whatsapp_country_code != '' && $request->whatsapp_no != '') {
            $tableObject->whatsapp_country_id = $request->whatsapp_country_code;
            $tableObject->whatsapp_no = $request->whatsapp_no;
        }
        if ($request->currency != '') {
            $tableObject->currency_id = $request->currency;
        }
        $tableObject->approval_status = "accepted";
        $tableObject->approved_on = date('Y-m-d H:i:s');
        $tableObject->approved_by =  session('data')['id'];
        $tableObject->password = '';
        if ($isUpdateFlow) {
            $tableObject->updated_by = session('data')['id'];
        } else {
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
        } else {
            CustomerDevice::where([['user_id', $request->id]])->update(['remember_token' => NULL]);
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
        $data['data'] = User::withTrashed()->find($id);
        $data['userAddress'] = UserAddress::with('city', 'state', 'country', 'user')->where('user_id', '=', $id)->get();
        return view('backend/customer_section/user_list/user_list_view', $data);
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
            'phone_country_code' => 'required|integer',
            'phone' => 'required|digits:10',
            'whatsapp_country_code' => 'integer',
            'whatsapp_no' => 'digits:10'
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
        $data['approvalStatusArray'] = approvalStatusArray();
        $data['user_approval_view'] = checkPermission('user_approval_view');
        $data['user_approval_update'] = checkPermission('user_approval_update');
        return view('backend/customer_section/user_approval_list/index', ["data" => $data]);
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
                $query = User::with('phone_country', 'whatsapp_country', 'currency')->where('approval_status', '!=', 'accepted')->orderBy('updated_at', 'desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_name']) && !is_null($request['search']['search_name'])) {
                            $query->where('name', 'like', "%" . $request['search']['search_name'] . "%");
                        }
                        if (isset($request['search']['search_phone']) && !is_null($request['search']['search_phone'])) {
                            $query->where('phone', 'like', "%" . $request['search']['search_phone'] . "%");
                        }
                        if (isset($request['search']['search_email']) && !is_null($request['search']['search_email'])) {
                            $query->where('email', 'like', "%" . $request['search']['search_email'] . "%");
                        }
                        if (isset($request['search']['search_approval_status']) && !is_null($request['search']['search_approval_status'])) {
                            $query->where('approval_status', 'like', "%" . $request['search']['search_approval_status'] . "%");
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
                    ->editColumn('gstin', function ($event) {
                        return $event->gstin ?? 'Not found';
                    })
                    // ->editColumn('gst_certificate', function ($event) {
                    //     if (str_contains($event->gst_certificate, '.pdf')) {
                    //         $file  = '<span><i class="fa fa-file"></i>' . $event->gst_certificate . '</span>';
                    //     } else {
                    //         // $image_path = getFile($event->gst_certificate, 'gst_certificate', false);
                    //         // $file  = '<img src="' . $image_path . '" alt="File Not Found" width="150" height="150">';
                    //         $imageUrl = ListingImageUrl('gst_certificate', $event->gst_certificate, 'image', false);
                    //         $file  = ' <img src="' . $imageUrl . '" width="150" height="150"/>';
                    //     }

                    //     return $file;
                    // })
                    ->editColumn('approval_status', function ($event) {
                        $db_approval_status = $event->approval_status;
                        $bg_class = 'bg-danger';
                        if ($db_approval_status == 'accepted') {
                            $bg_class = 'bg-success';
                        } else if ($db_approval_status == 'rejected') {
                            $bg_class = 'bg-danger';
                        } else {
                            $bg_class = 'bg-warning';
                        }
                        $displayStatus = approvalStatusArray($db_approval_status);
                        $approvalStatus = '<span class="' . $bg_class . ' text-center rounded p-1 text-white">' . $displayStatus . '</span>';
                        return $approvalStatus;
                    })
                    ->editColumn('created_at', function ($event) {
                        return date('d-m-Y h:i A', strtotime($event->created_at));
                    })
                    ->editColumn('action', function ($event) {
                        $user_approval_view = checkPermission('user_approval_view');
                        $user_approval_update = checkPermission('user_approval_update');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($user_approval_view) {
                            $actions .= '<a href="user_approval_list_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View User Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($user_approval_update) {
                            $actions .= ' <a href="user_approval_list_update/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update Approval"><i class="fa fa-edit"></i></a>';
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['name', 'email', 'phone', 'approval_status', 'gstin', 'created_at', 'action'])->setRowId('id')->make(true);
            } catch (\Exception $e) {
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
        if ($data['data']) {
            $data['data']->image_path = getFile($data['data']->gst_certificate, 'gst_certificate', false);
        }
        return view('backend/customer_section/user_approval_list/user_approval_list_update', $data);
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
        $msg_data = array();
        $msg = "";
        $validationErrors = $this->validateRequestApprovalList($request, $_GET['id']);
        if (count($validationErrors)) {
            \Log::error("User Approval List Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        if (isset($_GET['id'])) {
            $getKeys = true;
            $approvalStatusArray = approvalStatusArray('', $getKeys);
            if (in_array($request->approval_status, $approvalStatusArray)) {
                $tableObject = User::find($_GET['id']);
                $msg = "Approval Status Updated Successfully";
            } else {
                errorMessage('Approval Status Does not Exists.', $msg_data);
            }
        }
        $tableObject->approval_status = $request->approval_status;
        $tableObject->gstin = $request->gstin ?? '';
        $tableObject->approval_status = $request->approval_status;
        $tableObject->approved_on = date('Y-m-d H:i:s');
        $tableObject->approved_by =  session('data')['id'];
        $tableObject->admin_remark = '';
        if ($request->approval_status ==  'rejected' && !empty($request->admin_remark)) {
            $tableObject->admin_remark = $request->admin_remark;
        }
        if ($request->approval_status ==  'accepted') {
            $tableObject->status = 1;
        }
        if ($request->approval_status !=  'accepted') {
            CustomerDevice::where([['user_id', $_GET['id']]])->update(['remember_token' => NULL]);
        }
        $tableObject->save();

        if ($request->hasFile('gst_certificate')) {
            $image = $request->file('gst_certificate');
            $actualImage = saveSingleImage($image, 'gst_certificate', $_GET['id']);
            $tableObject->gst_certificate = $actualImage;
            $tableObject->save();
        }
        successMessage($msg, $msg_data);
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
        return view('backend/customer_section/user_approval_list/user_approval_list_view', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 23-Mar-2022
     *   Uses :  User Approval List Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequestApprovalList(Request $request, $id)
    {
        return \Validator::make(
            $request->all(),
            [
                'approval_status' => 'required|string',
                'gstin' => ($request->approval_status == 'accepted') ? 'required|string|min:15|max:15|regex:' . config('global.GST_NO_VALIDATION') . '|unique:users,gstin' . ($id ? ",$id" : '') : 'nullable|string|min:15|max:15|regex:' . config('global.GST_NO_VALIDATION') . '|unique:users,gstin' . ($id ? ",$id" : ''),
                'gst_certificate' => ($request->approval_status == 'accepted' && empty($request->gst_certificate)) ?  'required|mimes:jpeg,png,jpg,pdf|max:' . config('global.MAX_IMAGE_SIZE') : ''
            ],
            [
                'gst_certificate.max' => 'The gst certificate must not be greater than 2MB.',
            ]
        )->errors();
    }
}
