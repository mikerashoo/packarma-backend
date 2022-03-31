<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackingType;
use Yajra\DataTables\DataTables;

class PackingTypeController extends Controller
{
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 29-Mar-2022
     *   Uses :  To show packing type listing page
     */

    public function index()
    {
        $data['packing_type_add'] = checkPermission('packing_type_add');
        $data['packing_type_edit'] = checkPermission('packing_type_edit');
        $data['packing_type_view'] = checkPermission('packing_type_view');
        $data['packing_type_status'] = checkPermission('packing_type_status');
        return view('backend/packing_type/index', ["data" => $data]);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  display dynamic data in datatable for packing type page
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = PackingType::select('*');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_packing_name']) && !is_null($request['search']['search_packing_name'])) {
                            $query->where('packing_name', 'like', "%" . $request['search']['search_packing_name'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('packing_name', function ($event) {
                        return $event->packing_name;
                    })
                    ->editColumn('packing_description', function ($event) {
                        return $event->packing_description;
                    })
                    ->editColumn('action', function ($event) {
                        $packing_type_view = checkPermission('packing_type_view');
                        $packing_type_edit = checkPermission('packing_type_edit');
                        $packing_type_status = checkPermission('packing_type_status');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($packing_type_view) {
                            $actions .= '<a href="packing_type_view/' . $event->id . '" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($packing_type_edit) {
                            $actions .= ' <a href="packing_type_edit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($packing_type_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishPackingType" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishPackingType" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['packing_name', 'packing_description', 'action'])->setRowId('id')->make(true);
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
     *   Created On : 29-Mar-2022
     *   Uses : To load Add packing type page
     */
    public function add()
    {
        return view('backend/packing_type/packing_type_add');
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 29-Mar-2022
     *   Uses :   To load edit packing type page
     *   @param int $id
     *   @return Response
     */
    public function edit($id)
    {
        $data['data'] = PackingType::find($id);
        return view('backend/packing_type/packing_type_edit', $data);
    }

    /**
     *    created by : Pradyumn Dwivedi
     *    Created On : 28-Mar-2022
     *   Uses :  to save add/edit form data
     *   @param Request request
     *   @return Response
     */
    public function saveFormData(Request $request)
    {
        $msg_data = array();
        $msg = "";
        $validationErrors = $this->validateRequest($request);
        if (count($validationErrors)) {
            \Log::error("Packing Type Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isEditFlow = false;
        if (isset($_GET['id'])) {
            $isEditFlow = true;
            $response = PackingType::where([['packing_name', strtolower($request->packing_name)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Packing Name Already Exist', $msg_data);
            }
            $tableObject = PackingType::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tableObject = new PackingType;
            $response = PackingType::where([['packing_name', strtolower($request->packing_name)]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Packing Name Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        $tableObject->packing_name = $request->packing_name;
        $tableObject->packing_description = $request->packing_description;
        if($isEditFlow){
            $tableObject->updated_by = session('data')['id'];
        }else{
            $tableObject->created_by = session('data')['id'];
        }
        $tableObject->save();
        successMessage($msg, $msg_data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  to load packing type view
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data['data'] = PackingType::find($id);
        return view('backend/packing_type/packing_type_view', $data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 29-Mar-2022
     *   Uses :  To publish or unpublish packing type records
     *   @param Request request
     *   @return Response
     */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = PackingType::find($request->id);
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
     *   Created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  packing type add|Edit Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'packing_name' => 'required|string',
            'packing_description' => 'required|string',
        ])->errors();
    }
}