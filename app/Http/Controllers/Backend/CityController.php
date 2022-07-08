<?php
/*
    *	Developed by : Sagar Thokal - Mypcot Infotech 
    *	Project Name : RRPL 
    *	File Name : CityController.php
    *	File Path : app\Http\Controllers\Backend\CityController.php
    *	Created On : 09-02-2022
    *	http ://www.mypcot.com
*/
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Yajra\DataTables\DataTables;

class CityController extends Controller
{
    /**
       *   created by : Sagar Thokal
       *   Created On : 09-Feb-2022
       *   Uses :  To show city listing page
    */
    public function index(){
        return redirect('/webadmin/dashboard');
        // $data['states'] = State::all();
        // $data['city_add'] = checkPermission('city_add');
        // $data['city_view'] = checkPermission('city_view');
        // $data['city_edit'] = checkPermission('city_edit');
        // $data['city_status'] = checkPermission('city_status');        
        // return view('backend/city/index',["data"=>$data]);
    }

    /**
       *   created by : Sagar Thokal
       *   Created On : 09-Feb-2022
       *   Uses :  display dynamic data in datatable for city page
       *   @param Request request
       *   @return Response
    */
    public function fetch(Request $request){
        if ($request->ajax()) {
        	try {
	            $query = City::with('state','country')->orderBy('updated_at','desc');           
	            return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                                            
                        if (isset($request['search']['search_city_name']) && ! is_null($request['search']['search_city_name'])) {
                            $query->where('city_name', 'like', "%" . $request['search']['search_city_name'] . "%");
                        }
                        if (isset($request['search']['search_state']) && ! is_null($request['search']['search_state'])) {
                            $query->where('state_id', $request['search']['search_state']);                           
                        }
                        
                        $query->get();
                    })
	                ->editColumn('city_name', function ($event) {
	                    return $event->city_name;
	                })
                    ->editColumn('state_name', function ($event) {
	                    return $event->state->state_name;
	                })
                    // ->editColumn('country_name', function ($event) {
	                //     return $event->country->country_name;
	                // })
                    ->editColumn('status', function ($event) {
	                    $city_status = checkPermission('city_status');
	                    $actions = '<span style="white-space:nowrap;">';
                        if($city_status) {
                            if($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishCity" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishCity" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';	
                        return $actions;
	                }) 
	                ->editColumn('action', function ($event) {
                        $city_view = checkPermission('city_view');
                        $city_edit = checkPermission('city_edit');
	                    $actions = '<span style="white-space:nowrap;">';
                        if ($city_view) {
                            $actions .= '<a href="city_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View City Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if($city_edit) {
                            $actions .= ' <a href="city_edit/'.$event->id.'" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        $actions .= '</span>';	
                        return $actions;
	                }) 
	                ->addIndexColumn()
	                ->rawColumns(['city_name','state_name','country_name','status','action'])->setRowId('id')->make(true);
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
       *   Created On : 10-Feb-2022
       *   Uses : To load Add city page
    */
    public function add() {
        return redirect('/webadmin/dashboard');
        // $data['state'] = State::all();
        // $data['country'] = Country::all();
        // return view('backend/city/city_add',["data"=>$data]);
    }

    /**
       *   created by : Sagar Thokal
       *   Created On : 10-Feb-2022
       *   Uses :  To load Edit city page
       *   @param int $id
       *   @return Response
    */
    public function edit($id) {
        return redirect('/webadmin/dashboard');
        // $data['data'] = City::find($id);
        // $data['state'] = State::all();
        // $data['country'] = Country::all();
        // return view('backend/city/city_edit',["data"=>$data]);
    }
    
    /**
       *   created by : Sagar Thokal
       *   Created On : 10-Feb-2022
       *   Uses :  To store city details in table
       *   @param Request request
       *   @return Response
    */
    public function saveFormData(Request $request)
    {
    	$msg_data=array();
        $msg = "";
        $validationErrors = $this->validateRequest($request);
		if (count($validationErrors)) {
            \Log::error("City Validation Exception: " . implode(", ", $validationErrors->all()));
        	errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isEditFlow = false;
        if(isset($_GET['id'])) {
            $isEditFlow = true;
            $response = City::where([['city_name', strtolower($request->city_name)],['id', '<>', $_GET['id']]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('City Name Already Exist', $msg_data);
            }
            $tblObj = City::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tblObj = new City;
            $response = City::where([['city_name',strtolower($request->city_name)]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('City Name Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        $tblObj->city_name = $request->city_name;
        $tblObj->state_id = $request->state;
        // $tblObj->country_id = $request->country;
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
     *   Created On : 28-Mar-2022
     *   Uses :  to load banners view
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        return redirect('/webadmin/dashboard');
        // $data['data'] = City::find($id);
        // $data['state'] = State::all();
        // $data['country'] = Country::all();
        // return view('backend/city/city_view', $data);
    }

    /**
       *   created by : Sagar Thokal
       *   Created On : 09-Feb-2022
       *   Uses :  To publish or unpublish City records
       *   @param Request request
       *   @return Response
    */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = City::find($request->id);
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
       *   created by : Sagar Thokal
       *   Created On : 10-Feb-2022
       *   Uses :  City Add|Edit Form Validation part will be handle by below function
       *   @param Request request
       *   @return Response
    */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'state' => 'required|integer',  
            'city_name' => 'required|string',
            // 'country' => 'required|integer',
        ])->errors();
    }
}
