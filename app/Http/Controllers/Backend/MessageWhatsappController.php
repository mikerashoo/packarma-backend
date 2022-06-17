<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MessageWhatsapp;
use App\Models\Language;
use Yajra\DataTables\DataTables;

class MessageWhatsappController extends Controller
{
    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 18-April-2022
       *   Uses :  To show whatsapp message listing page
    */
    public function index(){
        $data['whatsapp_view'] = checkPermission('whatsapp_view');
        $data['whatsapp_edit'] = checkPermission('whatsapp_edit');
        $data['whatsapp_send'] = checkPermission('whatsapp_send');
        $data['whatsapp_status'] = checkPermission('whatsapp_status');
        return view('backend/messaging/whatsapp/index',["data"=>$data]);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 18-April-2022
       *   Uses :  display dynamic data in datatable for whatsapp page
       *   @param Request request
       *   @return Response
    */
    public function fetch(Request $request){
        if ($request->ajax()) {
        	try {
	            $query = MessageWhatsapp::select('*')->orderBy('updated_at','desc');              
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
                        $whatsapp_view = checkPermission('whatsapp_view');
	                    $whatsapp_edit = checkPermission('whatsapp_edit');
                        $whatsapp_status = checkPermission('whatsapp_status');
                        $whatsapp_send = checkPermission('whatsapp_send');
	                    $actions = '<span style="white-space:nowrap;">';
                        if ($whatsapp_view) {
                            $actions .= '<a href="whatsapp_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View Whatsapp Message Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if($whatsapp_edit) {
                            $actions .= ' <a href="whatsapp_edit/'.$event->id.'" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        // if($whatsapp_send) {
                        //     $actions .= ' <a href="whatsapp_send/'.$event->id.'" class="btn btn-secondary btn-sm src_data" title="Send"><i class="fa fa-share"></i></a>';
                        // }
                        if($whatsapp_status) {
                            if($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishWhatsappStatus" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishWhatsappStatus" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery">';
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
       *   Created On : 18-April-2022
       *   Uses :  To load Edit whatsapp message page
       *   @param int $id
       *   @return Response
    */
    public function edit($id) {
        $data['data'] = MessageWhatsapp::find($id);
        $data['messageUserType'] = messageUserType();
        $data['messageTrigger'] = messageTrigger();
        return view('backend/messaging/whatsapp/whatsapp_edit', $data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 18-April-2022
       *   Uses :  To store whatsapp edited details in table
       *   @param Request request
       *   @return Response
    */
    public function saveFormData(Request $request)
    {
    	$msg_data=array();
        $msg = "";
        $validationErrors = $this->validateRequest($request);
		if (count($validationErrors)) {
            \Log::error("Whatsapp Validation Exception: " . implode(", ", $validationErrors->all()));
        	errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        
        $isEdit = false;
        if(isset($_GET['id'])) {
            $isEdit = true;
            $tblObj = MessageWhatsapp::find($_GET['id']);
            $msg = "Data Updated Successfully";
        }
        if($request->hasFile('file_attached')) {
            $fixedSize = config('global.SIZE.WHATSAPP_FILE');
            $size = $fixedSize/1000;
            $fileSize = $request->file('file_attached')->getSize();
            if($fileSize >= $fixedSize){
                errorMessage('File size should be less than '.$size.'KB', $msg_data);
            };
        }
        $tblObj->user_type = $request->user_type;
        $tblObj->trigger = $request->trigger;
        $tblObj->message = $request->message;
        if($isEdit){
            $tblObj->updated_by = session('data')['id'];
        }else{
            $tblObj->created_by = session('data')['id'];
        }
        $tblObj->save();
        $last_inserted_id = $tblObj->id;
        if($request->hasFile('file_attached')) {
            $image = $request->file('file_attached');
            $actualImage = saveSingleImage($image,'whatsapp_file',$last_inserted_id);
            $companyObj = MessageWhatsapp::find($last_inserted_id);
            $companyObj->file_attached = $actualImage;
            $companyObj->save();
        }
        successMessage($msg , $msg_data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 18-April-2022
       *   Uses :  To load view whatsapp message page
       *   @param int $id
       *   @return Response
    */
    public function view($id) {
        $data['data'] = MessageWhatsapp::find($id);
        return view('backend/messaging/whatsapp/whatsapp_view',$data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 18-April-2022
       *   Uses :  To publish or unpublish sms message records
       *   @param Request request
       *   @return Response
    */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = MessageWhatsapp::find($request->id);
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
       *   Created On : 18-April-2022
       *   Uses :  whatsapp message Edit Form Validation part will be handle by below function
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
