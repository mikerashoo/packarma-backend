<?php
/*
    *	Developed by : Pradyumn Dwivedi - Mypcot Infotech 
    *	Project Name : Packult 
    *	File Name : VendorController.php
    *	File Path : app\Http\Controllers\Backend\VendorController.php
    *	Created On : 25-03-2022
    *	http ://www.mypcot.com
*/

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\VendorMaterialMapping;
use App\Models\PackagingMaterial;
use Yajra\DataTables\DataTables;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Support\Facades\Crypt;


class VendorController extends Controller
{
    public $emptyDate = '0000-00-00 00:00:00';
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 25-Mar-2022
     *   Uses :  To show vendor listing page
     */
    public function index()
    {
        $data['vendor_add'] = checkPermission('vendor_add');
        $data['vendor_view'] = checkPermission('vendor_view');
        $data['vendor_edit'] = checkPermission('vendor_edit');
        $data['vendor_status'] = checkPermission('vendor_status');
        $data['vendor_material_map'] = checkPermission('vendor_material_map');
        return view('backend/vendors/vendor_list/index', ["data" => $data]);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 25-Mar-2022
     *   Uses :  display dynamic data in datatable for vendor page
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Vendor::select('*')->Where('approval_status', '=', 'accepted')->orderBy('updated_at', 'desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if ($request['search']['search_vendor_name'] && !is_null($request['search']['search_vendor_name'])) {
                            $query->where('vendor_name', 'like', "%" . $request['search']['search_vendor_name'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('mark_featured', function ($event) {
                        $vendor_edit = checkPermission('vendor_edit');
                        $featured = '';
                        if ($vendor_edit) {
                            if ($event->is_featured == '1') {
                                $featured .= ' <input type="checkbox" data-url="featuredVendor" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $featured .= ' <input type="checkbox" data-url="featuredVendor" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        } else {
                            $db_featured = $event->is_featured;
                            $bg_class = 'bg-light-danger';
                            if ($db_featured == '1') {
                                $bg_class = 'bg-light-success';
                            }
                            $displayFeaturedStatus = displayFeatured($db_featured);
                            $featured = '<span class=" badge badge-pill ' . $bg_class . ' mb-2 mr-2">' . $displayFeaturedStatus . '</span>';
                        }
                        return $featured;
                    })
                    ->editColumn('vendor_name', function ($event) {
                        return $event->vendor_name;
                    })
                    ->editColumn('vendor_company_name', function ($event) {
                        return $event->vendor_company_name;
                    })
                    ->editColumn('gstin', function ($event) {
                        return $event->gstin ?? 'not found';
                    })
                    ->editColumn('gst_certificate', function ($event) {
                        if (str_contains($event->gst_certificate, '.pdf')) {
                            $file  = '<span><i class="fa fa-file"></i>' . $event->gst_certificate . '</span>';
                        } else {
                            // $image_path = getFile($event->gst_certificate, 'vendor_gst_certificate', false);
                            // $file  = '<img src="' . $image_path . '" alt="File Not Found" width="150" height="150">';
                            $imageUrl = ListingImageUrl('vendor_gst_certificate', $event->gst_certificate, 'image', false);
                            $file  = ' <img src="' . $imageUrl . '" width="150" height="150"/>';
                        }

                        return $file;
                    })
                    ->editColumn('vendor_status', function ($event) {
                        $vendor_status = checkPermission('vendor_status');
                        $status = '';
                        if ($vendor_status) {
                            if ($event->status == '1') {
                                $status .= ' <input type="checkbox" data-url="publishVendor" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $status .= ' <input type="checkbox" data-url="publishVendor" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        } else {
                            $db_status = $event->status;
                            $bg_class = 'bg-danger';
                            if ($db_status == '1') {
                                $bg_class = 'bg-success';
                            }
                            $displayStatus = displayStatus($db_status);
                            $status = '<span class="' . $bg_class . ' text-center rounded p-1 text-white">' . $displayStatus . '</span>';
                        }
                        return $status;
                    })
                    ->editColumn('action', function ($event) {
                        $vendor_view = checkPermission('vendor_view');
                        $vendor_edit = checkPermission('vendor_edit');
                        $vendor_material_map = checkPermission('vendor_material_map');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($vendor_view) {
                            $actions .= '<a href="vendor_view/' . $event->id . '" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($vendor_edit) {
                            $actions .= ' <a href="vendor_edit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($vendor_material_map) {
                            $actions .= ' <a href="vendor_material_map?id=' . Crypt::encrypt($event->id) . '" class="btn btn-secondary btn-sm" title="Map Material"><i class="fa ft-zap"></i></a>';
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['vendor_name', 'gstin', 'gst_certificate', 'vendor_approval_status', 'vendor_status', 'mark_featured', 'action'])->setRowId('id')->make(true);
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
     *   Created On : 25-mar-2022
     *   Uses : To load Add vendor page
     */
    public function add()
    {
        $data['phone_country'] = Country::all();
        $data['whatsapp_country'] = Country::all();
        $data['currency'] = Currency::all();
        return view('backend/vendors/vendor_list/vendor_add', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 25-Mar-2022
     *   Uses :  
     *   @param int $id
     *   @return Response
     */
    public function edit($id)
    {
        $data['data'] = Vendor::find($id);
        $data['phone_country'] = Country::all();
        $data['whatsapp_country'] = Country::all();
        $data['currency'] = Currency::all();
        if ($data['data']) {
            $data['data']->image_path = getFile($data['data']->gst_certificate, 'vendor_gst_certificate', false);
        }
        return view('backend/vendors/vendor_list/vendor_edit', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 25-Mar-2022
     *   Uses : to save add|Edit Vendor details
     *   @param Request request
     *   @return Response
     */
    public function saveFormData(Request $request)
    {
        $msg_data = array();
        $msg = "";

        $isEditFlow = false;
        if (isset($_GET['id'])) {
            $validationErrors = $this->validateEditVendorRequest($request);
            if (count($validationErrors)) {
                \Log::error("Vendor Validation Exception: " . implode(", ", $validationErrors->all()));
                errorMessage(implode("\n", $validationErrors->all()), $msg_data);
            }

            $isEditFlow = true;
            $response = Vendor::where([['vendor_name', strtolower($request->vendor_name)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Vendor Name Already Exist', $msg_data);
            }
            $CheckPhoneresponse = Vendor::where([['phone_country_id', $request->country_phone_code], ['phone', $request->phone], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($CheckPhoneresponse[0])) {
                errorMessage('Phone Number Already Exist', $msg_data);
            }
            $CheckPhoneresponse = Vendor::where([['whatsapp_country_id', $request->whatsapp_country_code], ['whatsapp_no', $request->whatsapp_no], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($CheckPhoneresponse[0])) {
                errorMessage('Whatsapp Number Already Exist', $msg_data);
            }
            if (!empty($request->whatsapp_no) && empty($request->whatsapp_country_code)) {
                errorMessage('Please Select Whatsapp Country Code.', $msg_data);
            }
            $tblObj = Vendor::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $validationErrors = $this->validateAddVendorRequest($request);
            if (count($validationErrors)) {
                \Log::error("Vendor Validation Exception: " . implode(", ", $validationErrors->all()));
                errorMessage(implode("\n", $validationErrors->all()), $msg_data);
            }

            $tblObj = new Vendor;
            $response = Vendor::where([['vendor_name', strtolower($request->vendor_name)]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage('Vendor Name Already Exist', $msg_data);
            }
            $CheckPhoneresponse = Vendor::where([['phone_country_id', ($request->country_phone_code)], ['phone', $request->phone]])->get()->toArray();
            if (isset($CheckPhoneresponse[0])) {
                errorMessage('Phone Number Already Exist', $msg_data);
            }
            $CheckPhoneresponse = Vendor::where([['whatsapp_country_id', ($request->whatsapp_country_code)], ['whatsapp_no', $request->whatsapp_no]])->get()->toArray();
            if (isset($CheckPhoneresponse[0])) {
                errorMessage('Whatsapp Number Already Exist', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        $maxPhoneCodeLength = Country::where('id', $request->phone_country_code)->get()->toArray();
        $allowedPhoneLength = $maxPhoneCodeLength[0]['phone_length'];
        if (strlen($request->phone) != $allowedPhoneLength) {
            errorMessage("Phone Number Should be $allowedPhoneLength digit long.", $msg_data);
        }
        if (!empty($request->whatsapp_country_code)) {
            $maxPhoneCodeLength = Country::where('id', $request->whatsapp_country_code)->get()->toArray();
            $allowedPhoneLength = $maxPhoneCodeLength[0]['phone_length'];
            if (strlen($request->whatsapp_no) != $allowedPhoneLength) {
                errorMessage("Whatsapp Number Should be $allowedPhoneLength digit long.", $msg_data);
            }
            $tblObj->whatsapp_country_id = $request->whatsapp_country_code;
            $tblObj->whatsapp_no = $request->whatsapp_no;
        }
        $tblObj->vendor_name = $request->vendor_name;
        if (isset($request->vendor_email)) {
            $tblObj->vendor_email = strtolower($request->vendor_email);
            $vendor_password = md5(strtolower($request->vendor_email) . $request->vendor_password);
            $tblObj->vendor_password = $vendor_password;
        }

        $tblObj->vendor_company_name = $request->vendor_company_name;
        $tblObj->gstin = $request->gstin;
        $tblObj->phone_country_id = $request->phone_country_code;
        $tblObj->phone = $request->phone;
        $tblObj->currency_id = $request->currency;
        if ($isEditFlow) {
            $tblObj->updated_by = session('data')['id'];
        } else {
            $tblObj->approved_on = date('Y-m-d H:i:s');
            $tblObj->approved_by = session('data')['id'];
            $tblObj->approval_status = 'accepted';
            $tblObj->created_by = session('data')['id'];
        }
        $tblObj->save();
        $last_inserted_id = $tblObj->id;

        if ($request->hasFile('gst_certificate')) {
            $image = $request->file('gst_certificate');
            $actualImage = saveSingleImage($image, 'vendor_gst_certificate', $last_inserted_id);
            $tblObj = Vendor::find($last_inserted_id);
            $tblObj->gst_certificate = $actualImage;
            $tblObj->save();
        }


        successMessage($msg, $msg_data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 25-Mar-2022
     *   Uses :  To load view vendor page
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data['data'] = Vendor::with('phone_country', 'whatsapp_country', 'packaging_material')->find($id);
        $data['vendor_material_mapping'] = VendorMaterialMapping::with('packaging_material', 'recommendation_engine', 'product')->where('vendor_id', '=', $id)->get();
        return view('backend/vendors/vendor_list/vendor_view', $data);
    }

    //-----------vendor approval list section---------
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 05-april-2022 
     *   Uses :  To show Pending vendor listing for approval
     */
    public function indexApprovalList()
    {
        $data['data'] = Vendor::all();
        $data['vendor_approval_view'] = checkPermission('vendor_approval_view');
        $data['vendor_approval_update'] = checkPermission('vendor_approval_update');
        return view('backend/vendors/vendor_approval_list/index', ["data" => $data]);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 05-april-2022
     *   Uses :  display dynamic data in datatable for Pending vendor in user approval list  
     *   @param Request request
     *   @return Response
     */
    public function fetchVendorApprovalList(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Vendor::with('phone_country', 'whatsapp_country', 'currency')->where('approval_status', '!=', 'accepted')->orderBy('updated_at', 'desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_name']) && !is_null($request['search']['search_name'])) {
                            $query->where('vendor_name', 'like', "%" . $request['search']['search_name'] . "%");
                        }
                        if (isset($request['search']['search_phone']) && !is_null($request['search']['search_phone'])) {
                            $query->where('phone', 'like', "%" . $request['search']['search_phone'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('name', function ($event) {
                        return $event->vendor_name;
                    })
                    ->editColumn('email', function ($event) {
                        return $event->vendor_email;
                    })
                    ->editColumn('phone', function ($event) {
                        return '+' . $event->phone_country->phone_code . ' ' . $event->phone;
                    })
                    ->editColumn('gstin', function ($event) {
                        return $event->gstin ?? 'not found';
                    })
                    ->editColumn('gst_certificate', function ($event) {
                        if (str_contains($event->gst_certificate, '.pdf')) {
                            $file  = '<span><i class="fa fa-edit"></i>' . $event->gst_certificate . '</span>';
                        } else {
                            $imageUrl = ListingImageUrl('vendor_gst_certificate', $event->gst_certificate, 'image', false);
                            $file  = ' <img src="' . $imageUrl . '" width="150" height="150"/>';
                            // $image_path = getFile($event->gst_certificate, 'vendor_gst_certificate', false);
                            // $file  = '<img src="' . $image_path . '" alt="File Not Found" width="150" height="150">';
                        }

                        return $file;
                    })
                    ->editColumn('approval_status', function ($event) {
                        $db_approval_status = $event->approval_status;
                        $bg_class = 'bg-danger';
                        if ($db_approval_status == 'accepted') {
                            $bg_class = 'bg-success';
                        } else if ($db_approval_status == 'rejected') {
                            $bg_class = 'bg-danger';
                        } else {
                            $bg_class = 'bg-warning';
                        }
                        $displayStatus = approvalStatusArray($db_approval_status);
                        $approvalStatus = '<span class="' . $bg_class . ' text-center rounded p-1 text-white">' . $displayStatus . '</span>';
                        return $approvalStatus;
                    })
                    ->editColumn('created_at', function ($event) {
                        return date('d-m-Y H:i A', strtotime($event->created_at));
                    })
                    ->editColumn('action', function ($event) {
                        $vendor_approval_list_view = checkPermission('vendor_approval_list_view');
                        $vendor_approval_list_update = checkPermission('vendor_approval_list_update');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($vendor_approval_list_view) {
                            $actions .= '<a href="vendor_approval_list_view/' . $event->id . '" class="btn btn-primary btn-sm modal_src_data" data-size="large" data-title="View Vendor Approval List Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($vendor_approval_list_update) {
                            $actions .= ' <a href="vendor_approval_list_update/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update Approval"><i class="fa fa-edit"></i></a>';
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['name', 'email', 'phone', 'gstin', 'gst_certificate', 'approval_status', 'created_at', 'action'])->setRowId('id')->make(true);
            } catch (\Exception $e) {
                \Log::error("Something Went Wrong. Error: " . $e->getMessage());
                return response([
                    'draw' => 0,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'Something went wrong',
                ]);
            }
        }
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 25-Mar-2022
     *   Uses :  To load vendor approval flow
     *   @param int $id
     *   @return Response
     */
    public function vendorApproval($id)
    {
        $data['data'] = Vendor::find($id);
        $data['approvalArray'] = approvalStatusArray();
        if ($data['data']) {
            $data['data']->image_path = getFile($data['data']->gst_certificate, 'vendor_gst_certificate', false);
        }
        return view('backend/vendors/vendor_approval_list/vendor_approval_update', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 05-April-2022
     *   Uses :  To update Vendor Approval Status
     *   @param Request request
     *   @return Response
     */
    public function saveVendorApprovalStatus(Request $request)
    {
        $msg_data = array();
        $msg = "";
        $validationErrors = $this->validateRequest($request, $_GET['id']);
        if (count($validationErrors)) {
            \Log::error("Vendor Approval Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        if (isset($_GET['id'])) {
            $getKeys = true;
            $approvalStatusArray = approvalStatusArray('', $getKeys);
            if (in_array($request->approval_status, $approvalStatusArray)) {
                $tableObject = Vendor::find($_GET['id']);
                $msg = "Approval Status Updated Successfully";
            } else {
                errorMessage('Approval Status Does not Exists.', $msg_data);
            }
        }
        $tableObject->approval_status = $request->approval_status;
        $tableObject->gstin = $request->gstin ?? '';
        $tableObject->status = 1;
        $tableObject->approved_on = date('Y-m-d H:i:s');
        $tableObject->approved_by =  session('data')['id'];
        $tableObject->admin_remark = '';
        if ($request->approval_status ==  'rejected' && !empty($request->admin_remark)) {
            $tableObject->admin_remark = $request->admin_remark;
        }
        $tableObject->save();

        if ($request->hasFile('gst_certificate')) {
            $image = $request->file('gst_certificate');
            $actualImage = saveSingleImage($image, 'vendor_gst_certificate', $_GET['id']);
            // $tblObj = Vendor::find($_GET['id']);
            $tableObject->gst_certificate = $actualImage;
            $tableObject->save();
        }


        successMessage($msg, $msg_data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 05-april-2022
     *   Uses :  To view vendor approval list details  
     *   @param int $id
     *   @return Response
     */
    public function viewApprovalList($id)
    {

        $data['data'] = Vendor::with('phone_country', 'whatsapp_country', 'packaging_material')->find($id);
        $data['vendor_material_mapping'] = VendorMaterialMapping::with('packaging_material', 'recommendation_engine', 'product')->where('vendor_id', '=', $id)->get();
        return view('backend/vendors/vendor_approval_list/vendor_approval_view', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 25-Mar-2022
     *   Uses :  Vendor approval Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request, $id)
    {
        return \Validator::make($request->all(), [
            'approval_status' => 'required',
            'gstin' => ($request->approval_status == 'accepted') ? 'required|string|min:15|max:15|unique:vendors,gstin' . ($id ? ",$id" : '') : '',
            'gst_certificate' => ($request->approval_status == 'accepted') ?  'sometimes|required|mimes:jpeg,png,jpg,pdf|max:' . config('global.MAX_IMAGE_SIZE') : '',

        ])->errors();
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 15-June-2022
     *   Uses :  Vendor Add Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateAddVendorRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'vendor_name' => 'required|string',
            'vendor_email' => 'required|string',
            'vendor_password' => 'required|string|min:8',
            'vendor_company_name' => 'required|string',
            'gstin' => 'required|string|min:15|max:15|unique:vendors,gstin' . ($request->id ? ",$request->id" : ''),
            'phone_country_code' => 'required|integer',
            'phone' => 'required|integer',
            'currency' => 'required|integer',
            'gst_certificate' => 'sometimes|required|mimes:jpeg,png,jpg,pdf|max:' . config('global.MAX_IMAGE_SIZE'),

        ])->errors();
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 25-Mar-2022
     *   Uses :  Vendor Edit Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateEditVendorRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'vendor_name' => 'required|string',
            'vendor_company_name' => 'required|string',
            'gstin' => 'required|string|min:15|max:15|unique:vendors,gstin' . ($request->id ? ",$request->id" : ''),
            'phone_country_code' => 'required|integer',
            'phone' => 'required|integer',
            'currency' => 'required|integer',
            'gst_certificate' => 'sometimes|required|mimes:jpeg,png,jpg,pdf|max:' . config('global.MAX_IMAGE_SIZE'),

        ])->errors();
    }


    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 25-Mar-2022
     *   Uses :  To Mark Featured as Vendor
     *   @param Request request
     *   @return Response
     */
    public function markFeatured(Request $request)
    {
        $msg_data = array();
        $recordData = Vendor::find($request->id);
        $recordData->is_featured = $request->status;
        $recordData->save();
        if ($request->status == 1) {
            successMessage('Vendor mark as Featured', $msg_data);
        } else {
            successMessage('Vendor unmark as Featured', $msg_data);
        }
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 01-April-2022
     *   Uses :  To publish or unpublish vendor records
     *   @param Request request
     *   @return Response
     */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = Vendor::find($request->id);
        $recordData->status = $request->status;
        $recordData->save();
        if ($request->status == 1) {
            successMessage('Published', $msg_data);
        } else {
            successMessage('Unpublished', $msg_data);
        }
    }
}
