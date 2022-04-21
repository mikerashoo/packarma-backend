<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MessageEmail;
use App\Models\Language;
use Yajra\DataTables\DataTables;

class MessageEmailController extends Controller
{
     /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 20-April-2022
       *   Uses :  To show email listing page
    */
    public function index(){
        $data['email_view'] = checkPermission('email_view');
        $data['email_edit'] = checkPermission('email_edit');
        $data['email_send'] = checkPermission('email_send');
        $data['email_status'] = checkPermission('email_status');
        return view('backend/messaging/email/index',["data"=>$data]);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 20-April-2022
       *   Uses :  display dynamic data in datatable for email message page
       *   @param Request request
       *   @return Response
    */
    public function fetch(Request $request){
        if ($request->ajax()) {
        	try {
	            $query = MessageEmail::select('*');              
	            return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_title']) && ! is_null($request['search']['search_title'])) {
                            $query->where('title', 'like', "%" . $request['search']['search_title'] . "%");
                        }
                        if (isset($request['search']['search_subject']) && ! is_null($request['search']['search_subject'])) {
                            $query->where('title', 'like', "%" . $request['search']['search_subject'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('title', function ($event) {
	                    return $event->title;
	                })
                    ->editColumn('subject', function ($event) {
	                    return $event->subject;
	                })
                    ->editColumn('user_type', function ($event) {
	                    return messageUserType($event->user_type);
	                })
                    ->editColumn('trigger', function ($event) {
	                    return messageTrigger($event->trigger);
	                })
	                ->editColumn('action', function ($event) {
                        $email_view = checkPermission('email_view');
	                    $email_edit = checkPermission('email_edit');
                        $email_status = checkPermission('email_status');
                        $email_send = checkPermission('email_send');
	                    $actions = '<span style="white-space:nowrap;">';
                        if ($email_view) {
                            $actions .= '<a href="email_view/' . $event->id . '" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if($email_edit) {
                            $actions .= ' <a href="email_edit/'.$event->id.'" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        // if($email_send) {
                        //     $actions .= ' <a href="email_send/'.$event->id.'" class="btn btn-secondary btn-sm src_data" title="Send"><i class="fa fa-share"></i></a>';
                        // }
                        if($email_status) {
                            if($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishEmailStatus" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishEmailStatus" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
	                }) 
	                ->addIndexColumn() 
	                ->rawColumns(['title','subject','user_type','trigger','action'])->setRowId('id')->make(true);
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
       *   Created On : 20-April-2022
       *   Uses :  To load Edit email message page
       *   @param int $id
       *   @return Response
    */
    public function edit($id) {
        $data['data'] = MessageEmail::find($id);
        $data['messageUserType'] = messageUserType();
        $data['messageTrigger'] = messageTrigger();
        return view('backend/messaging/email/email_edit', $data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 20-April-2022
       *   Uses :  To store email edited details in table
       *   @param Request request
       *   @return Response
    */
    public function saveFormData(Request $request)
    {
        // print_r($request->all());exit;
    	$msg_data=array();
        $msg = "";
        $validationErrors = $this->validateRequest($request);
		if (count($validationErrors)) {
            \Log::error("Email Validation Exception: " . implode(", ", $validationErrors->all()));
        	errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        
        $isEdit = false;
        if(isset($_GET['id'])) {
            $isEdit = true;
            $response = MessageEmail::where([['title',strtolower($request->title)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('Email Title is Already Exist', $msg_data);
            }
            $response = MessageEmail::where([['subject',strtolower($request->subject)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('Email Subject is Already Exist', $msg_data);
            }
            $tblObj = MessageEmail::find($_GET['id']);
            $msg = "Data Updated Successfully";
        }
        $tblObj->title = $request->title;
        $tblObj->subject = $request->subject;
        if($request->label != ''){
            $tblObj->label = $request->label;
        }else{
            $tblObj->label = '';
        }
        $tblObj->user_type = $request->user_type;
        $tblObj->trigger = $request->trigger;
        $tblObj->content = $request->editiorData;
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
       *   Created On : 20-April-2022
       *   Uses :  To load view email message page
       *   @param int $id
       *   @return Response
    */
    public function view($id) {
        $data['data'] = MessageEmail::find($id);
        return view('backend/messaging/email/email_view',$data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 20-April-2022
       *   Uses :  To publish or unpublish email message records
       *   @param Request request
       *   @return Response
    */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = MessageEmail::find($request->id);
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
       *   Uses :  email message Edit Form Validation part will be handle by below function
       *   @param Request request
       *   @return Response
    */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'title' => 'required|string',
            'subject' => 'required|string',
	        'user_type' => 'required|string',
            'trigger' => 'required|string',
            'description' => 'required|string'
        ])->errors();
    }
}
