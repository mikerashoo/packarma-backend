<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\MessageNotification;
use Illuminate\Http\Request;
use App\Models\PackagingMaterial;
use App\Models\Vendor;
use App\Models\RecommendationEngine;
use App\Models\Product;
use App\Models\VendorMaterialMapping;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;


class VendorMaterialController extends Controller
{
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 01-march-2022
     *   Uses :  To show vendor material mapping listing page
     */
    public function index()
    {
        try {
            $data['vendor'] = Vendor::withTrashed()->Where('approval_status', '=', 'accepted')->orderBy('vendor_name', 'asc')->get();
            $data['packaging_material'] = PackagingMaterial::orderBy('packaging_material_name', 'asc')->get();
            $data['vendor_material_map_add'] = checkPermission('vendor_material_map_add');
            $data['vendor_material_map_edit'] = checkPermission('vendor_material_map_edit');
            $data['vendor_material_map_view'] = checkPermission('vendor_material_map_view');
            $data['vendor_material_map_status'] = checkPermission('vendor_material_map_status');
            if (isset($_GET['id'])) {
                $data['id'] = Crypt::decrypt($_GET['id']);
            }
            return view('backend/vendors/vendor_material_mapping/index', $data);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return redirect('404');
        }
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 01-march-2022
     *   Uses :  To show vendor material mapping listing page using datatables
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = VendorMaterialMapping::with('packaging_material', 'vendor')->orderBy('updated_at', 'desc')->withTrashed();
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if ($request['search']['search_vendor'] && !is_null($request['search']['search_vendor'])) {
                            $query->where('vendor_id', $request['search']['search_vendor']);
                        }
                        if (isset($request['search']['search_material']) && !is_null($request['search']['search_material'])) {
                            $query->where('packaging_material_id', $request['search']['search_material']);
                        }
                        $query->get();
                    })
                    ->editColumn('vendor_name', function ($event) {
                        $isVendorDeleted = isRecordDeleted($event->vendor->deleted_at);
                        if (!$isVendorDeleted) {
                            return $event->vendor->vendor_name;
                        } else {
                            return '<span class="text-danger text-center">' . $event->vendor->vendor_name . '</span>';
                        }
                    })
                    ->editColumn('material_name', function ($event) {
                        return $event->packaging_material->packaging_material_name;
                    })
                    ->editColumn('min_amt_profit', function ($event) {
                        return $event->min_amt_profit;
                    })
                    ->editColumn('vendor_price', function ($event) {
                        return $event->vendor_price;
                    })
                    ->editColumn('vendor_material_map_status', function ($event) {
                        $isVendorDeleted = isRecordDeleted($event->vendor->deleted_at);

                        $vendor_material_map_status = checkPermission('vendor_material_map_status');
                        $status = '';
                        if (!$isVendorDeleted) {
                            if ($vendor_material_map_status) {
                                if ($event->status == '1') {
                                    $status .= ' <input type="checkbox" data-url="publishVendorMaterialMap" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                                } else {
                                    $status .= ' <input type="checkbox" data-url="publishVendorMaterialMap" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
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
                        }
                    })
                    ->editColumn('action', function ($event) {
                        $isVendorDeleted = isRecordDeleted($event->vendor->deleted_at);
                        $vendor_material_map_view = checkPermission('vendor_material_map_view');
                        $vendor_material_map_edit = checkPermission('vendor_material_map_edit');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($vendor_material_map_view) {
                            $actions .= '<a href="vendor_material_map_view/' . $event->id . '" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if (!$isVendorDeleted) {

                            if ($vendor_material_map_edit) {
                                $actions .= ' <a href="vendor_material_map_edit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                            }
                        } else {
                            $actions .= ' <span class="bg-danger text-center p-1 text-white" style="border-radius:20px !important;"> Deleted</span>';
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['vendor_name', 'material_name', 'min_amt_profit', 'min_stock_qty', 'vendor_material_map_status', 'action'])->setRowId('id')->make(true);
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
     *   Created On : 01-04-2022
     *   Uses : To load add vendor material mapping
     */
    public function add()
    {
        if (isset($_GET['id'])) {
            $data['vendor'][] = Vendor::find($_GET['id']);
            $data['id'] = $_GET['id'];
        } else {
            $data['vendor'] = Vendor::Where('approval_status', '=', 'accepted')->orderBy('vendor_name')->get();
        }
        // $data['product'] = Product::orderBy('product_name','asc')->get();
        $data['packaging_material'] = PackagingMaterial::orderBy('packaging_material_name')->get();
        $data['recommendation_engine'] = RecommendationEngine::all();
        return view('backend/vendors/vendor_material_mapping/vendor_material_map_add', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 07-03-2022
     *   Uses :  To load Edit Vendor material map details
     *   @param int $id
     *   @return Response
     */
    public function edit($id)
    {
        $data['data'] = VendorMaterialMapping::find($id);
        if (empty($data['data'])) {
            \Log::error("Edit address: Address id not found");
            errorMessage('Address id not found', $msg_data);
        }
        $data['vendor'] = Vendor::all();
        $data['product'] = Product::all();
        $data['packaging_material'] = PackagingMaterial::all();
        $data['recommendation_engine'] = RecommendationEngine::all();
        return view('backend/vendors/vendor_material_mapping/vendor_material_map_edit', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 01-04-2022
     *   Uses :  To store add/edit vendor material map details in table
     *   @param Request request
     *   @return Response
     */

    public function saveFormData(Request $request)
    {


        $msg_data = array();
        $msg = "";
        $validationErrors = $this->validateRequest($request);
        if (count($validationErrors)) {
            \Log::error("Vendor Material Map Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $isEditFlow = false;
        $materialData = PackagingMaterial::find($request->material);
        if (isset($_GET['id'])) {
            $isEditFlow = true;
            $response = VendorMaterialMapping::where([['vendor_id', ($request->vendor)], ['packaging_material_id', ($request->material)], ['id', '<>', $_GET['id']]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage($materialData->packaging_material_name . ' Material is Already Mapped With Selected Vendor', $msg_data);
            }
            $tblObj = VendorMaterialMapping::find($_GET['id']);
            $msg = "Data Updated Successfully";
        } else {
            $tblObj = new VendorMaterialMapping;
            $response = VendorMaterialMapping::where([['vendor_id', ($request->vendor)], ['packaging_material_id', ($request->material)]])->get()->toArray();
            if (isset($response[0])) {
                errorMessage($materialData->packaging_material_name . ' Material is Already Mapped With Selected Vendor', $msg_data);
            }
            $msg = "Data Saved Successfully";
        }
        $tblObj->vendor_id = $request->vendor;
        $tblObj->packaging_material_id = $request->material;
        $tblObj->min_amt_profit = $request->commission_rate_per_kg;
        // $tblObj->min_stock_qty = $request->commission_rate_per_qty;
        $tblObj->vendor_price = $request->vendor_price;
        if ($isEditFlow) {
            $tblObj->updated_by = session('data')['id'];
        } else {
            $tblObj->created_by = session('data')['id'];
        }
        $tblObj->save();

        $can_send_fcm_notification =  DB::table('general_settings')->where('type', 'trigger_vendor_fcm_notification')->value('value');
        if ($can_send_fcm_notification == 1) {
            $this->callVendorFcmNotification($request->vendor, $request->material);
        }

        successMessage($msg, $msg_data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 01-April-2022
     *   Uses :  To view vendor material mapping details  
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data['data'] = VendorMaterialMapping::withTrashed()->with('vendor', 'packaging_material', 'recommendation_engine', 'product')->find($id);
        return view('backend/vendors/vendor_material_mapping/vendor_material_map_view', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 07-Mar-2022
     *   Uses :  Vendor material Map Add|Edit Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [

            'vendor' => 'required|integer',
            'material' => 'required|integer',
            // 'commission_rate_per_kg' => 'required|regex:/^\d+(\.\d{0,3})?$/',
            'commission_rate_per_kg' => 'required|numeric',
            // 'commission_rate_per_qty' => 'required|regex:/^\d+(\.\d{0,3})?$/',
            'vendor_price' => 'required|numeric',
            // 'vendor_price' => 'regex:/^\d+(\.\d{3})?$/',
        ])->errors();
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 01-April-2022
     *   Uses :  To publish or unpublish vendor material Mapping 
     *   @param Request request
     *   @return Response
     */
    public function updateStatus(Request $request)
    {
        $msg_data = array();
        $recordData = VendorMaterialMapping::find($request->id);
        $recordData->status = $request->status;
        $recordData->save();
        if ($request->status == 1) {
            successMessage('Published', $msg_data);
        } else {
            successMessage('Unpublished', $msg_data);
        }
    }

    private function callVendorFcmNotification($vendor_id, $material_id)
    {
        $landingPage = 'Materials';
        if ((!empty($vendor_id) && $vendor_id > 0) && (!empty($material_id) && $material_id > 0)) {
            $material_name =  DB::table('packaging_materials')->where('id', $material_id)->value('packaging_material_name');

            $notificationData = MessageNotification::where([['user_type', 'vendor'], ['notification_name', 'material_mapping'], ['status', 1]])->first();

            if (!empty($notificationData)) {
                $notificationData['type_id'] = $material_id;
                $notificationData['image_path'] = '';
                if (!empty($notificationData['notification_image']) && \Storage::disk('s3')->exists('/notification/vendor'. '/' . $notificationData['notification_image'])) {
                    $notificationData['image_path'] = getFile($notificationData['notification_image'], 'notification/vendor');
                }

                if (empty($notificationData['page_name'])) {
                    $notificationData['page_name'] = $landingPage;
                }

                $notificationData['body'] = str_replace('$$material_name$$', $material_name, $notificationData['body']);
                $userFcmData = DB::table('vendors')->select('vendors.id', 'vendor_devices.fcm_id','vendor_devices.imei_no','vendor_devices.remember_token')
                    ->where([['vendors.id', $vendor_id], ['vendors.status', 1], ['vendors.fcm_notification', 1], ['vendors.approval_status', 'accepted'], ['vendors.deleted_at', NULL]])
                    ->leftjoin('vendor_devices', 'vendor_devices.vendor_id', '=', 'vendors.id')
                    ->get();


                if (!empty($userFcmData)) {
                    //modified by : Pradyumn Dwivedi, Modified at : 14-Oct-2022
                    $device_ids = array();
                    $imei_nos = array();
                    $i=0;
                    foreach ($userFcmData as $key => $val) {
                        if (!empty($val->remember_token)){
                            array_push($device_ids, $val->fcm_id);
                            array_push($imei_nos, $val->imei_no);
                        }
                    }
                    //modified by : Pradyumn Dwivedi, Modified at : 14-Oct-2022
                    //combine imei id and fcm as key value in new array
                    $devices_data =  array_combine($imei_nos, $device_ids);
                    sendFcmNotification($devices_data, $notificationData, 'vendor', $vendor_id);
                }
            }
        }
    }
}
