<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Yajra\DataTables\DataTables;

class SubCategoryController extends Controller
{
   /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  To show sub sub category listing page
     */

    public function index()
    {
        $data['sub_category_add'] = checkPermission('sub_category_add');
        $data['sub_category_edit'] = checkPermission('sub_category_edit');
        $data['sub_category_view'] = checkPermission('sub_category_edit');
        $data['sub_category_status'] = checkPermission('sub_category_status');
        return view('backend/sub_category/index', ["data" => $data]);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  display dynamic data in datatable for sub category page
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = SubCategory::with('category');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_sub_category_name']) && !is_null($request['search']['search_sub_category_name'])) {
                            $query->where('sub_category_name', 'like', "%" . $request['search']['search_sub_category_name'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('sub_category_name', function ($event) {
                        return $event->sub_category_name;
                    })
                    ->editColumn('category_name', function ($event) {
                        return $event->category->category_name;
                    })
                    ->editColumn('sub_category_image_url', function ($event) {
                        $imageUrl = ListingImageUrl('sub_category',$event->sub_category_thumb_image,'thumb');      
                        return ' <img src="'. $imageUrl .'" />';
                    })
                    
                    ->editColumn('action', function ($event) {
                        $sub_category_view = checkPermission('sub_category_view');
                        $sub_category_edit = checkPermission('sub_category_edit');
                        $sub_category_status = checkPermission('sub_category_status');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($sub_category_view) {
                            $actions .= '<a href="sub_category_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View Sub Category Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($sub_category_edit) {
                            $actions .= ' <a href="sub_category_edit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($sub_category_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishSubCategory" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishSubCategory" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['sub_category_name', 'category_name','sub_category_image_url', 'action'])->setRowId('id')->make(true);
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
     *   Uses : To load Add sub category page
     */
    public function add()
    {
        $data['category'] = Category::all();
        return view('backend/sub_category/sub_category_add',$data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :   To load edit sub category page
     *   @param int $id
     *   @return Response
     */
    public function edit($id)
    {
        $data['data'] = SubCategory::find($id);
        $data['category'] = Category::all();
        if($data['data']){
            $data['data']->image_path = getFile($data['data']->sub_category_image,'sub_category',true);
        }
        return view('backend/sub_category/sub_category_edit', $data);
    }

    /**
     *    created by : Pradyumn Dwivedi
     *    Created On : 28-Mar-2022
     *   Uses :   To save Add/edit sub category page
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
            \Log::error("Sub Category Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isEditFlow = false;
        if (isset($_GET['id'])) {
            $isEditFlow = true;
            $response = SubCategory::where([['sub_category_name', strtolower($request->sub_category_name)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Sub Category Already Exist', $msg_data);
            }
            $tableObject = SubCategory::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tableObject = new SubCategory;
            $response = SubCategory::where([['sub_category_name', strtolower($request->sub_category_name)]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Sub Category Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        if($request->hasFile('sub_category_image')) {
            $fixedSize = config('global.SIZE.SUB_CATEGORY');
            $size = $fixedSize/1000;
            $fileSize = $request->file('sub_category_image')->getSize();
            if($fileSize >= $fixedSize){
                errorMessage('Image file size should be less than '.$size.'KB', $msg_data);
            };
        }
        $tableObject->sub_category_name = $request->sub_category_name;
        $tableObject->category_id = $request->category;
        if($isEditFlow){
            $tableObject->updated_by = session('data')['id'];
        }else{
            $tableObject->created_by = session('data')['id'];
        }
        $tableObject->save();
        $last_inserted_id = $tableObject->id;
        if($request->hasFile('sub_category_image')) {
            $image = $request->file('sub_category_image');
            $actualImage = saveSingleImage($image,'sub_category',$last_inserted_id);
            $thumbImage = createThumbnail($image,'sub_category',$last_inserted_id,'sub_category');
            $bannerObj = SubCategory::find($last_inserted_id);
            $bannerObj->sub_category_image = $actualImage;
            $bannerObj->sub_category_thumb_image = $thumbImage;
            $bannerObj->save();
        }
        successMessage($msg, $msg_data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  to load category view
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data= SubCategory::find($id);
        if($data){
            $data->image_path = getFile($data->sub_category_image,'sub_category',true);
        }
        return view('backend/sub_category/sub_category_view', ["data"=>$data]);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  To publish or unpublish sub category records
     *   @param Request request
     *   @return Response
     */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = SubCategory::find($request->id);
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
     *   Uses :  sub category Edit Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'sub_category_name' => 'required|string',
            'category' => 'required|integer',
        ])->errors();
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  sub category Add Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateNewRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'sub_category_name' => 'required|string',
            'category' => 'required|integer',
            'sub_category_image' => 'required',
        ])->errors();
    }
}
