<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MessageSms;
use App\Models\Language;
use Yajra\DataTables\DataTables;

class MessageSmsController extends Controller
{
    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 16-April-2022
       *   Uses :  To show sms listing page
    */
    public function index(){
        $data['sms_view'] = checkPermission('sms_view');
        $data['sms_edit'] = checkPermission('sms_edit');
        $data['sms_send'] = checkPermission('sms_send');
        $data['sms_status'] = checkPermission('sms_status');
        return view('backend/messaging/sms/index',["data"=>$data]);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 16-April-2022
       *   Uses :  display dynamic data in datatable for sms page
       *   @param Request request
       *   @return Response
    */
    public function fetch(Request $request){
        if ($request->ajax()) {
        	try {
	            $query = MessageSms::select('*');              
	            return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                                            
                        if (isset($request['search']['search_operation']) && ! is_null($request['search']['search_operation'])) {
                            $query->where('operation', 'like', "%" . $request['search']['search_operation'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('user_type', function ($event) {
	                    return messageUserType($event->user_type);
	                })
                    ->editColumn('operation', function ($event) {
	                    return $event->operation;
	                })
                    ->editColumn('trigger', function ($event) {
	                    return messageTrigger($event->trigger);
	                })
	                ->editColumn('action', function ($event) {
                        $sms_view = checkPermission('sms_view');
	                    $sms_edit = checkPermission('sms_edit');
                        $sms_status = checkPermission('sms_status');
                        $sms_send = checkPermission('sms_send');
	                    $actions = '<span style="white-space:nowrap;">';
                        if ($sms_view) {
                            $actions .= '<a href="sms_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View SMS Message Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if($sms_edit) {
                            $actions .= ' <a href="sms_edit/'.$event->id.'" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        // if($sms_send) {
                        //     $actions .= ' <a href="sms_send/'.$event->id.'" class="btn btn-secondary btn-sm src_data" title="Send"><i class="fa fa-share"></i></a>';
                        // }
                        if($sms_status) {
                            if($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishSmsStatus" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishSmsStatus" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
	                }) 
	                ->addIndexColumn() 
	                ->rawColumns(['user_type','operation','trigger','action'])->setRowId('id')->make(true);
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
       *   Created On : 16-April-2022
       *   Uses :  To load Edit sms message page
       *   @param int $id
       *   @return Response
    */
    public function edit($id) {
        $data['data'] = MessageSms::find($id);
        $data['messageUserType'] = messageUserType();
        $data['messageTrigger'] = messageTrigger();
        return view('backend/messaging/sms/sms_edit', $data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 16-April-2022
       *   Uses :  To store sms edited details in table
       *   @param Request request
       *   @return Response
    */
    public function saveFormData(Request $request)
    {
    	$msg_data=array();
        $msg = "";
        $validationErrors = $this->validateRequest($request);
		if (count($validationErrors)) {
            \Log::error("SMS Validation Exception: " . implode(", ", $validationErrors->all()));
        	errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        
        $isEdit = false;
        if(isset($_GET['id'])) {
            $isEdit = true;
            $tblObj = MessageSms::find($_GET['id']);
            $msg = "Data Updated Successfully";
        }
        $tblObj->type = $request->type;
        $tblObj->user_type = $request->user_type;
        $tblObj->language_id = $request->language_id;
        $tblObj->params = $request->params;
        $tblObj->operation = $request->operation;
        $tblObj->message = $request->message;
        $tblObj->trigger = $request->trigger;
        if($isEdit){
            $tblObj->updated_by = session('data')['id'];
        }else{
            $tblObj->created_by = session('data')['id'];
        }
        $tblObj->save();
        successMessage($msg , $msg_data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 16-April-2022
       *   Uses :  To load view sms message page
       *   @param int $id
       *   @return Response
    */
    public function view($id) {
        $data['data'] = MessageSms::with('language')->find($id);
        return view('backend/messaging/sms/sms_view',$data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 16-April-2022
       *   Uses :  To publish or unpublish sms message records
       *   @param Request request
       *   @return Response
    */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = MessageSms::find($request->id);
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
       *   Created On : 16-April-2022
       *   Uses :  sms message Edit Form Validation part will be handle by below function
       *   @param Request request
       *   @return Response
    */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
	        'user_type' => 'required|string',
            'trigger' => 'required|string',
            'message' => 'required|string'
        ])->errors();
    }
}
