<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MessageNotification;
use Illuminate\Support\Facades\URL;
use App\Models\Language;
use Yajra\DataTables\DataTables;
class MessageNotificationController extends Controller
{
    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 19-April-2022
       *   Uses :  To show notification listing page
    */
    public function index(){
        $data['notification_add'] = checkPermission('notification_add');
        $data['notification_view'] = checkPermission('notification_view');
        $data['notification_edit'] = checkPermission('notification_edit');
        $data['notification_send'] = checkPermission('notification_send');
        $data['notification_status'] = checkPermission('notification_status');
        return view('backend/messaging/notification/index',["data"=>$data]);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 19-April-2022
       *   Uses :  display dynamic data in datatable for notification page
       *   @param Request request
       *   @return Response
    */
    public function fetch(Request $request){
        if ($request->ajax()) {
        	try {
	            $query = MessageNotification::select('*')->orderBy('updated_at','desc');             
	            return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                                            
                        if (isset($request['search']['search_title']) && ! is_null($request['search']['search_title'])) {
                            $query->where('title', 'like', "%" . $request['search']['search_title'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('title', function ($event) {
	                    return $event->title;
	                })
                    ->editColumn('user_type', function ($event) {
	                    return messageUserType($event->user_type);
	                })
                    ->editColumn('trigger', function ($event) {
	                    return messageTrigger($event->trigger);
	                })
                    ->editColumn('notification_date', function ($event) {
	                    return date('d-m-Y H:i:s', strtotime($event->notification_date));
	                })
	                ->editColumn('action', function ($event) {
                        $notification_view = checkPermission('notification_view');
	                    $notification_edit = checkPermission('notification_edit');
                        $notification_status = checkPermission('notification_status');
                        $notification_send = checkPermission('notification_send');
	                    $actions = '<span style="white-space:nowrap;">';
                        if ($notification_view) {
                            $actions .= '<a href="notification_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View Notification Message Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if($notification_edit) {
                            $actions .= ' <a href="notification_edit/'.$event->id.'" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        // if($notification_send) {
                        //     $actions .= ' <a href="notification_send/'.$event->id.'" class="btn btn-secondary btn-sm src_data" title="Send"><i class="fa fa-share"></i></a>';
                        // }
                        if($notification_status) {
                            if($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishNotificationStatus" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishNotificationStatus" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
	                }) 
	                ->addIndexColumn() 
	                ->rawColumns(['title','user_type','operation','trigger','notification_date','action'])->setRowId('id')->make(true);
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
       *   Created On : 19-April-2022
       *   Uses :  To load add notification message page
    */
    public function add() {
        $data['messageUserType'] = messageUserType();
        $data['messageTrigger'] = messageTrigger();
        return view('backend/messaging/notification/notification_add', $data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 19-April-2022
       *   Uses :  To load Edit notification message page
       *   @param int $id
       *   @return Response
    */
    public function edit($id) {
        $data['data'] = MessageNotification::find($id);
        $data['messageUserType'] = messageUserType();
        $data['messageTrigger'] = messageTrigger();
        return view('backend/messaging/notification/notification_edit', $data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 19-April-2022
       *   Uses :  To store add/edit details in table
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
            $response = MessageNotification::where([['title',strtolower($request->title)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('Notification Title is Already Exist', $msg_data);
            }
            $tblObj = MessageNotification::find($_GET['id']);
            $msg = "Data Updated Successfully";
        }else{
            $tblObj = new MessageNotification;
            $response = MessageNotification::where([['title',strtolower($request->title)]])->get()->toArray();
            if(isset($response[0])){
                errorMessage('Notification Title is Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        if($request->hasFile('notification_image')) {
            $fixedSize = config('global.SIZE.NOTIFICATION');
            $size = $fixedSize/1000;
            $fileSize = $request->file('notification_image')->getSize();
            if($fileSize >= $fixedSize){
                errorMessage('File size should be less than '.$size.'KB', $msg_data);
            };
        }
        $tblObj->title = $request->title;
        $tblObj->body = $request->notification_body;
        $tblObj->user_type = $request->user_type;
        $tblObj->trigger = $request->trigger;
        if($isEdit){
            $tblObj->updated_by = session('data')['id'];
        }else{
            $tblObj->created_by = session('data')['id'];
        }
        $tblObj->save();
        $last_inserted_id = $tblObj->id;
        if($request->hasFile('notification_image')) {
            $image = $request->file('notification_image');
            $actualImage = saveSingleImage($image,'notification',$last_inserted_id);
            $thumbImage = createThumbnail($image,'notification',$last_inserted_id,'notification');
            $bannerObj = MessageNotification::find($last_inserted_id);
            $bannerObj->notification_image = $actualImage;
            $bannerObj->notification_thumb_image = $thumbImage;
            $bannerObj->save();
        }
        successMessage($msg , $msg_data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 19-April-2022
     *   Uses :  to load notification view
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data['data'] = MessageNotification::find($id);
        if($data['data']){
            $data['data']->image_path = getFile($data['data']->notification_image,'notification',true);
        }
        $data['messageUserType'] = messageUserType();
        $data['messageTrigger'] = messageTrigger();
        return view('backend/messaging/notification/notification_view', $data);
    }

    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 19-April-2022
       *   Uses :  To publish or unpublish notification message records
       *   @param Request request
       *   @return Response
    */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = MessageNotification::find($request->id);
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
       *   Created On : 19-April-2022
       *   Uses :  notification message add|Edit Form Validation part will be handle by below function
       *   @param Request request
       *   @return Response
    */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'title' => 'required|string',
            'notification_body' => 'required|string',
	        'user_type' => 'required|string',
            'trigger' => 'required|string'
        ])->errors();
    }
}
