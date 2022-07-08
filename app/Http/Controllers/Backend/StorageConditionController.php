<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StorageCondition;
use Yajra\DataTables\DataTables;

class StorageConditionController extends Controller
{
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  To show storage condition listing page
     */
    public function index()
    {
        $data['storage_condition_add'] = checkPermission('storage_condition_add');
        $data['storage_condition_edit'] = checkPermission('storage_condition_edit');
        $data['storage_condition_view'] = checkPermission('storage_condition_view');
        $data['storage_condition_status'] = checkPermission('storage_condition_status');
        return view('backend/storage_condition/index', ["data" => $data]);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  display dynamic data in datatable for storage condition page
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = StorageCondition::select('*')->orderBy('updated_at', 'desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_storage_condition']) && !is_null($request['search']['search_storage_condition'])) {
                            $query->where('storage_condition_title', 'like', "%" . $request['search']['search_storage_condition'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('storage_condition_title', function ($event) {
                        return $event->storage_condition_title;
                    })
                    ->editColumn('storage_condition_description', function ($event) {
                        return $event->storage_condition_description;
                    })
                    ->editColumn('updated_at', function ($event) {
                        return date('d-m-Y H:i A', strtotime($event->updated_at));
                    })
                    ->editColumn('action', function ($event) {
                        $storage_condition_view = checkPermission('storage_condition_view');
                        $storage_condition_edit = checkPermission('storage_condition_edit');
                        $storage_condition_status = checkPermission('storage_condition_status');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($storage_condition_view) {
                            $actions .= '<a href="storage_condition_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View Storage Condition Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($storage_condition_edit) {
                            $actions .= ' <a href="storage_condition_edit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($storage_condition_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishStorageCondition" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishStorageCondition" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['storage_condition_name', 'storage_condition_description', 'updated_at', 'action'])->setRowId('id')->make(true);
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
     *   Created On : 04-April-2022
     *   Uses : To load Add storage condition page
     */
    public function add()
    {
        return view('backend/storage_condition/storage_condition_add');
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  to load edit storage condition page
     *   @param int $id
     *   @return Response
     */
    public function edit($id)
    {
        $data['data'] = StorageCondition::find($id);
        return view('backend/storage_condition/storage_condition_edit', $data);
    }


    /**
     *    created by : Pradyumn Dwivedi
     *    Created On : 04-April-2022
     *   Uses : to save add/edit storage condition form data 
     *   @param Request request
     *   @return Response
     */
    public function saveFormData(Request $request)
    {
        $msg_data = array();
        $msg = "";
        $validationErrors = $this->validateRequest($request);
        if (count($validationErrors)) {
            \Log::error("Storage Condition Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isEditFlow = false;
        if (isset($_GET['id'])) {
            $isEditFlow = true;
            $response = StorageCondition::where([['storage_condition_title', strtolower($request->storage_condition_title)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Storage Condition Already Exist', $msg_data);
            }
            $tableObject = StorageCondition::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tableObject = new StorageCondition;
            $response = StorageCondition::where([['storage_condition_title', strtolower($request->storage_condition_title)]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Storage Condition Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        $tableObject->storage_condition_title = $request->storage_condition_title;
        $tableObject->storage_condition_description = $request->storage_condition_description;
        if ($isEditFlow) {
            $tableObject->updated_by = session('data')['id'];
        } else {
            $tableObject->created_by = session('data')['id'];
        }
        $tableObject->save();
        successMessage($msg, $msg_data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  to load storage condition view
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data['data'] = StorageCondition::find($id);
        return view('backend/storage_condition/storage_condition_view', $data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  To publish or unpublish storage condition records
     *   @param Request request
     *   @return Response
     */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = StorageCondition::find($request->id);
        $recordData->status = $request->status;
        $recordData->save();
        if ($request->status == 1) {
            successMessage('Published', $msg_data);
        } else {
            successMessage('Unpublished', $msg_data);
        }
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  storage condition Add|Edit Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'storage_condition_title' => 'required|string',
            // 'storage_condition_description' => 'required|string',
        ])->errors();
    }
}
