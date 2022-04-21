<?php
/*
    *	Developed by : Pradyumn Dwivedi - Mypcot Infotech 
    *	Project Name : Packult 
    *	File Name : CategoryController.php
    *	File Path : app\Http\Controllers\Backend\CategoryController.php
    *	Created On : 17-03-2022
    *	http ://www.mypcot.com
*/
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Yajra\DataTables\DataTables;
class CategoryController extends Controller
{
    /**
       *   created by : Pradyumn Dwivedi
       *   Created On : 17-March-2022
       *   Uses :  To show category listing page
    */
    public function index(){
        $data['category_add'] = checkPermission('category_add');
        $data['category_edit'] = checkPermission('category_edit');
        $data['category_view'] = checkPermission('category_view');
        $data['category_status'] = checkPermission('category_status');
        return view('backend/category/index',["data"=>$data]);
    }
    
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  display dynamic data in datatable for category page
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Category::select('*');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_category_name']) && !is_null($request['search']['search_category_name'])) {
                            $query->where('category_name', 'like', "%" . $request['search']['search_category_name'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('category_name', function ($event) {
                        return $event->category_name;
                    })
                    ->editColumn('category_image_url', function ($event) {
                        $imageUrl = ListingImageUrl('category',$event->category_thumb_image,'thumb');      
                        return ' <img src="'. $imageUrl .'" />';
                    })
                    ->editColumn('action', function ($event) {
                        $category_view = checkPermission('category_view');
                        $category_edit = checkPermission('category_edit');
                        $category_status = checkPermission('category_status');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($category_view) {
                            $actions .= '<a href="category_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View Category Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($category_edit) {
                            $actions .= ' <a href="category_edit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($category_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishCategory" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishCategory" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns([ 'category_name','category_image_url', 'action'])->setRowId('id')->make(true);
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
       *   Created On : 17-Mar-2022
       *   Uses : To load Add category page
    */
    public function add() {
        return view('backend/category/category_add');
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  
     *   @param int $id
     *   @return Response
     */
    public function edit($id)
    {
        $data = Category::find($id);
        if($data){
            $data->image_path = getFile($data->category_image,'category',true);
        }
        return view('backend/category/category_edit', ["data"=>$data]);
    }


    /**
     *    created by : Pradyumn Dwivedi
     *    Created On : 28-Mar-2022
     *   Uses : to save add/edit category foem data 
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
        if (count($validationErrors)) {
            \Log::error("Category Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isEditFlow = false;
        if (isset($_GET['id'])) {
            $isEditFlow = true;
            $response = Category::where([['category_name', strtolower($request->category_name)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage(' Category Already Exist', $msg_data);
            }
            $tableObject = Category::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tableObject = new Category;
            $response = Category::where([['category_name', strtolower($request->category_name)]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Category Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        if($request->hasFile('category_image')) {
            $fixedSize = config('global.SIZE.CATEGORY');
            $size = $fixedSize/1000;
            $fileSize = $request->file('category_image')->getSize();  //check file size
            if($fileSize >= $fixedSize){
                errorMessage('Image file size should be less than '.$size.'KB', $msg_data);
            };
        }
        if($isEditFlow){
            $tableObject->updated_by = session('data')['id'];
        }else{
            $tableObject->created_by = session('data')['id'];
        }
        $tableObject->category_name = $request->category_name;
        $tableObject->save();
        $last_inserted_id = $tableObject->id;
        if($request->hasFile('category_image')) {
            $image = $request->file('category_image');
            $actualImage = saveSingleImage($image,'category',$last_inserted_id);
            $thumbImage = createThumbnail($image,'category',$last_inserted_id,'category');
            $bannerObj = Category::find($last_inserted_id);
            $bannerObj->category_image = $actualImage;
            $bannerObj->category_thumb_image = $thumbImage;
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
        $data= Category::find($id);
        if($data){
            $data->image_path = getFile($data->category_image,'category',true);
        }
        return view('backend/category/category_view', ["data"=>$data]);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  To publish or unpublish category records
     *   @param Request request
     *   @return Response
     */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = Category::find($request->id);
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
     *   Uses :  category Add|Edit Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'category_name' => 'required|string',
        ])->errors();
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  Banner Add|Edit Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateNewRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'category_name' => 'required|string',
            'category_image' => 'required',
        ])->errors();
    }
    
}
