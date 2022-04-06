<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Country;
use Yajra\DataTables\DataTables;

class StateController extends Controller
{
    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 22-Mar-2022
       *   Uses :  To show state listing page
    */
    public function index(){
        $data['country'] = Country::all();
        $data['state_add'] = checkPermission('state_add');
        $data['state_view'] = checkPermission('state_view');
        $data['state_edit'] = checkPermission('state_edit');
        $data['state_status'] = checkPermission('state_status');        
        return view('backend/state/index',["data"=>$data]);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 22-Mar-2022
       *   Uses :  display dynamic data in datatable for state page
       *   @param Request request
       *   @return Response
    */
    public function fetch(Request $request){
        if ($request->ajax()) {
        	try {
	            $query = State::with('country');              
	            return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                                            
                        if (isset($request['search']['search_state_name']) && ! is_null($request['search']['search_state_name'])) {
                            $query->where('state_name', 'like', "%" . $request['search']['search_state_name'] . "%");
                        }
                        if (isset($request['search']['search_country']) && ! is_null($request['search']['search_country'])) {
                            $query->where('country_id', $request['search']['search_country']);                           
                        }
                        
                        $query->get();
                    })
	                ->editColumn('state_name', function ($event) {
	                    return $event->state_name;
	                })
                    ->editColumn('country_name', function ($event) {
	                    return $event->country->country_name;
	                })
	                ->editColumn('action', function ($event) {
                        $state_view = checkPermission('state_view');
                        $state_edit = checkPermission('state_edit');
	                    $state_status = checkPermission('state_status');
	                    $actions = '<span style="white-space:nowrap;">';
                        if ($state_view) {
                            $actions .= '<a href="state_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View State Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if($state_edit) {
                            $actions .= ' <a href="state_edit/'.$event->id.'" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if($state_status) {
                            if($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishState" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishState" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';	
                        return $actions;
	                }) 
	                ->addIndexColumn()
	                ->rawColumns(['state_name','country_name','action'])->setRowId('id')->make(true);
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
       *   Created On : 22-March-2022
       *   Uses : To load Add state page
    */
    public function add() {
        $data['country'] = Country::all();
        return view('backend/state/state_add',$data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 22-Mar-2022
       *   Uses :  To load Edit state page
       *   @param int $id
       *   @return Response
    */
    public function edit($id) {
        $data['data'] = State::find($id);
        $data['country'] = Country::all();
        return view('backend/state/state_edit',$data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 22-Mar-2022
       *   Uses :  To store state details in table
       *   @param Request request
       *   @return Response
    */
    public function saveFormData(Request $request)
    {
    	$msg_data=array();
        $msg = "";
        $validationErrors = $this->validateRequest($request);
		if (count($validationErrors)) {
            \Log::error("State Validation Exception: " . implode(", ", $validationErrors->all()));
        	errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isEditFlow = false;
        if(isset($_GET['id'])) {
            $isEditFlow = true;
            $response = State::where([['state_name', strtolower($request->state_name)],['id', '<>', $_GET['id']]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('State Name Already Exist', $msg_data);
            }
            $tblObj = State::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tblObj = new State;
            $response = State::where([['state_name',strtolower($request->state_name)]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('State Name Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        $tblObj->state_name = $request->state_name;
        $tblObj->country_id = $request->country;
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
     *   Uses :  to load state view
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
       $data['data'] = State::find($id);
       $data['country'] = Country::all();
        return view('backend/state/state_view', $data);
    }
	

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 22-Mar-2022
       *   Uses :  To publish or unpublish State records
       *   @param Request request
       *   @return Response
    */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = State::find($request->id);
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
       *   Created On : 22-Mar-2022
       *   Uses :  State Add|Edit Form Validation part will be handle by below function
       *   @param Request request
       *   @return Response
    */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'state_name' => 'required|string',
	        'country' => 'required|integer',
        ])->errors();
    }
}
