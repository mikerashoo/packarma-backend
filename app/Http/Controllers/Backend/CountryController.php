<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Currency;
use Yajra\DataTables\DataTables;

class CountryController extends Controller
{
    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 28-Feb-2022
       *   Uses :  To show country listing page
    */
    public function index(){
        $data['country_add'] = checkPermission('country_add');
        $data['country_view'] = checkPermission('country_view');
        $data['country_edit'] = checkPermission('country_edit');
        $data['country_status'] = checkPermission('country_status'); 
        return view('backend/country/index',["data"=>$data]);
    }

    public function fetch(Request $request){
        if ($request->ajax()) {
        	try {
	            $query = Country::with('currency');                
	            return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        
                        if (isset($request['search']['search_country_name']) && ! is_null($request['search']['search_country_name'])) {
                            $query->where('country_name', 'like', "%" . $request['search']['search_country_name'] . "%");
                        }
                        if (isset($request['search']['search_phone_code']) && ! is_null($request['search']['search_phone_code'])) {
                            $query->where('phone_code', 'like', "%" . $request['search']['search_phone_code'] . "%");
                        }
                        
                        $query->get();
                    })
                    ->editColumn('country_name', function ($event) {
	                    return $event->country_name;                        
	                })
                    ->editColumn('phone_code', function ($event) {
	                    return $event->phone_code;
	                })
                    ->editColumn('phone_length', function ($event) {
	                    return $event->phone_length;
	                })
                    ->editColumn('currency_code', function ($event) {                       
	                    return $event->currency->currency_code;
	                })
	                ->editColumn('action', function ($event) {
                        $country_view = checkPermission('country_view');
                        $country_edit = checkPermission('country_edit');
	                    $country_status = checkPermission('country_status');
	                    $actions = '<span style="white-space:nowrap;">';
                        if ($country_view) {
                            $actions .= '<a href="country_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View Country Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if($country_edit) {
                            $actions .= ' <a href="country_edit/'.$event->id.'" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if($country_status) {
                            if($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishCountry" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishCountry" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
	                }) 
	                ->addIndexColumn()
	                ->rawColumns(['country_name','phone_code','phone_length','currency_code','action'])->setRowId('id')->make(true);
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
       *   Created On : 28-Feb-2022
       *   Uses :  To store country details in table
       *   @param Request request
       *   @return Response
    */
    public function saveFormData(Request $request)
    {
    	$msg_data=array();
        $msg = "";
        $validationErrors = $this->validateRequest($request);
		if (count($validationErrors)) {
            \Log::error("Country Validation Exception: " . implode(", ", $validationErrors->all()));
        	errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isEditFlow = false;
        if(isset($_GET['id'])) {
            $isEditFlow = true;
            $response = Country::where([['country_name', strtolower($request->country_name)],['id', '<>', $_GET['id']]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('Country Name Already Exist', $msg_data);
            }
            $tblObj = Country::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tblObj = new Country;
            $response = Country::where([['country_name',strtolower($request->country_name)]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('Country Name Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        if(isset($_GET['id'])) {
            $response = Country::where([['phone_code', strtolower($request->phone_code)],['id', '<>', $_GET['id']]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('Phone Code Already Exist', $msg_data);
            }
            $tblObj = Country::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tblObj = new Country;
            $response = Country::where([['phone_code',strtolower($request->phone_code)]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('Phone Code Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }

        $tblObj->country_name = $request->country_name;
        $tblObj->phone_code = $request->phone_code;
        $tblObj->phone_length = $request->phone_length;
        $tblObj->currency_id = $request->currency_id;
        if($isEditFlow){
            $tblObj->updated_by = session('data')['id'];
        }else{
            $tblObj->created_by = session('data')['id'];
        }
        $tblObj->save();
        successMessage($msg , $msg_data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 05-April-2022
     *   Uses :  to load country view
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data['data'] = Country::find($id);
        $data['currency'] = Currency::all();
        return view('backend/country/country_view', $data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 28-Feb-2022
       *   Uses : To load Add country page
    */
    public function addCountry() {
        $data = Currency::all();
        return view('backend/country/country_add',["data"=>$data]);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 28-Feb-2022
       *   Uses :  To load Edit country page
       *   @param int $id
       *   @return Response
    */

    public function editCountry($id) {
        $data['data'] = Country::find($id);
        $data['currency'] = Currency::all();        
        return view('backend/country/country_edit',["data"=>$data]);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 28-Feb-2022
       *   Uses :  To publish or unpublish Country records
       *   @param Request request
       *   @return Response
    */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = Country::find($request->id);
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
       *   Created On : 28-Feb-2022
       *   Uses :  Country Add|Edit Form Validation part will be handle by below function
       *   @param Request request
       *   @return Response
    */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'country_name' => 'string|required',
	        'phone_code' => 'numeric|required',
            'phone_length' => 'numeric|required',
        ])->errors();
    }
}
