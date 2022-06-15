<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MeasurementUnit;
use Yajra\DataTables\DataTables;

class MeasurementUnitController extends Controller
{
     /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 12-April-2022
       *   Uses :  To show measurement unit  listing page
    */
    public function index(){
        $data['measurement_unit_add'] = checkPermission('measurement_unit_add');
        $data['measurement_unit_edit'] = checkPermission('measurement_unit_edit');
        $data['measurement_unit_view'] = checkPermission('measurement_unit_view');
        $data['measurement_unit_status'] = checkPermission('measurement_unit_status');
        return view('backend/measurement_unit/index',["data"=>$data]);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 12-April-2022
     *   Uses :  display dynamic data in datatable for measurement unit page
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = MeasurementUnit::select('*')->orderBy('updated_at','desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_unit_name']) && !is_null($request['search']['search_unit_name'])) {
                            $query->where('unit_name', 'like', "%" . $request['search']['search_unit_name'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('unit_name', function ($event) {
                        return $event->unit_name;
                    })
                    ->editColumn('unit_symbol', function ($event) {
                        return $event->unit_symbol;
                    })
                    // ->editColumn('unit_form', function ($event) {
	                //     return measurementUnitForm($event->unit_form);                        
	                // })
                    ->editColumn('action', function ($event) {
                        $measurement_unit_view = checkPermission('measurement_unit_view');
                        $measurement_unit_edit = checkPermission('measurement_unit_edit');
                        $measurement_unit_status = checkPermission('measurement_unit_status');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($measurement_unit_view) {
                            $actions .= '<a href="measurement_unit_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View Measurement Unit Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($measurement_unit_edit) {
                            $actions .= ' <a href="measurement_unit_edit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($measurement_unit_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishMeasurementUnit" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishMeasurementUnit" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns([ 'unit_name','unit_symbol', 'action'])->setRowId('id')->make(true);
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
       *   Created On : 12-April-2022
       *   Uses : To load Add measurement unit page
    */
    public function add() {
        $data['measurementUnitForm'] = measurementUnitForm();
        return view('backend/measurement_unit/measurement_unit_add', $data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 12-April-2022
     *   Uses :  to load edit measurement unit page
     *   @param int $id
     *   @return Response
     */
    public function edit($id)
    {
        $data['data'] = MeasurementUnit::find($id);
        $data['measurementUnitForm'] = measurementUnitForm();
        return view('backend/measurement_unit/measurement_unit_edit', $data);
    }


    /**
     *    created by : Pradyumn Dwivedi
     *    Created On : 12-April-2022
     *   Uses : to save add/edit measurement unit form data 
     *   @param Request request
     *   @return Response
     */
    public function saveFormData(Request $request)
    {
        $msg_data = array();
        $msg = "";
        $validationErrors = $this->validateRequest($request);
        if (count($validationErrors)) {
            \Log::error("Measurement Unit Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isEditFlow = false;
        if (isset($_GET['id'])) {
            $isEditFlow = true;
            $response = MeasurementUnit::where([['unit_name', strtolower($request->unit_name)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Unit Name Already Exist', $msg_data);
            }
            $response = MeasurementUnit::where([['unit_symbol', strtolower($request->unit_symbol)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Unit Symbol Already Exist', $msg_data);
            }
            // //unit_form  -  is actual unit_form
            // $getKeys = true;
            // $measurementUnitForm = measurementUnitForm('',$getKeys);
            // if (in_array( $request->unit_form, $measurementUnitForm))
            // {
            //    $tableObject = MeasurementUnit::find($_GET['id']);
            //    $msg = "Data Updated Successfully";
            // }
            // else{
            //     errorMessage('Measurement Unit Form Does not Exists.', $msg_data);
            // }
            $tableObject = MeasurementUnit::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tableObject = new MeasurementUnit;
            $response = MeasurementUnit::where([['unit_name', strtolower($request->unit_name)]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Unit Name Already Exist', $msg_data);
            }
            $response = MeasurementUnit::where([['unit_symbol', strtolower($request->unit_symbol)]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Unit Symbol Already Exist', $msg_data);
            }
            //unit_form  -  is actual unit_form
            // $getKeys = true;
            // $measurementUnitForm = measurementUnitForm('',$getKeys);
            // if (in_array( $request->unit_form, $measurementUnitForm))
            // {
            //    $msg = "Data Updated Successfully";
            // }
            // else{
            //     errorMessage('Measurement Unit Form Does not Exists.', $msg_data);
            // }
            $msg = "Data Updated Successfully";
        }
        $tableObject->unit_form = $request->unit_form;
        $tableObject->unit_name = $request->unit_name;
        $tableObject->unit_symbol = $request->unit_symbol;
        if($isEditFlow){
            $tableObject->updated_by = session('data')['id'];
        }
        else{
            $tableObject->created_by = session('data')['id'];
        }
        $tableObject->save();
        successMessage($msg, $msg_data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 12-April-2022
     *   Uses :  to load measurement unit view
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data['data'] = MeasurementUnit::find($id);
        $data['measurementUnitForm'] = measurementUnitForm();
        return view('backend/measurement_unit/measurement_unit_view', $data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 12-April-2022
     *   Uses :  To publish or unpublish measurement unit records
     *   @param Request request
     *   @return Response
     */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = MeasurementUnit::find($request->id);
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
     *   Created On : 12-April-2022
     *   Uses :  measurement unit Add|Edit Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'unit_name' => 'required|string',
            'unit_symbol' => 'required|string',
        ])->errors();
    }
}
