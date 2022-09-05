<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\City;
use App\Models\State;
use App\Models\User;
use App\Models\Currency;
use App\Models\UserAddress;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Crypt;

class UserAddressController extends Controller
{
    /**
     *  created by : Pradyumn Dwivedi
     *   Created On : 23-Mar-2022
     *   Uses :  To show user Address  listing 
     */
    public function index()
    {
        try {
            $data['user'] = User::withTrashed()->where('approval_status', '=', 'accepted')->orderBy('name', 'asc')->get();
            $data['user_address_add'] = checkPermission('user_address_add');
            $data['user_address_view'] = checkPermission('user_address_view');
            $data['user_address_edit'] = checkPermission('user_address_edit');
            $data['user_address_status'] = checkPermission('user_address_status');
            if (isset($_GET['id'])) {
                $data['id'] = Crypt::decrypt($_GET['id']);
            }
            return view('backend/customer_section/user_address_list/index', $data);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return redirect('404');
        }
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 24-Mar-2022
     *   Uses :  display dynamic data in for user Address
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = UserAddress::with('user', 'state')->orderBy('updated_at', 'desc')->withTrashed();
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_user']) && !is_null($request['search']['search_user'])) {
                            $query->where('user_id', $request['search']['search_user']);
                        }
                        if (isset($request['search']['search_city']) && !is_null($request['search']['search_city'])) {
                            $query->where('city_name', 'like', "%" . $request['search']['search_city'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('name', function ($event) {
                        $isUserDeleted = isRecordDeleted($event->user->deleted_at);
                        if (!$isUserDeleted) {
                            return $event->user->name;
                        } else {
                            return '<span class="text-danger text-center">' . $event->user->name . '</span>';
                        }
                        // return $event->user->name;
                    })
                    ->editColumn('address_name', function ($event) {
                        $isUserAddressDeleted = isRecordDeleted($event->deleted_at);
                        if (!$isUserAddressDeleted) {
                            return $event->address_name;
                        } else {
                            return '<span class="text-danger text-center">' . $event->address_name . '</span>';
                        }
                        // return $event->user->name;
                    })
                    ->editColumn('state', function ($event) {
                        return $event->state->state_name;
                    })
                    ->editColumn('city', function ($event) {
                        return $event->city_name;
                    })
                    ->editColumn('pincode', function ($event) {
                        return $event->pincode;
                    })
                    ->editColumn('action', function ($event) {
                        $isUserDeleted = isRecordDeleted($event->user->deleted_at);
                        $isUserAddressDeleted = isRecordDeleted($event->deleted_at);
                        $user_address_view = checkPermission('user_address_view');
                        $user_address_edit = checkPermission('user_address_edit');
                        $user_address_status = checkPermission('user_address_status');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($user_address_view) {
                            $actions .= '<a href="user_address_view/' . $event->id . '" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if (!$isUserDeleted && !$isUserAddressDeleted) {
                            if ($user_address_edit) {
                                $actions .= ' <a href="user_address_edit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                            }
                            if ($user_address_status) {
                                if ($event->status == '1') {
                                    $actions .= ' <input type="checkbox" data-url="publishUserAddress" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                                } else {
                                    $actions .= ' <input type="checkbox" data-url="publishUserAddress" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                                }
                            }
                        } else {
                            $actions .= ' <span class="bg-danger text-center p-1 text-white" style="border-radius:20px !important;"> Deleted</span>';
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['name', 'address_name', 'state', 'city', 'pincode', 'action'])->setRowId('id')->make(true);
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
     *   Created On : 24-March-2022
     *   Uses : To load Add User address
     */
    public function add()
    {
        // $data['city'] = City::all();
        if (isset($_GET['id'])) {
            $data['user'][] = User::find($_GET['id']);
            $data['id'] = $_GET['id'];
        } else {
            $data['user'] = User::where('approval_status', 'accepted')->orderBy('name', 'asc')->get();
        }
        $data['addressType'] = addressType();
        $data['state'] = State::where('status', 1)->orderBy('state_name', 'asc')->get();
        $data['country'] = Country::where('status', 1)->orderBy('country_name', 'asc')->get();
        return view('backend/customer_section/user_address_list/user_address_add', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 24-Mar-2022
     *   Uses :  To load Edit User Address
     *   @param int $id
     *   @return Response
     */
    public function edit($id)
    {
        $data['data'] = UserAddress::find($id);
        $msg_data = array();
        if (empty($data['data'])) {
            \Log::error("Edit address: Address id not found");
            errorMessage('Address id not found', $msg_data);
        }
        // $data['city'] = City::all();
        $data['user'] = User::all();
        $data['state'] = State::all();
        $data['country'] = Country::all();
        $data['addressType'] = addressType();
        return view('backend/customer_section/user_address_list/user_address_edit', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 24-Mar-2022
     *   Uses :  To store add/edit User Address details in table
     *   @param Request request
     *   @return Response
     */

    public function saveFormData(Request $request)
    {
        $msg_data = array();
        $msg = "";
        if(isset($_GET['id'])) {
    		$validationErrors = $this->validateRequest($request, $_GET['id']);
    	} else {
    		$validationErrors = $this->validateNewRequest($request);
    	}
        if (count($validationErrors)) {
            \Log::error("Category Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        //to check gst number unique for different user, and gst can be same for same user 
        if($request->address_type == 'billing'){
            if(isset($request->gst_no) && !empty(isset($request->gst_no))){
                $response = UserAddress::where([['gstin', $request->gst_no], ['user_id', '<>', $request->user]])->get()->toArray();
                if (isset($response[0])) {
                    errorMessage('GST number already exist', $msg_data);
                }
            }else{
                errorMessage(__('user_address.if_user_type_billing_gst_number_required'), $msg_data);
            }
        }
        $isUpdateFlow = false;
        if (isset($_GET['id'])) {
            $isUpdateFlow = true;
            $getKeys = true;
            $addressType = addressType('', $getKeys);
            if (isset($request->address_type)) {
                if (in_array($request->address_type, $addressType)) {
                    $msg = "Data Updated Successfully";
                } else {
                    errorMessage('Address Type Does not Exists.', $msg_data);
                }
            }
            $tableObject = UserAddress::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tableObject = new UserAddress;
            $getKeys = true;
            $addressType = addressType('', $getKeys);
            if (isset($request->address_type)) {
                if (in_array($request->address_type, $addressType)) {
                    $msg = "Data Updated Successfully";
                } else {
                    errorMessage('Address Type Does not Exists.', $msg_data);
                }
            }
            $msg = "Data Saved Successfully";
        }

        if (isset($request->address_name)) {
            $tableObject->address_name = $request->address_name;
        }
        if (isset($request->address_type)) {
            $getKeys = true;
            $addressType = addressType('', $getKeys);
            if (in_array($request->address_type, $addressType)) {
                $tableObject->type = $request->address_type;
            } else {
                errorMessage('Address Type Does Not Exists.', $msg_data);
            }
        }
        if (isset($request->mobile_no)) {
            $tableObject->mobile_no = $request->mobile_no;
        }
        $tableObject->user_id = $request->user;
        $tableObject->country_id = $request->country;
        $tableObject->state_id = $request->state;
        $tableObject->city_name = $request->city_name;
        $tableObject->pincode = $request->pincode;
        $tableObject->flat = $request->flat;
        $tableObject->area = $request->area;
        $tableObject->land_mark = $request->landmark;
        if ($isUpdateFlow) {
            $tableObject->updated_by = session('data')['id'];
        } else {
            $tableObject->created_by = session('data')['id'];
        }
        if($request->address_type == 'billing' && isset($request->gst_no)){
            $tableObject->gstin = $request->gst_no;
        }
        else{
            $tableObject->gstin = null;
        }
        $tableObject->save();
        successMessage($msg, $msg_data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 24-Mar-2022
     *   Uses :  To view user address  
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data['data'] = UserAddress::withTrashed()->with('user', 'city', 'state', 'country')->find($id);
        $data['addressType'] = addressType();
        return view('backend/customer_section//user_address_list/user_address_view', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 24-mar-2022
     *   Uses :  To publish or unpublish user address records
     *   @param Request request
     *   @return Response
     */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = UserAddress::find($request->id);
        $recordData->status = $request->input('status');
        $recordData->save();
        if ($request->status == 1) {
            successMessage('Published', $msg_data);
        } else {
            successMessage('Unpublished', $msg_data);
        }
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 24-Mar-2022
     *   Uses :  User Address Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request, $id)
    {
        return \Validator::make($request->all(), [
            'user' => 'required|integer',
            'address_name' => 'required|string',
            'address_type' => 'required|string',
            'mobile_no' => 'required|digits:10',
            'country' => 'required|integer',
            'state' => 'required|integer',
            'city_name' => 'required|string',
            'pincode' => 'required|digits:6',
            'flat' => 'required|string',
            'area' => 'required|string',
            'landmark' => 'required|string',
            'gst_no'=>'nullable|string|min:15|max:15|regex:' . config('global.GST_NO_VALIDATION'),
            // 'gst_no'=> ($request->address_type == 'billing') ? 'string|min:15|max:15|regex:' . config('global.GST_NO_VALIDATION').'|unique:user_addresses,gstin,' . $id . ',user_id,deleted_at,NULL': '' ,
        ])->errors();
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 24-Mar-2022
     *   Uses :  User Address Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateNewRequest(Request $request)
    { 
        return \Validator::make($request->all(), [

           
            'user' => 'required|integer',
            'address_name' => 'required|string',
            'address_type' => 'required|string',
            'mobile_no' => 'required|digits:10',
            'country' => 'required|integer',
            'state' => 'required|integer',
            'city_name' => 'required|string',
            'pincode' => 'required|digits:6',
            'flat' => 'required|string',
            'area' => 'required|string',
            'landmark' => 'required|string',
            'gst_no'=>'nullable|string|min:15|max:15|regex:' . config('global.GST_NO_VALIDATION'),
            // 'gst_no'=> ($request->address_type == 'billing') ? 'string|min:15|max:15|regex:' . config('global.GST_NO_VALIDATION').'|unique:user_addresses,gstin,'.$request->user.',user_id,deleted_at' : '' ,
        ])->errors();
    }
}
