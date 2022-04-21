<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductForm;
use App\Models\PackagingTreatment;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 30-Mar-2022
     *   Uses :  To show sub product listing page
     */

    public function index()
    {
        $data['sub_category'] = SubCategory::all();
        $data['category'] = Category::all();
        $data['product_add'] = checkPermission('product_add');
        $data['product_edit'] = checkPermission('product_edit');
        $data['product_view'] = checkPermission('product_view');
        $data['product_status'] = checkPermission('product_status');
        return view('backend/product/index', ["data" => $data]);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 30-Mar-2022
     *   Uses :  display dynamic data in datatable for product page
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Product::with('category','sub_category');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_product_name']) && !is_null($request['search']['search_product_name'])) {
                            $query->where('product_name', 'like', "%" . $request['search']['search_product_name'] . "%");
                        }
                        if (isset($request['search']['search_category']) && ! is_null($request['search']['search_category'])) {
                            $query->where('category_id', $request['search']['search_category']);
                        }
                        if (isset($request['search']['search_sub_category']) && ! is_null($request['search']['search_sub_category'])) {
                            $query->where('sub_category_id', $request['search']['search_sub_category']);
                        }
                        $query->get();
                    })
                    ->editColumn('product_name', function ($event) {
                        return $event->product_name;
                    })
                    ->editColumn('category_name', function ($event) {
                        return $event->category->category_name;
                    })
                    ->editColumn('sub_category_name', function ($event) {
                        return $event->sub_category->sub_category_name;
                    })
                    ->editColumn('product_form', function ($event) {
                        return $event->product_form->product_form_name;
                    })
                    ->editColumn('product_image_url', function ($event) {
                        $imageUrl = ListingImageUrl('product',$event->product_thumb_image,'thumb');      
                        return ' <img src="'. $imageUrl .'" />';
                    })
                    
                    ->editColumn('action', function ($event) {
                        $product_view = checkPermission('product_view');
                        $product_edit = checkPermission('product_edit');
                        $product_status = checkPermission('product_status');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($product_view) {
                            $actions .= '<a href="product_view/' . $event->id . '" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($product_edit) {
                            $actions .= ' <a href="product_edit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($product_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishProduct" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishProduct" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['product_name', 'category_name','sub_category_name','product_form','product_image_url', 'action'])->setRowId('id')->make(true);
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
     *   Uses : To load Add sub product page
     */
    public function add()
    {
        $data['data'] = Product::all();
        $data['category'] = Category::all();
        $data['product_form'] = ProductForm::all();
        $data['sub_category'] = SubCategory::all();
        $data['packaging_treatment'] = PackagingTreatment::all();
        return view('backend/product/product_add', $data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 30-Mar-2022
     *   Uses :   To load edit product page
     *   @param int $id
     *   @return Response
     */
    public function edit($id)
    {
        $data['sub_category'] = SubCategory::all();
        $data['category'] = Category::all();
        $data['product_form'] = ProductForm::all();
        $data['packaging_treatment'] = PackagingTreatment::all();
        $data['data'] = Product::find($id);
        if($data['data']){
            $data['data']->image_path = getFile($data['data']->product_image,'product',true);
        }
        return view('backend/product/product_edit', $data);
    }

    /**
     *    created by : Pradyumn Dwivedi
     *    Created On : 28-Mar-2022
     *   Uses :   To save Add/edit product page
     *   @param Request request
     *   @return Response
     */
    public function saveFormData(Request $request)
    {
        $msg_data = array();
        $msg = "";
        if(isset($_GET['id'])) {
    		$validationErrors = $this->validateRequest($request);
    	} else {
    		$validationErrors = $this->validateNewRequest($request);
    	}
        //$validationErrors = $this->validateRequest($request);
        if (count($validationErrors)) {
            \Log::error("Product Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isEditFlow = false;
        if (isset($_GET['id'])) {
            $isEditFlow = true;
            $response = Product::where([['product_name', strtolower($request->product_name)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Product Already Exist', $msg_data);
            }
            $tableObject = Product::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tableObject = new Product;
            $response = Product::where([['product_name', strtolower($request->product_name)]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Product Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        if($request->hasFile('product_image')) {
            $fixedSize = config('global.SIZE.PRODUCT');
            $size = $fixedSize/1000;
            $fileSize = $request->file('product_image')->getSize();
            if($fileSize >= $fixedSize){
                errorMessage('Image file size should be less than '.$size.'KB', $msg_data);
            };
        }
        $tableObject->product_name = $request->product_name;
        $tableObject->product_description = $request->product_description;
        $tableObject->category_id = $request->category;
        $tableObject->sub_category_id = $request->sub_category;
        $tableObject->product_form_id = $request->product_form;
        $tableObject->packaging_treatment_id = $request->packaging_treatment;
        if($isEditFlow){
            $tableObject->updated_by = session('data')['id'];
        }else{
            $tableObject->created_by = session('data')['id'];
        }
        $tableObject->save();
        $last_inserted_id = $tableObject->id;
        if($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $actualImage = saveSingleImage($image,'product',$last_inserted_id);
            $thumbImage = createThumbnail($image,'product',$last_inserted_id,'product');
            $bannerObj = Product::find($last_inserted_id);
            $bannerObj->product_image = $actualImage;
            $bannerObj->product_thumb_image = $thumbImage;
            $bannerObj->save();
        }
        successMessage($msg, $msg_data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 30-Mar-2022
     *   Uses :  to load product view
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data['data'] = Product::with('sub_category','category','product_form','packaging_treatment')->find($id);
        if($data['data']){
            $data['data']->image_path = getFile($data['data']->product_image,'product',true);
        }
        return view('backend/product/product_view', $data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 30-Mar-2022
     *   Uses :  To publish or unpublish product records
     *   @param Request request
     *   @return Response
     */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = Product::find($request->id);
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
     *   Uses :  product Edit Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'product_name' => 'required|string',
            'product_description' => 'required|string',
            'sub_category' => 'required|integer',
            'category' => 'required|integer',
            'product_form' => 'required|integer',
            'packaging_treatment' => 'required|integer',
        ])->errors();
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 30-Mar-2022
     *   Uses :  product Add Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateNewRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'product_name' => 'required|string',
            'product_description' => 'required|string',
            'sub_category' => 'required|integer',
            'category' => 'required|integer',
            'product_form' => 'required|integer',
            'packaging_treatment' => 'required|integer',
            'product_image' => 'required',
        ])->errors();
    }
}
