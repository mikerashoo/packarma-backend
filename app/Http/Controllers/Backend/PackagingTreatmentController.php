<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackagingTreatment;
use Yajra\DataTables\DataTables;

class PackagingTreatmentController extends Controller
{
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 30-Mar-2022
     *   Uses :  To show packaging treatment listing page
     */

    public function index()
    {
        $data['packaging_treatment_add'] = checkPermission('packaging_treatment_add');
        $data['packaging_treatment_edit'] = checkPermission('packaging_treatment_edit');
        $data['packaging_treatment_view'] = checkPermission('packaging_treatment_view');
        $data['packaging_treatment_status'] = checkPermission('packaging_treatment_status');
        return view('backend/packaging_treatment/index', ["data" => $data]);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 30-Mar-2022
     *   Uses :  display dynamic data in datatable for packaging treatment page
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = PackagingTreatment::select('*');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_packaging_treatment']) && !is_null($request['search']['search_packaging_treatment'])) {
                            $query->where('packaging_treatment_name', 'like', "%" . $request['search']['search_packaging_treatment'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('packaging_treatment_name', function ($event) {
                        return $event->packaging_treatment_name;
                    })
                    ->editColumn('packaging_treatment_description', function ($event) {
                        return $event->packaging_treatment_description;
                    })
                    ->editColumn('mark_featured', function ($event) {
                        $packaging_treatment_edit = checkPermission('packaging_treatment_edit');
                        $featured = '';
                        if($packaging_treatment_edit) {
                            if($event->is_featured == '1') {
                                $featured .= ' <input type="checkbox" data-url="featuredPackagingTreatment" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery" checked>';
                            } else {
                                $featured .= ' <input type="checkbox" data-url="featuredPackagingTreatment" id="switchery'.$event->id.'" data-id="'.$event->id.'" class="js-switch switchery">';
                            }
                        }else{
                            $db_featured = $event->is_featured;
                            $bg_class = 'bg-light-danger';
                            if($db_featured == '1'){
                                $bg_class = 'bg-light-success';
                            }
                            $displayFeaturedStatus = displayFeatured($db_featured);
                            $featured = '<span class=" badge badge-pill '.$bg_class.' mb-2 mr-2">'. $displayFeaturedStatus.'</span>'; 
                        }
	                    return $featured;
	                })
                    ->editColumn('action', function ($event) {
                        $packaging_treatment_view = checkPermission('packaging_treatment_view');
                        $packaging_treatment_edit = checkPermission('packaging_treatment_edit');
                        $packaging_treatment_status = checkPermission('packaging_treatment_status');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($packaging_treatment_view) {
                            $actions .= '<a href="packaging_treatment_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View Packaging Treatment Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($packaging_treatment_edit) {
                            $actions .= ' <a href="packaging_treatment_edit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($packaging_treatment_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishPackagingTreatment" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishPackagingTreatment" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['packaging_treatment_name', 'packaging_treatment_description','mark_featured','action'])->setRowId('id')->make(true);
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
     *   Uses : To load Add packaging treatment page
     */
    public function add()
    {
        return view('backend/packaging_treatment/packaging_treatment_add');
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :   To load edit packaging treatment page
     *   @param int $id
     *   @return Response
     */
    public function edit($id)
    {
        $data['data'] = PackagingTreatment::find($id);
        if($data['data']){
            $data['data']->image_path = getFile($data['data']->packaging_treatment_image,'packaging_treatment',true);
        }
        return view('backend/packaging_treatment/packaging_treatment_edit', $data);
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
            \Log::error("Packaging Treatment Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isEditFlow = false;
        if (isset($_GET['id'])) {
            $isEditFlow = true;
            $response = PackagingTreatment::where([['packaging_treatment_name', strtolower($request->packaging_treatment_name)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Packaging Treatment Name Already Exist', $msg_data);
            }
            $tableObject = PackagingTreatment::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tableObject = new PackagingTreatment;
            $response = PackagingTreatment::where([['packaging_treatment_name', strtolower($request->packaging_treatment_name)]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Packaging Treatment Name Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        if($request->hasFile('packaging_treatment_image')) {
            $fixedSize = config('global.SIZE.PACKAGING_TREATMENT');
            $size = $fixedSize/1000;
            $fileSize = $request->file('packaging_treatment_image')->getSize();
            if($fileSize >= $fixedSize){
                errorMessage('Image file size should be less than '.$size.'KB', $msg_data);
            };
        }
        $tableObject->packaging_treatment_name = $request->packaging_treatment_name;
        $tableObject->packaging_treatment_description = $request->packaging_treatment_description;
        if($isEditFlow){
            $tableObject->updated_by = session('data')['id'];
        }else{
            $tableObject->created_by = session('data')['id'];
        }
        $tableObject->save();
        $last_inserted_id = $tableObject->id;
        if($request->hasFile('packaging_treatment_image')) {
            $image = $request->file('packaging_treatment_image');
            $actualImage = saveSingleImage($image,'packaging_treatment',$last_inserted_id);
            $thumbImage = createThumbnail($image,'packaging_treatment',$last_inserted_id,'packaging_treatment');
            $bannerObj = PackagingTreatment::find($last_inserted_id);
            $bannerObj->packaging_treatment_image = $actualImage;
            $bannerObj->packaging_treatment_thumb_image = $thumbImage;
            $bannerObj->save();
        }
        successMessage($msg, $msg_data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  to load packaging treatment view
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data= PackagingTreatment::find($id);
        if($data){
            $data->image_path = getFile($data->packaging_treatment_image,'packaging_treatment',true);
        }
        return view('backend/packaging_treatment/packaging_treatment_view', ["data"=>$data]);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  To publish or unpublish packaging machine records
     *   @param Request request
     *   @return Response
     */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = PackagingTreatment::find($request->id);
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
       *   created by : Pradyumn Dwivedi
       *   Created On : 18-May-2022
       *   Uses :  To Mark Featured Packaging Treatment
       *   @param Request request
       *   @return Response
    */
    public function markFeatured(Request $request)
    {
        $msg_data = array();
        $recordData = PackagingTreatment::find($request->id);
        $recordData->is_featured = $request->status;
        $recordData->save();
        if($request->status == 1) {
        	successMessage('Packaging Treatment mark as Featured', $msg_data);
        }
        else {
        	successMessage('Packaging Treatment unmark as Featured', $msg_data);
        }
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  packaging treatment add|Edit Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'packaging_treatment_name' => 'required|string',
            'packaging_treatment_description' => 'required|string',
        ])->errors();
    }
}
