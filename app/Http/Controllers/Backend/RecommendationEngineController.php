<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RecommendationEngine;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductForm;
use App\Models\PackingType;
use App\Models\PackagingTreatment;
use App\Models\PackagingMachine;
use App\Models\PackagingMaterial;
use App\Models\Vendor;
use Yajra\DataTables\DataTables;

class RecommendationEngineController extends Controller
{
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 31-Mar-2022
     *   Uses :  To show recommendation engine listing page
     */

    public function index()
    {
        $data['recommendation_engine_add'] = checkPermission('recommendation_engine_add');
        $data['recommendation_engine_edit'] = checkPermission('recommendation_engine_edit');
        $data['recommendation_engine_view'] = checkPermission('recommendation_engine_view');
        $data['recommendation_engine_status'] = checkPermission('recommendation_engine_status');
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
                $query = RecommendationEngine::with('product');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_recommendation_engine']) && !is_null($request['search']['search_recommendation_engine'])) {
                            $query->where('engine_name', 'like', "%" . $request['search']['search_recommendation_engine'] . "%");
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
                        $recommendation_engine_view = checkPermission('recommendation_engine_view');
                        $recommendation_engine_edit = checkPermission('recommendation_engine_edit');
                        $recommendation_engine_status = checkPermission('recommendation_engine_status');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($recommendation_engine_view) {
                            $actions .= '<a href="recommendation_engine_view/' . $event->id . '" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($recommendation_engine_edit) {
                            $actions .= ' <a href="recommendation_engine_edit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($recommendation_engine_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishRecommendationEngine" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishRecommendationEngine" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
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
        $data['packaging_machine'] = PackagingMachine::all();
        $data['packaging_treatment'] = PackagingTreatment::all();
        $data['packaging_material'] = PackagingMaterial::all();
        $data['vendor'] = Vendor::all();
        return view('backend/recommendation_engine/recommendation_engine_add',$data);
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
        $data['product'] = Product::all();
        $data['category'] = Category::all();
        $data['product_form'] = ProductForm::all();
        $data['packing_type'] = PackingType::all();
        $data['packaging_machine'] = PackagingMachine::all();
        $data['packaging_treatment'] = PackagingTreatment::all();
        $data['packaging_material'] = PackagingMaterial::all();
        $data['vendor'] = Vendor::all();
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
            $response = RecommendationEngine::where([['engine_name', strtolower($request->engine_name)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Engine Name Already Exist', $msg_data);
            }
            $tableObject = RecommendationEngine::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tableObject = new RecommendationEngine;
            $response = RecommendationEngine::where([['engine_name', strtolower($request->engine_name)]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Engine Name Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        $tableObject->engine_name = $request->engine_name;
        $tableObject->structure_type = $request->structure_type;
        $tableObject->product_id = $request->product;
        $tableObject->min_shelf_life = $request->min_shelf_life;
        $tableObject->max_shelf_life = $request->max_shelf_life;
        $tableObject->min_weight = $request->min_weight;
        $tableObject->max_weight = $request->max_weight;
        $tableObject->category_id = $request->product_category;
        $tableObject->product_form_id = $request->product_form;
        $tableObject->packing_type_id = $request->packing_type;
        $tableObject->packaging_machine_id = $request->packaging_machine;
        $tableObject->packaging_treatment_id = $request->packaging_treatment;
        $tableObject->packaging_material_id = $request->packaging_material;
        $tableObject->display_shelf_life = $request->display_shelf_life;
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
     *   Created On : 31-Mar-2022
     *   Uses :  to load recommendation engine view
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data['data'] = RecommendationEngine::find($id);
        $data['product'] = Product::all();
        $data['category'] = Category::all();
        $data['product_form'] = ProductForm::all();
        $data['packing_type'] = PackingType::all();
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
     *   Uses :  recommendation engine add|Edit Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'engine_name' => 'required|string',
            'structure_type' => 'required|string', 
            'product' => 'required|integer',
            'min_shelf_life' => 'required|integer',
            'max_shelf_life' => 'required|integer',
            'display_shelf_life' => 'required|integer',
            'min_weight' => 'required|numeric',
            'max_weight' => 'required|numeric',
            'product_category' => 'required|integer',
            'product_form' => 'required|integer',
            'packing_type' => 'required|integer',
            'packaging_machine' => 'required|integer',
            'packaging_treatment' => 'required|integer',
            'packaging_material' => 'required|integer',
        ])->errors();
    }
}
