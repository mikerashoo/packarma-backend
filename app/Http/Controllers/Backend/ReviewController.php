<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use Yajra\DataTables\DataTables;

class ReviewController extends Controller
{
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 11-April-2022
     *   Uses :  To show review listing page
     */
    public function index()
    {
        $data['user'] = User::withTrashed()->where('approval_status', 'accepted')->get();
        $data['review_edit'] = checkPermission('review_edit');
        $data['review_view'] = checkPermission('review_view');
        $data['review_status'] = checkPermission('review_status');
        return view('backend/review/index', ["data" => $data]);
    }
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 11-April-2022
     *   Uses :  display dynamic data in datatable for review page      
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Review::with('user', 'product')->orderBy('updated_at', 'desc')->withTrashed();
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_user']) && !is_null($request['search']['search_user'])) {
                            $query->where('user_id', $request['search']['search_user']);
                        }
                        $query->get();
                    })
                    ->editColumn('user_id', function ($event) {
                        $isDeleted = isRecordDeleted($event->deleted_at);
                        if (!$isDeleted) {
                            return $event->user->name;
                        } else {
                            return '<span class="text-danger text-center">' . $event->name . '</span>';
                        }
                    })
                    ->editColumn('product_name', function ($event) {
                        return $event->product->product_name;
                    })
                    ->editColumn('rating', function ($event) {
                        return $event->rating;
                    })
                    /*  
                ->editColumn('title', function ($event) {
	                    return $event->title;
	                })  
                ->editColumn('approval_status', function ($event) {
                    $db_approval_status = $event->approval_status;
                    $bg_class = 'bg-danger';
                    if($db_approval_status == 'accepted'){
                        $bg_class = 'bg-success';
                    }else if($db_approval_status == 'rejected'){
                        $bg_class = 'bg-danger';
                    }else{
                        $bg_class = 'bg-warning';
                    }
                    $displayStatus = approvalStatusArray($db_approval_status);
                    $approvalStatus = '<span class="'.$bg_class.' text-center rounded p-1 text-white">'. $displayStatus.'</span>';
                    return $approvalStatus;                        
                })
                */
                    ->editColumn('status', function ($event) {
                        $review_status = checkPermission('review_status');
                        $status = '';
                        if ($review_status) {
                            if ($event->status == '1') {
                                $status .= ' <input type="checkbox" data-url="publishReview" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $status .= ' <input type="checkbox" data-url="publishReview" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        } else {
                            $db_status = $event->status;
                            $bg_class = 'bg-danger';
                            if ($db_status == '1') {
                                $bg_class = 'bg-success';
                            }
                            $displayStatus = displayStatus($db_status);
                            $status = '<span class="' . $bg_class . ' text-center rounded p-1 text-white">' . $displayStatus . '</span>';
                        }
                        return $status;
                    })
                    ->editColumn('review_date', function ($event) {
                        $displayDate = date('d-m-Y h:i A', strtotime($event->updated_at));
                        return $displayDate;
                    })
                    ->editColumn('action', function ($event) {
                        $review_edit = checkPermission('review_edit');
                        $review_view = checkPermission('review_view');
                        // $actions = '';
                        $actions = '<span style="white-space:nowrap;">';
                        if ($review_view) {
                            $actions .= '<a href="review_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="Review Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($review_edit) {
                            // $actions .= ' <a href="reviewApproval/'.$event->id.'" class="btn btn-info btn-sm src_data" title="Approval"><i class="fa ft-zap"></i></a>';
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['user_id', 'product_name', 'rating', 'status', 'review_date', 'action'])->setRowId('id')->make(true);
            } catch (\Exception $e) {
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
     *   Created On : 11-April-2022
     *   Uses :  To load Edit review page
     *   @param int $id
     *   @return Response
     */
    public function approval($id)
    {
        $data['data'] = Review::find($id);
        $data['approvalArray'] = approvalStatusArray();
        return view('backend/review/reviewApproval', $data);
    }


    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 11-April-2022
     *   Uses :  To store review details in table
     *   @param Request request
     *   @return Response
     */
    public function updateApprovalStatus(Request $request)
    {
        $msg_data = array();
        $msg = "";
        $validationErrors = $this->validateRequest($request);
        if (count($validationErrors)) {
            \Log::error("Review Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        if (isset($_GET['id'])) {
            //status  -  is actual status
            $getKeys = true;
            $approvalStatusArray = approvalStatusArray('', $getKeys);
            // print_r($$request->approval_status);exit;
            if (in_array($request->approval_status, $approvalStatusArray)) {
                $tableObject = Review::find($_GET['id']);
                $msg = "Approval Status Updated Successfully";
            } else {
                errorMessage('Approval Status Does not Exists.', $msg_data);
            }
        }
        $tableObject->approval_status = $request->approval_status;
        $tableObject->approved_on = date('Y-m-d H:i:s');
        $tableObject->approved_by =  session('data')['id'];
        $tableObject->admin_remark = '';
        if ($request->approval_status ==  'rejected' && !empty($request->admin_remark)) {
            $tableObject->admin_remark = $request->admin_remark;
        }
        $tableObject->save();
        successMessage($msg, $msg_data);
    }
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 11-April-2022
     *   Uses :  To publish or unpublish reviews 
     *   @param Request request
     *   @return Response
     */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = Review::find($request->id);
        $recordData->status = $request->status;
        $recordData->save();
        if ($request->status == 1) {
            successMessage('Published', $msg_data);
        } else {
            successMessage('Unpublished', $msg_data);
        }
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 11-April-2022
     *   Uses :  Review Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'approval_status' => 'string|required',
        ])->errors();
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 28-Feb-2022
     *   Uses :  To view review  
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data['data'] = Review::with('user', 'product')->find($id);
        return view('backend/review/review_view', $data);
    }
}
