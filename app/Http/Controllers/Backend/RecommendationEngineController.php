<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductForm;
use App\Models\PackingType;
use App\Models\StorageCondition;
use App\Models\MeasurementUnit;
use App\Models\PackagingTreatment;
use App\Models\PackagingMachine;
use App\Models\PackagingMaterial;
use Yajra\DataTables\DataTables;
use App\Models\RecommendationEngine;

class RecommendationEngineController extends Controller
{
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 31-Mar-2022
     *   Uses :  To show recommendation engine listing page (recommendation engine name replaced by packaging solution)
     */

    public function index()
    {
        $data['solutionStructureType'] = solutionStructureType();
        $data['storage_condition'] = StorageCondition::orderBy('storage_condition_title', 'asc')->get();
        $data['product'] = Product::orderBy('product_name', 'asc')->get();
        $data['category'] = Category::orderBy('category_name', 'asc')->get();
        $data['product_form'] = ProductForm::orderBy('product_form_name', 'asc')->get();
        $data['packaging_treatment'] = PackagingTreatment::orderBy('packaging_treatment_name', 'asc')->get();
        $data['packing_type'] = PackingType::orderBy('packing_name', 'asc')->get();
        $data['packaging_machine'] = PackagingMachine::orderBy('packaging_machine_name', 'asc')->get();
        $data['packaging_material'] = PackagingMaterial::orderBy('packaging_material_name', 'asc')->get();

        $data['packaging_solution_add'] = checkPermission('packaging_solution_add');
        $data['packaging_solution_edit'] = checkPermission('packaging_solution_edit');
        $data['packaging_solution_view'] = checkPermission('packaging_solution_view');
        $data['packaging_solution_status'] = checkPermission('packaging_solution_status');
        return view('backend/recommendation_engine/index', ["data" => $data]);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 31-Mar-2022
     *   Uses :  display dynamic data in datatable for recommendation engine page
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = RecommendationEngine::with('product')->orderBy('updated_at', 'desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_recommendation_engine']) && !empty($request['search']['search_recommendation_engine'])) {
                            $query->where('engine_name', 'like', "%" . $request['search']['search_recommendation_engine'] . "%");
                        }
                        if (isset($request['search']['search_structure_type']) && !empty($request['search']['search_structure_type'])) {
                            $query->where('structure_type', 'like', "%" . $request['search']['search_structure_type'] . "%");
                        }
                        if (isset($request['search']['search_storage_condition']) && !empty($request['search']['search_storage_condition'])) {
                            $query->where('storage_condition_id', $request['search']['search_storage_condition']);
                        }
                        if (isset($request['search']['search_product_name']) && !empty($request['search']['search_product_name'])) {
                            $query->where('product_id', $request['search']['search_product_name']);
                        }
                        if (isset($request['search']['search_category_name']) && !empty($request['search']['search_category_name'])) {
                            $query->where('category_id', $request['search']['search_category_name']);
                        }
                        if (isset($request['search']['search_product_form']) && !empty($request['search']['search_product_form'])) {
                            $query->where('product_form_id', $request['search']['search_product_form']);
                        }
                        if (isset($request['search']['search_packaging_treatment']) && !empty($request['search']['search_packaging_treatment'])) {
                            $query->where('packaging_treatment_id', $request['search']['search_packaging_treatment']);
                        }
                        if (isset($request['search']['search_packing_type']) && !empty($request['search']['search_packing_type'])) {
                            $query->where('packing_type_id', $request['search']['search_packing_type']);
                        }
                        if (isset($request['search']['search_packaging_machine']) && !empty($request['search']['search_packaging_machine'])) {
                            $query->where('packaging_machine_id', $request['search']['search_packaging_machine']);
                        }
                        if (isset($request['search']['search_packaging_material']) && !empty($request['search']['search_packaging_material'])) {
                            $query->where('packaging_material_id', $request['search']['search_packaging_material']);
                        }
                        $query->get();
                    })
                    ->editColumn('engine_name', function ($event) {
                        return $event->engine_name;
                    })
                    ->editColumn('structure_type', function ($event) {
                        return $event->structure_type;
                    })
                    ->editColumn('product_name', function ($event) {
                        return $event->product->product_name;
                    })
                    ->editColumn('action', function ($event) {
                        $packaging_solution_view = checkPermission('packaging_solution_view');
                        $packaging_solution_edit = checkPermission('packaging_solution_edit');
                        $packaging_solution_status = checkPermission('packaging_solution_status');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($packaging_solution_view) {
                            $actions .= '<a href="packaging_solution_view/' . $event->id . '" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($packaging_solution_edit) {
                            $actions .= ' <a href="packaging_solution_edit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($packaging_solution_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishPackagingSolution" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishPackagingSolution" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['engine_name', 'structure_type', 'product_name', 'action'])->setRowId('id')->make(true);
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
     *   Created On : 31-Mar-2022
     *   Uses : To load Add recommendation engine page
     */
    public function add()
    {
        $data['product'] = Product::all();
        $data['category'] = Category::all();
        $data['product_form'] = ProductForm::all();
        $data['packing_type'] = PackingType::all();
        $data['storage_condition'] = StorageCondition::all();
        $data['packaging_machine'] = PackagingMachine::all();
        $data['packaging_treatment'] = PackagingTreatment::all();
        $data['packaging_material'] = PackagingMaterial::all();
        $data['solutionStructureType'] = solutionStructureType();
        $data['vendor'] = Vendor::all();
        $data['data'] = RecommendationEngine::all();

        return view('backend/recommendation_engine/recommendation_engine_add', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 27-04-2022
     *   Uses : get product details from product table in recrommendation engine selected product from dropdown  
     */
    public function getProductDetails(Request $request)
    {
        $productData = Product::with('category', 'product_form', 'packaging_treatment','recommendation_engine')
        ->with(['units' => function($query){
            $query->select('id', 'unit_name', 'unit_symbol');
        }])
        ->find($request->product_id);

        $data['category'] = $productData->category;
        $data['product_form'] = $productData->product_form;
        $data['packaging_treatment'] = $productData->packaging_treatment;
        $data['recommendation_engine'] = $productData['recommendation_engine'];
        $data['units'] = $productData['units'];

        successMessage('Data fetched successfully', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 17-Sept-2022
     *   Uses :To get measurement unit to show in label of min/max product weight  
     */
    public function fetchMeasurementUnit(Request $request)
    {
        $data['data'] = MeasurementUnit::where('id',$request->id)->get();
        return response()->json($data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 31-Mar-2022
     *   Uses :   To load edit recommendation engine page
     *   @param int $id
     *   @return Response
     */
    public function edit($id)
    {
        $data['data'] = RecommendationEngine::find($id);
        $data['vendor'] = Vendor::all();
        $data['product'] = Product::all();
        $data['category'] = Category::all();
        $data['product_form'] = ProductForm::all();
        $data['packing_type'] = PackingType::all();
        $data['storage_condition'] = StorageCondition::all();
        $data['packaging_machine'] = PackagingMachine::all();
        $data['packaging_treatment'] = PackagingTreatment::all();
        $data['packaging_material'] = PackagingMaterial::all();
        $data['solutionStructureType'] = solutionStructureType();
        $data['initial_unit'] = Product::with('units')->find($data['data']->product_id);
        // print_r($data);exit;
        return view('backend/recommendation_engine/recommendation_engine_edit', $data);
    }

    /**
     *    created by : Pradyumn Dwivedi
     *    Created On : 31-Mar-2022
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
            \Log::error("Recommendation Engine Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isEditFlow = false;
        if (isset($_GET['id'])) {
            $isEditFlow = true;
            // $displayValue = true;
            $response = RecommendationEngine::where([['engine_name', strtolower($request->packaging_solution)],['product_id', $request->product],['packaging_material_id', $request->packaging_material], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage(__('packaging_solution.packaging_solution_already_exit_for_selected_product_and_material'), $msg_data);
            }

            $solutionStructureType = solutionStructureType();
            if (in_array($request->structure_type, $solutionStructureType)) {
                $tableObject = RecommendationEngine::find($_GET['id']);
                $msg = "Data Updated Successfully";
            } else {
                errorMessage('Structure type does not exist.', $msg_data);
            }
            
        } 
        else {
            $response = RecommendationEngine::where([['engine_name', strtolower($request->packaging_solution)],['product_id', $request->product],['packaging_material_id', $request->packaging_material]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage(__('packaging_solution.packaging_solution_already_exit_for_selected_product_and_material'), $msg_data);
            }

            $solutionStructureType = solutionStructureType();
            if (in_array($request->structure_type, $solutionStructureType)) {
                $tableObject = new RecommendationEngine;
                $msg = "Data Updated Successfully";
            } else {
                errorMessage('Structure type does not exist.', $msg_data);
            }
        }
        $tableObject->engine_name = $request->packaging_solution;
        $tableObject->structure_type = $request->structure_type;
        // print_r($request->sequence);exit;
        $tableObject->sequence = $request->sequence;
        $tableObject->product_id = $request->product;
        $tableObject->min_shelf_life = $request->min_shelf_life ?? 1;
        $tableObject->max_shelf_life = $request->max_shelf_life ?? 100;
        $tableObject->min_weight = $request->min_weight;
        $tableObject->max_weight = $request->max_weight;
        $tableObject->category_id = $request->product_category;
        $tableObject->product_form_id = $request->product_form;
        $tableObject->packing_type_id = $request->packing_type;
        $tableObject->packaging_machine_id = $request->packaging_machine;
        $tableObject->packaging_treatment_id = $request->packaging_treatment;
        $tableObject->packaging_material_id = $request->packaging_material;
        $tableObject->storage_condition_id = $request->storage_condition;
        $tableObject->display_shelf_life = $request->display_shelf_life;
        $tableObject->min_order_quantity = $request->min_order_quantity;
        $tableObject->min_order_quantity_unit = $request->min_order_quantity_unit;
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
     *   Created On : 31-Mar-2022
     *   Uses :  to load recommendation engine view
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data['data'] = RecommendationEngine::find($id);
        $data['product'] = Product::find($data['data']['product_id']);
        $data['category'] = Category::all();
        $data['product_form'] = ProductForm::all();
        $data['packing_type'] = PackingType::all();
        $data['storage_condition'] = StorageCondition::all();
        $data['packaging_machine'] = PackagingMachine::all();
        $data['packaging_treatment'] = PackagingTreatment::all();
        $data['packaging_material'] = PackagingMaterial::all();
        $data['vendor'] = Vendor::all();
        return view('backend/recommendation_engine/recommendation_engine_view', $data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 29-Mar-2022
     *   Uses :  To publish or unpublish recommendation engine records
     *   @param Request request
     *   @return Response
     */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = RecommendationEngine::find($request->id);
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
     *   Created On : 28-Mar-2022
     *   Uses :  recommendation engine add|Edit Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make(
            $request->all(),
            [
                'packaging_solution' => 'required|string',
                'structure_type' => 'required|string',
                'sequence' => 'required|integer',
                'product' => 'required|integer',
                // 'min_shelf_life' => 'required|integer',
                // 'max_shelf_life' => 'required|integer',
                'display_shelf_life' => 'required|integer',
                'min_weight' => 'required|numeric',
                'max_weight' => 'required|numeric',
                'product_category' => 'required|integer',
                'product_form' => 'required|integer',
                'packing_type' => 'required|integer',
                'packaging_machine' => 'required|integer',
                'packaging_treatment' => 'required|integer',
                'packaging_material' => 'required|integer',
                'storage_condition' => 'required|integer',
                'min_order_quantity' => 'required|numeric',
                'min_order_quantity_unit' => 'required|string'
            ])->errors();
    }
}
