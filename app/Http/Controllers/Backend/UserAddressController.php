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
            $data['city'] = City::all();
            $data['user'] = UserAddress::all();
            $data['user'] = User::all();
            $data['user_address_add'] = checkPermission('user_address_add');
            $data['user_address_view'] = checkPermission('user_address_view');
            $data['user_address_edit'] = checkPermission('user_address_edit');
            $data['user_address_status'] = checkPermission('user_address_status');
            if (isset($_GET['id'])) {
                $data['id'] = Crypt::decrypt($_GET['id']);
            }
            return view('backend/customer_section/user_address_list/index', $data);
        }
        catch (\Exception $e) {
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
                $query = UserAddress::with('user', 'city');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_user']) && !is_null($request['search']['search_user'])) {
                            $query->where('user_id', $request['search']['search_user']);
                        }
                        if (isset($request['search']['search_city']) && ! is_null($request['search']['search_city'])) {
                            $query->where('city_id', $request['search']['search_city']);                           
                        }
                        $query->get();
                    })
                    ->editColumn('name', function ($event) {
                        return $event->user->name;
                    })
                    ->editColumn('city_name', function ($event) {
                        return $event->city->city_name;
                    })
                    ->editColumn('address', function ($event) {
                        return $event->address;
                    })
                    ->editColumn('pincode', function ($event) {
                        return $event->pincode;
                    })
                    ->editColumn('action', function ($event) {
                        $user_address_view = checkPermission('user_address_view');
                        $user_address_edit = checkPermission('user_address_edit');
                        $user_address_status = checkPermission('user_address_status');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($user_address_view) {
                            $actions .= '<a href="user_address_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View User Address Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($user_address_edit) {
                            $actions .= ' <a href="user_address_edit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($user_address_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url=publishUserAddress" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            }
                            else {
                                $actions .= ' <input type="checkbox" data-url="publishUserAddress" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['name', 'city_name', 'address', 'pincode', 'action'])->setRowId('id')->make(true);
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
     *   Created On : 24-March-2022
     *   Uses : To load Add User address
     */
    public function add()
    {
        $data['city'] = City::all();
        if (isset($_GET['id'])) {
            $data['user'][] = User::find($_GET['id']);
            $data['id'] = $_GET['id'];
        }
        else {
            $data['user'] = User::all();
        }
        $data['state'] = State::all();
        $data['country'] = Country::all();
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
        $data['city'] = City::all();
        $data['user'] = User::all();
        $data['state'] = State::all();
        $data['country'] = Country::all();
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
        $validationErrors = $this->validateRequest($request);
        if (count($validationErrors)) {
            \Log::error("User Address Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isUpdateFlow = false;
        if (isset($_GET['id'])) {
            $isUpdateFlow = true;
            $response = UserAddress::where([['pincode', $request->pincode], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Pincode Already Exist', $msg_data);
            }

            $response = UserAddress::where([['address', strtolower($request->address)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Address Already Exist', $msg_data);
            }
            $tableObject = UserAddress::find($_GET['id']);
            $msg = "Data Updated Successfully";
        }
        else {
            $tableObject = new UserAddress;
            $response = UserAddress::where([['pincode', $request->pincode]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Pincode Already Exist', $msg_data);
            }
            $response = UserAddress::where([['address', strtolower($request->address)]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Address Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        $tableObject->user_id = $request->user;
        $tableObject->city_id = $request->city;
        $tableObject->state_id = $request->state;
        $tableObject->country_id = $request->country;
        $tableObject->address = $request->address;
        $tableObject->pincode = $request->pincode;
        if ($isUpdateFlow) {
            $tableObject->updated_by = session('data')['id'];
        }
        else {
            $tableObject->created_by = session('data')['id'];
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
    public function view($id) {
        $data['data'] = UserAddress::with('user','city','state','country')->find($id);
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
        }
        else {
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
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'user' => 'required|integer',
            'city' => 'required|integer',
            'state' => 'required|integer',
            'country' => 'required|integer',
            'address' => 'required|string',
            'pincode' => 'required|integer',
        ])->errors();
    }
}
