<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductForm;
use Yajra\DataTables\DataTables;

class ProductFormController extends Controller
{
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 29-Mar-2022
     *   Uses :  To show product form listing page
     */

    public function index()
    {
        $data['product_form_add'] = checkPermission('product_form_add');
        $data['product_form_edit'] = checkPermission('product_form_edit');
        $data['product_form_view'] = checkPermission('product_form_view');
        $data['product_form_status'] = checkPermission('product_form_status');
        return view('backend/product_form/index', ["data" => $data]);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  display dynamic data in datatable for product form page
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = ProductForm::select('*')->orderBy('updated_at', 'desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_product_form']) && !is_null($request['search']['search_product_form'])) {
                            $query->where('product_form_name', 'like', "%" . $request['search']['search_product_form'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('product_form_name', function ($event) {
                        return $event->product_form_name;
                    })
                    ->editColumn('short_description', function ($event) {
                        return $event->short_description;
                    })
                    ->editColumn('action', function ($event) {
                        $product_form_view = checkPermission('product_form_view');
                        $product_form_edit = checkPermission('product_form_edit');
                        $product_form_status = checkPermission('product_form_status');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($product_form_view) {
                            $actions .= '<a href="product_form_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View Product Form Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($product_form_edit) {
                            $actions .= ' <a href="product_form_edit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($product_form_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishProductForm" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishProductForm" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['product_form_name', 'short_description', 'action'])->setRowId('id')->make(true);
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
     *   Created On : 28-Mar-2022
     *   Uses : To load Add product form page
     */
    public function add()
    {
        return view('backend/product_form/product_form_add');
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :   To load edit product form page
     *   @param int $id
     *   @return Response
     */
    public function edit($id)
    {
        $data['data'] = ProductForm::find($id);
        if ($data['data']) {
            $data['data']->image_path = getFile($data['data']->product_form_image, 'product_form', true);
        }
        return view('backend/product_form/product_form_edit', $data);
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
            \Log::error("Product Form Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isEditFlow = false;
        if (isset($_GET['id'])) {
            $isEditFlow = true;
            $response = ProductForm::where([['product_form_name', strtolower($request->product_form_name)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Product Form Already Exist', $msg_data);
            }
            $tableObject = ProductForm::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tableObject = new ProductForm;
            $response = ProductForm::where([['product_form_name', strtolower($request->product_form_name)]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Product Form Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        if ($request->hasFile('product_form_image')) {
            $fixedSize = config('global.SIZE.PRODUCT_FORM');
            $size = $fixedSize / 1000;
            $fileSize = $request->file('product_form_image')->getSize();
            if ($fileSize >= $fixedSize) {
                errorMessage('Image file size should be less than ' . $size . 'KB', $msg_data);
            };
        }
        $tableObject->product_form_name = $request->product_form_name;
        $tableObject->short_description = $request->short_description;
        if ($isEditFlow) {
            $tableObject->updated_by = session('data')['id'];
        } else {
            $tableObject->created_by = session('data')['id'];
        }
        $tableObject->save();
        $last_inserted_id = $tableObject->id;
        if ($request->hasFile('product_form_image')) {
            $image = $request->file('product_form_image');
            $actualImage = saveSingleImage($image, 'product_form', $last_inserted_id);
            $thumbImage = createThumbnail($image, 'product_form', $last_inserted_id, 'product_form');
            $bannerObj = ProductForm::find($last_inserted_id);
            $bannerObj->product_form_image = $actualImage;
            $bannerObj->product_form_thumb_image = $thumbImage;
            $bannerObj->save();
        }
        successMessage($msg, $msg_data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  to load product form view
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data = ProductForm::find($id);
        if ($data) {
            $data->image_path = getFile($data->product_form_image, 'product_form', true);
        }
        return view('backend/product_form/product_form_view', ["data" => $data]);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  To publish or unpublish sub product form records
     *   @param Request request
     *   @return Response
     */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = ProductForm::find($request->id);
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
     *   Uses :  product form add|Edit Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'product_form_name' => 'required|string',
            // 'short_description' => 'required|string',
        ])->errors();
    }
}
