<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackagingMaterial;
use Yajra\DataTables\DataTables;

class PackagingMaterialController extends Controller
{
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 30-Mar-2022
     *   Uses :  To show packaging material listing page
     */

    public function index()
    {
        $data['packaging_material_add'] = checkPermission('packaging_material_add');
        $data['packaging_material_view'] = checkPermission('packaging_material_view');
        $data['packaging_material_edit'] = checkPermission('packaging_material_edit');
        $data['packaging_material_status'] = checkPermission('packaging_material_status');
        return view('backend/packaging_material/index', ["data" => $data]);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 30-Mar-2022
     *   Uses :  display dynamic data in datatable for packaging material page
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = PackagingMaterial::select('*');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_packaging_material']) && !is_null($request['search']['search_packaging_material'])) {
                            $query->where('packaging_material_name', 'like', "%" . $request['search']['search_packaging_material'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('packaging_material', function ($event) {
                        return $event->packaging_material_name;
                    })
                    ->editColumn('material_description', function ($event) {
                        return $event->material_description;
                    })
                    ->editColumn('shelf_life', function ($event) {
                        return $event->shelf_life;
                    })
                    ->editColumn('approx_price', function ($event) {
                        return $event->approx_price;
                    })
                    ->editColumn('action', function ($event) {
                        $packaging_material_view = checkPermission('packaging_material_view');
                        $packaging_material_edit = checkPermission('packaging_material_edit');
                        $packaging_material_status = checkPermission('packaging_material_status');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($packaging_material_view) {
                            $actions .= '<a href="packaging_material_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View Packaging Material Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($packaging_material_edit) {
                            $actions .= ' <a href="packaging_material_edit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($packaging_material_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishPackagingMaterial" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishPackagingMaterial" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['packaging_material', 'material_description', 'shelf_life', 'approx_price', 'action'])->setRowId('id')->make(true);
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
     *   Created On : 30-Mar-2022
     *   Uses : To load Add packaging material page
     */
    public function add()
    {
        return view('backend/packaging_material/packaging_material_add');
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 30-Mar-2022
     *   Uses :   To load edit packaging material page
     *   @param int $id
     *   @return Response
     */
    public function edit($id)
    {
        $data['data'] = PackagingMaterial::find($id);
        return view('backend/packaging_material/packaging_material_edit', $data);
    }

    /**
     *    created by : Pradyumn Dwivedi
     *    Created On : 30 -Mar-2022
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
            \Log::error("Packaging Material Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isEditFlow = false;
        if (isset($_GET['id'])) {
            $isEditFlow = true;
            $response = PackagingMaterial::where([['packaging_material_name', strtolower($request->packaging_material_name)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Packaging Material Already Exist', $msg_data);
            }
            if($request->shelf_life == 0){
                errorMessage('Shelf Life Should be Greater Than 0', $msg_data);
            }
            if($request->price == 0){
                errorMessage('Price Should be Greater Than 0', $msg_data);
            }
            $tableObject = PackagingMaterial::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tableObject = new PackagingMaterial;
            $response = PackagingMaterial::where([['packaging_material_name', strtolower($request->packaging_material_name)]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Packaging Material Already Exist', $msg_data);
            }
            if($request->shelf_life == 0){
                errorMessage('Shelf Life Should be Greater Than 0', $msg_data);
            }
            if($request->price == 0){
                errorMessage('Price Should be Greater Than 0', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        $tableObject->packaging_material_name = $request->packaging_material_name;
        $tableObject->material_description = $request->material_description;
        if($request->shelf_life <= 30){
            $tableObject->shelf_life = 30;
        }elseif($request->shelf_life <= 60){
            $tableObject->shelf_life = 60;
        }else{
            $tableObject->shelf_life = 90;
        }
        $tableObject->approx_price = $request->price;
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
     *   Created On : 30-Mar-2022
     *   Uses :  to load packaging material view
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data['data'] = PackagingMaterial::find($id);
        return view('backend/packaging_material/packaging_material_view', $data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 30-Mar-2022
     *   Uses :  To publish or unpublish packaging material records
     *   @param Request request
     *   @return Response
     */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = PackagingMaterial::find($request->id);
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
     *   Created On : 30-Mar-2022
     *   Uses :  packaging material add|Edit Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'packaging_material_name' => 'required|string',
            'material_description' => 'required|string',
            'shelf_life' => 'required|integer',
            'price' => 'required|numeric'
        ])->errors();
    }
}
