<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackagingMachine;
use Yajra\DataTables\DataTables;

class PackagingMachineController extends Controller
{
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 30-Mar-2022
     *   Uses :  To show packaging machine listing page
     */

    public function index()
    {
        $data['packaging_machine_add'] = checkPermission('packaging_machine_add');
        $data['packaging_machine_edit'] = checkPermission('packaging_machine_edit');
        $data['packaging_machine_view'] = checkPermission('packaging_machine_view');
        $data['packaging_machine_status'] = checkPermission('packaging_machine_status');
        return view('backend/packaging_machine/index', ["data" => $data]);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  display dynamic data in datatable for packaging machine page
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = PackagingMachine::select('*')->orderBy('updated_at', 'desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_packaging_machine']) && !is_null($request['search']['search_packaging_machine'])) {
                            $query->where('packaging_machine_name', 'like', "%" . $request['search']['search_packaging_machine'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('packaging_machine_name', function ($event) {
                        return $event->packaging_machine_name;
                    })
                    ->editColumn('packaging_machine_description', function ($event) {
                        return $event->packaging_machine_description;
                    })
                    ->editColumn('action', function ($event) {
                        $packaging_machine_view = checkPermission('packaging_machine_view');
                        $packaging_machine_edit = checkPermission('packaging_machine_edit');
                        $packaging_machine_status = checkPermission('packaging_machine_status');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($packaging_machine_view) {
                            $actions .= '<a href="packaging_machine_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View Packaging Machine Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($packaging_machine_edit) {
                            $actions .= ' <a href="packaging_machine_edit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($packaging_machine_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publishPackagingMachine" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publishPackagingMachine" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['packaging_machine_name', 'packaging_machine_description', 'action'])->setRowId('id')->make(true);
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
     *   Uses : To load Add packaging machine page
     */
    public function add()
    {
        return view('backend/packaging_machine/packaging_machine_add');
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :   To load edit packaging machine page
     *   @param int $id
     *   @return Response
     */
    public function edit($id)
    {
        $data['data'] = PackagingMachine::find($id);
        if ($data['data']) {
            $data['data']->image_path = getFile($data['data']->packaging_machine_image, 'packaging_machine', true);
        }
        return view('backend/packaging_machine/packaging_machine_edit', $data);
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
            \Log::error("Packaging Machine Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isEditFlow = false;
        if (isset($_GET['id'])) {
            $isEditFlow = true;
            $response = PackagingMachine::where([['packaging_machine_name', strtolower($request->packaging_machine_name)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Packaging Machine Name Already Exist', $msg_data);
            }
            $tableObject = PackagingMachine::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tableObject = new PackagingMachine;
            $response = PackagingMachine::where([['packaging_machine_name', strtolower($request->packaging_machine_name)]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Packaging Machine Name Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        if ($request->hasFile('packaging_machine_image')) {
            $fixedSize = config('global.SIZE.PACKAGING_MACHINE');
            $size = $fixedSize / 1000;
            $fileSize = $request->file('packaging_machine_image')->getSize();
            if ($fileSize >= $fixedSize) {
                errorMessage('Image file size should be less than ' . $size . 'KB', $msg_data);
            };
        }
        $tableObject->packaging_machine_name = $request->packaging_machine_name;
        $tableObject->packaging_machine_description = $request->packaging_machine_description;
        if ($isEditFlow) {
            $tableObject->updated_by = session('data')['id'];
        } else {
            $tableObject->created_by = session('data')['id'];
        }
        $tableObject->save();
        $last_inserted_id = $tableObject->id;
        if ($request->hasFile('packaging_machine_image')) {
            $image = $request->file('packaging_machine_image');
            $actualImage = saveSingleImage($image, 'packaging_machine', $last_inserted_id);
            $thumbImage = createThumbnail($image, 'packaging_machine', $last_inserted_id, 'packaging_machine');
            $bannerObj = PackagingMachine::find($last_inserted_id);
            $bannerObj->packaging_machine_image = $actualImage;
            $bannerObj->packaging_machine_thumb_image = $thumbImage;
            $bannerObj->save();
        }
        successMessage($msg, $msg_data);
    }

    /**
     *   Created by : Pradyumn Dwivedi
     *   Created On : 28-Mar-2022
     *   Uses :  to load packaging machine view
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data = PackagingMachine::find($id);
        if ($data) {
            $data->image_path = getFile($data->packaging_machine_image, 'packaging_machine', true);
        }
        return view('backend/packaging_machine/packaging_machine_view', ["data" => $data]);
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
        $recordData = PackagingMachine::find($request->id);
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
     *   Uses :  packaging machine add|Edit Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'packaging_machine_name' => 'required|string',
            // 'packaging_machine_description' => 'required|string',
        ])->errors();
    }
}
