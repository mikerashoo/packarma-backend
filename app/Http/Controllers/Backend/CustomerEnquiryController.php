<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\City;
use App\Models\User;
use App\Models\State;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductForm;
use App\Models\PackingType;
use App\Models\UserAddress;
use App\Models\MeasurementUnit;
use App\Models\CustomerEnquiry;
use App\Models\VendorQuotation;
use App\Models\VendorWarehouse;
use Yajra\DataTables\DataTables;
use App\Models\PackagingMachine;
use App\Models\StorageCondition;
use App\Models\PackagingTreatment;
use App\Models\PackagingMaterial;
use App\Models\RecommendationEngine;
use App\Models\VendorMaterialMapping;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Models\MessageNotification;

class CustomerEnquiryController extends Controller
{
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 16-April-2022
     *   Uses :  To show customer Enquiry listing page
     */
    public function index()
    {
        $data['user'] = User::withTrashed()->where('approval_status', 'accepted')->orderBy('name', 'asc')->get();
        $data['enquiryType'] = customerEnquiryType();
        $data['quoteType'] = customerEnquiryQuoteType();
        $data['customer_enquiry_add'] = checkPermission('customer_enquiry_add');
        $data['customer_enquiry_edit'] = checkPermission('customer_enquiry_edit');
        $data['customer_enquiry_view'] = checkPermission('customer_enquiry_view');
        $data['customer_enquiry_map_to_vendor'] = checkPermission('customer_enquiry_map_to_vendor');
        return view('backend/customer_section/customer_enquiry/index', ["data" => $data]);
    }
    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses : To load  customer enquiry data using datatables in custuomer enquiry listing page
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = CustomerEnquiry::with('product', 'state', 'city', 'user', 'country', 'vendor_quotation')->orderBy('updated_at', 'desc')->withTrashed();
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_user_name']) && !is_null($request['search']['search_user_name'])) {
                            $query->where('user_id', $request['search']['search_user_name']);
                        }
                        if (isset($request['search']['search_quote_type']) && !is_null($request['search']['search_quote_type'])) {
                            $query->where('quote_type', 'like', "%" . $request['search']['search_quote_type'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('description', function ($event) {
                        return $event->description;
                    })
                    ->editColumn('product_name', function ($event) {
                        return $event->product->product_name;
                    })
                    ->editColumn('user_name', function ($event) {
                        $isUserDeleted = isRecordDeleted($event->user->deleted_at);
                        if (!$isUserDeleted) {
                            return $event->user->name;
                        } else {
                            return '<span class="text-danger text-center">' . $event->user->name . '</span>';
                        }
                    })
                    ->editColumn('enquiry_status', function ($event) {
                        return customerEnquiryQuoteType($event->quote_type);
                    })
                    ->editColumn('updated_at', function ($event) {
                        return date('d-m-Y h:i A', strtotime($event->updated_at));
                    })
                    ->editColumn('action', function ($event) {
                        $isUserDeleted = isRecordDeleted($event->user->deleted_at);
                        $customer_enquiry_edit = checkPermission('customer_enquiry_edit');
                        $customer_enquiry_view = checkPermission('customer_enquiry_view');
                        $customer_enquiry_map_to_vendor = checkPermission('customer_enquiry_map_to_vendor');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($customer_enquiry_view) {
                            $actions .= '<a href="customer_enquiry_view/' . $event->id . '" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if (!$isUserDeleted) {
                            //finding vendor enquiry count
                            $vendor_quotation_count = VendorQuotation::where('customer_enquiry_id', '=', $event->id)->get()->count();
                            // if ($vendor_quotation_count == 0) {
                            if ($customer_enquiry_map_to_vendor) {
                                $actions .= ' <a href="customer_enquiry_map_to_vendor/' . $event->id . '" class="btn btn-info btn-sm src_data" title="Map Vendor"><i class="fa ft-zap"></i></a>';
                            }
                            // }
                        } else {
                            $actions .= ' <span class="bg-danger text-center p-1 text-white" style="border-radius:20px !important;">Deleted</span>';
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['description', 'user_name', 'product_name', 'enquiry_status', 'updated_at', 'action'])->setRowId('id')->make(true);
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
     *   Created On : 04-April-2022
     *   Uses : To load add customer enquiry page
     */
    public function addCustomerEnquiry()
    {
        $data['city'] = City::all();
        $data['user'] = User::where([['approval_status', 'accepted'], ['status', 1], ['deleted_at', NULL]])->get();
        $data['state'] = State::all();
        $data['vendor'] = Vendor::all();
        $data['country'] = Country::all();
        $data['product'] = Product::all();
        $data['category'] = Category::all();
        $data['sub_category'] = SubCategory::all();
        $data['product_form'] = ProductForm::all();
        $data['packing_type'] = PackingType::all();
        $data['user_address'] = UserAddress::all();
        $data['measurement_unit'] = MeasurementUnit::all();
        $data['packaging_machine'] = PackagingMachine::all();
        $data['storage_condition'] = StorageCondition::all();
        $data['packaging_treatment'] = PackagingTreatment::all();
        $data['quote_type'] = customerEnquiryQuoteType();
        return view('backend/customer_section/customer_enquiry/customer_enquiry_add', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  To store add new customer enquiry in table
     *   @param Request request
     *   @return Response
     */

    public function saveCustomerEnquiryFormData(Request $request)
    {
        $msg_data = array();
        $msg = "";
        $validationErrors = $this->validateRequest($request, 'addEnquiry');
        if (count($validationErrors)) {
            \Log::error("Customer Enquiry Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $tblObj = new CustomerEnquiry;
        $msg = "Data Saved Successfully";
        $tblObj->description = $request->description;
        $tblObj->user_id = $request->user;
        // $tblObj->order_id = $request->order_id;
        $tblObj->category_id = $request->category;
        $tblObj->sub_category_id = $request->sub_category;
        $tblObj->product_id = $request->product;
        $tblObj->shelf_life = $request->shelf_life;
        $tblObj->product_weight = $request->product_weight;
        $tblObj->measurement_unit_id = $request->measurement_unit;
        $tblObj->product_quantity = $request->product_quantity;
        $tblObj->storage_condition_id = $request->storage_condition;
        $tblObj->packaging_machine_id = $request->packaging_machine;
        $tblObj->product_form_id = $request->product_form;
        $tblObj->packing_type_id = $request->packing_type;
        $tblObj->packaging_treatment_id = $request->packaging_treatment;
        $tblObj->quote_type = $request->quote_type;
        // getting user address 
        if (isset($request->user_address)) {
            $userAddress = UserAddress::find($request->user_address);
            $tblObj->user_address_id = $request->user_address;
            $tblObj->country_id = $userAddress->country_id;
            $tblObj->state_id = $userAddress->state_id;
            $tblObj->city_id = $userAddress->city_id;
            $tblObj->pincode = $userAddress->pincode;
            $tblObj->address = $userAddress->address;
        }

        $tblObj->save();
        successMessage($msg, $msg_data);
    }

    // /**
    //  *   created by : Pradyumn Dwivedi
    //  *   Created On : 04-April-2022
    //  *   Uses :  To load customer enquiry map to vender
    //  *   @param int $id
    //  *   @return Response
    //  */
    // public function customerEnquiryMapToVendor($id)
    // {
    //     $data['data'] = CustomerEnquiry::find($id);
    //     $data['user_address'] = UserAddress::all();
    //     $data['addressType'] = addressType();
    //     $data['recommendation_engine'] = RecommendationEngine::where('product_id', $data['data']->product_id)->first()->toArray();
    //     $data['packaging_material'] = PackagingMaterial::where('id', $data['recommendation_engine']['packaging_material_id'])->first()->toArray();
    //     $data['vendor_material_map'] = VendorMaterialMapping::with('vendor')->where('packaging_material_id', $data['recommendation_engine']['packaging_material_id'])->first()->toArray();
    //     $data['vendor'] = Vendor::all()->toArray();
    //     $data['customerEnquiryType'] = customerEnquiryType();
    //     $data['vendorEnquiryStatus'] = vendorEnquiryStatus();
    //     $data['customerEnquiryQuoteType'] = customerEnquiryQuoteType();
    //     // $data['vendor_warehouse'] = VendorWarehouse::all()->toArray();        
    //     $data['city'] = City::all();
    //     $data['state'] = State::all();
    //     return view('backend/customer_section/customer_enquiry/customer_enquiry_map_to_vendor', $data);
    // }



    /**
     *   Updated by : Maaz
     *   Updated On : 16-Jun-2022
     *   Uses :  To load customer enquiry map to vender
     *   @param int $id
     *   @return Response
     */
    public function customerEnquiryMapToVendor($id)
    {
        $data['data'] = CustomerEnquiry::with('packaging_material', 'recommendation_engine')->find($id);
        $data['user_address'] = UserAddress::all();
        $data['vendor'] = Vendor::all();
        $data['addressType'] = addressType();
        $data['customerEnquiryType'] = customerEnquiryType();
        $data['vendorEnquiryStatus'] = vendorEnquiryStatus();
        $data['customerEnquiryQuoteType'] = customerEnquiryQuoteType();
        $data['customer_enquiry_id'] = getFormatid($data['data']->id, 'customer_enquiries');

        // $data['vendor_warehouse'] = VendorWarehouse::all();  

        $data['mapped_vendor'] = DB::table('vendor_quotations')->select(
            'vendor_quotations.id',
            'vendor_quotations.vendor_price',
            'vendor_quotations.mrp',
            'vendor_quotations.commission_amt',
            'vendor_quotations.lead_time',
            'vendor_quotations.enquiry_status',
            'vendor_quotations.customer_enquiry_id',
            'vendor_quotations.delivery_in_days',
            'customer_enquiries.recommendation_engine_id',
            'recommendation_engines.min_order_quantity_unit',
            'vendors.vendor_name',
            'customer_enquiries.description',
            'customer_enquiries.enquiry_type',
            'customer_enquiries.product_weight',
            'customer_enquiries.product_quantity',
            'customer_enquiries.shelf_life',
            'customer_enquiries.address',
            'measurement_units.unit_name',
            'measurement_units.unit_symbol',
        )
            ->leftjoin('vendors', 'vendor_quotations.vendor_id', '=', 'vendors.id')
            ->leftjoin('customer_enquiries', 'vendor_quotations.customer_enquiry_id', '=', 'customer_enquiries.id')
            ->leftjoin('measurement_units', 'customer_enquiries.measurement_unit_id', '=', 'measurement_units.id')
            ->leftjoin('recommendation_engines', 'customer_enquiries.recommendation_engine_id', '=', 'recommendation_engines.id')

            ->where([['vendor_quotations.customer_enquiry_id', $data['data']->id]])->get();

        // $data['mapped_vendor'] = VendorQuotation::with('vendor')->where('customer_enquiry_id', '=', $data['data']->id)->get();

        // $data['mapped_vendor'] = Vendor::whereIn('id', $data['vendor_id'])->get();
        $data['city'] = City::all();
        $data['state'] = State::all();
        return view('backend/customer_section/customer_enquiry/customer_enquiry_map_to_vendor', $data);
    }





    /**
     * 
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  To store customer enquiry map to vendor 
     *   @param Request request
     *   @return Response
     */
    // public function saveFormDataVendor(Request $request)
    // {
    //     $msg_data = array();
    //     $msg = "";
    //     $validationErrors = $this->validateRequest($request, 'vendor');
    //     if (count($validationErrors)) {
    //         \Log::error("Cutomer Enquire Map To Vendor Validation Exception: " . implode(", ", $validationErrors->all()));
    //         errorMessage(implode("\n", $validationErrors->all()), $msg_data);
    //     }
    //     $msg = "Vendor Mapped successfully to Enquiry";
    //     $vendorArr = $request->vendor;
    //     foreach ($vendorArr as $k => $val) {
    //         $tblObj = new VendorQuotation;
    //         $tblObj->user_id = $request->user[$k];
    //         $tblObj->customer_enquiry_id = $request->customer_enquiry_id[$k];
    //         $tblObj->product_id = $request->product[$k];
    //         $tblObj->vendor_id = $val;
    //         // $tblObj->vendor_warehouse_id= $request->warehouse[$k];
    //         //amount calculation section started
    //         $tblObj->vendor_price =  $request->vendor_price[$k];
    //         $tblObj->commission_amt =  $request->commission_rate[$k];
    //         $tblObj->product_quantity = $request->product_quantity[$k];
    //         $tblObj->gst_type = $request->gst_type[$k];
    //         $tblObj->gst_percentage = $request->gst_percentage[$k] ?? 0.00;
    //         $mrp_rate =  $request->commission_rate[$k] + $request->vendor_price[$k];
    //         $tblObj->mrp = $mrp_rate;
    //         $sub_total_amount = $request->product_quantity[$k] * $mrp_rate;
    //         $tblObj->sub_total = $sub_total_amount;
    //         if ($tblObj->gst_type == 'cgst+sgst' || $tblObj->gst_type == 'igst') {
    //             $tblObj->gst_type = $request->gst_type[$k];
    //             $tblObj->gst_percentage = $request->gst_percentage[$k];
    //             $tblObj->gst_amount = $mrp_rate * $request->gst_percentage[$k] / 100;
    //         } else {
    //             $gst_amount = 0;
    //         }
    //         if (isset($request->freight_amount[$k])) {
    //             $tblObj->freight_amount = $request->freight_amount[$k];
    //         } else {
    //             $freight_amount = 0;
    //         }
    //         $tblObj->total_amount = $sub_total_amount + $gst_amount + $freight_amount;
    //         // print_r($balance);exit;
    //         //storing quotation validity in variable for increasing current time with validity hours
    //         $validity_hours =  $request->quotation_validity[$k];
    //         $currentDateTime = Carbon::now();
    //         $newDateTime = Carbon::now()->addHours($validity_hours)->toArray();
    //         $tblObj->quotation_expiry_datetime =  $newDateTime['formatted'];
    //         $tblObj->lead_time =  $request->lead_time[$k];
    //         $tblObj->created_by = session('data')['id'];
    //         $tblObj->save();
    //     }
    //     successMessage($msg, $msg_data);
    // }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  To store customer enquiry map to vendor 
     *   @param Request request
     *   @return Response
     */
    public function saveFormDataVendor(Request $request)
    {
        // print_r($request->all());
        // die;
        $msg_data = array();
        $msg = "";
        $validationErrors = $this->validateRequest($request, 'vendor');
        if (count($validationErrors)) {
            \Log::error("Cutomer Enquire Map To Vendor Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $msg = "Vendor Mapped successfully to Enquiry";
        $vendorArr = $request->vendor;
        // foreach ($vendorArr as $k => $val) {
        $tblObj = new VendorQuotation;
        $tblObj->id = $request->id;
        $tblObj->user_id = $request->user;
        $tblObj->customer_enquiry_id = $request->customer_enquiry_id;
        $tblObj->product_id = $request->product;
        $tblObj->vendor_id = $request->vendor;
        $enquiry_state_id = (int)$request->enquiry_state_id;
        $warehouse_with_state = explode('|', $request->warehouse ?? '0|0');
        $warehouse_state_id = (int)($warehouse_with_state[1]);
        $warehouse = $warehouse_with_state[0];
        // $tblObj->vendor_warehouse_id= $request->warehouse;

        //amount calculation section started
        //Modified by : Pradyumn, Created at: 27-Sept-2022, Uses: calculating vendor price and commission rate per kg :START
        $vendor_price_calc = $request->vendor_price_bulk / $request->product_quantity;
        $vendor_price_per_kg = number_format((float)$vendor_price_calc, 2, '.', '');
        $tblObj->vendor_amount = $request->vendor_price_bulk;
        
        $commission_price_calc = $request->commission_rate_bulk / $request->product_quantity;
        $commission_per_kg = number_format((float)$commission_price_calc, 2, '.', '');
        $tblObj->commission = $request->commission_rate_bulk;
        
        $tblObj->vendor_price = $vendor_price_per_kg;
        $tblObj->commission_amt = $commission_per_kg;
        $tblObj->delivery_in_days = $request->delivery_in_days;
        // modified by Pradyumn, 29-sept-2022, storing delivery charge to freight amount
        if(isset($request->delivery_charges) && !empty($request->delivery_charges)){
            $freight_amount = $request->delivery_charges;
            $tblObj->freight_amount = $freight_amount;
        }else{
            $freight_amount = 0;
        }
        //Modified by : Pradyumn, Created at: 27-Sept-2022, Uses: calculating vendor price and commission rate per kg :END

        $tblObj->product_quantity = $request->product_quantity;
        $gst = $request->gst_type ?? 'not_applicable';
        
        //Created by : Pradyumn, Created at: 27-Sept-2022, Uses: storing gst percentage when gst type is selected :START
        if(!empty($request->gst_type) && $request->gst_type != 'not_applicable'){
            $tblObj->gst_percentage = $request->gst_percentage; // ?? 0.00;
        }
        else{
            $tblObj->gst_percentage = 0.00;
        }
        
        //Created by : Pradyumn, Created at: 27-Sept-2022, Uses: storing gst percentage when gst type is selected :END
        // print_r($commission_per_kg);exit;
        $mrp_rate =   $vendor_price_per_kg + $commission_per_kg; //$request->commission_rate + $request->vendor_price;
        $tblObj->mrp = $mrp_rate;
        $sub_total_amount = $request->product_quantity * $mrp_rate;
        $tblObj->sub_total = $sub_total_amount;
        $tblObj->vendor_warehouse_id = $warehouse;
        
        if ($gst == 'applicable') {
            if ($enquiry_state_id == $warehouse_state_id) {
                $gst_type = 'cgst+sgst';
            } else {
                $gst_type = 'igst';
            }
            $tblObj->gst_percentage = $request->gst_percentage ?? 0.00;
        
            // modified by : pradyumn, at: 27-Sept-2022, Desc: calculating gst on sub_total amount
            $gst_amount_calc = $sub_total_amount * ($request->gst_percentage / 100.00); //$mrp_rate * ($request->gst_percentage / 100.00);
            $gst_amount = number_format((float)$gst_amount_calc, 2, '.', '');
        } else {

            $gst_type = $gst;
            $tblObj->gst_percentage = 0.00;
            $gst_amount = 0.00;
        }
        $tblObj->gst_type = $gst_type;
        $tblObj->gst_amount = $gst_amount;
        // modified by : pradyumn, at: 27-Sept-2022, added delivery charge to grand total
        $tblObj->total_amount = $sub_total_amount + $gst_amount + $freight_amount;
        // print_r($balance);exit;
        //storing quotation validity in variable for increasing current time with validity hours
        $validity_hours =  $request->quotation_validity;
        $currentDateTime = Carbon::now();
        $newDateTime = Carbon::now()->addHours($validity_hours)->toArray();
        $tblObj->quotation_expiry_datetime =  $newDateTime['formatted'];
        $tblObj->lead_time =  $request->lead_time ?? 7;
        $tblObj->created_by = session('data')['id'];
        $final_val = $tblObj->toarray();

        $quotationData = VendorQuotation::updateOrCreate(['id' => $request->id], $final_val);
        
        CustomerEnquiry::where('id', $request->customer_enquiry_id)->update(['quote_type' => 'map_to_vendor']);

        //send fcm notification to vendor after enquiry mapped to vendor
        $can_send_fcm_notification =  DB::table('general_settings')->where('type', 'trigger_vendor_fcm_notification')->value('value');
        if ($can_send_fcm_notification == 1) {
            $this->callEnquiryMappedFcmNotification($request->vendor, $quotationData->id);
        }
        successMessage($msg, $msg_data);
    }

    /*
    *Created By: Pradyumn, 
    *Created At : 7-sept-2022, 
    *uses: order delivery fcm notification for admin 
    */
    private function callEnquiryMappedFcmNotification($vendor_id, $vendor_quotation_id)
    {
        $landingPage = 'EnquiryDetails';
        if ((!empty($vendor_id) && $vendor_id > 0) && (!empty($vendor_quotation_id) && $vendor_quotation_id > 0)) {
            $notificationData = MessageNotification::where([['user_type', 'vendor'], ['notification_name', 'enquiry_mapped_to_vendor'], ['status', 1]])->first();

            if (!empty($notificationData)) {
                $notificationData['type_id'] = $vendor_quotation_id;

                if (!empty($notificationData['notification_image']) && file_exists(URL::to('/') . '/storage/app/public/uploads/notification/vendor' . $notificationData['notification_image'])) {
                    $notificationData['image_path'] = getFile($notificationData['notification_image'], 'notification/vendor');
                }

                if (empty($notificationData['page_name'])) {
                    $notificationData['page_name'] = $landingPage;
                }

                $formatted_quotation_id = getFormatid($vendor_quotation_id, 'vendor_quotations');
                // $notificationData['title'] = str_replace('$$enquiry_id$$', $formatted_id, $notificationData['title']);
                $notificationData['body'] = str_replace('$$vendor_quotation_id$$', $formatted_quotation_id, $notificationData['body']);
                $userFcmData = DB::table('vendors')->select('vendors.id', 'vendor_devices.fcm_id')
                    ->where([['vendors.id', $vendor_id], ['vendors.status', 1], ['vendors.fcm_notification', 1], ['vendors.approval_status', 'accepted'], ['vendors.deleted_at', NULL]])
                    ->leftjoin('vendor_devices', 'vendor_devices.vendor_id', '=', 'vendors.id')
                    ->get();

                if (!empty($userFcmData)) {
                    $device_ids = array();
                    foreach ($userFcmData as $key => $val) {
                        array_push($device_ids, $val->fcm_id);
                    }

                    sendFcmNotification($device_ids, $notificationData);
                }
            }
        }
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 27-04-2022
     *   Uses : get selected vendor warehouse and vendor price, commission rate from vendor material mapping table in customer enquiry map to vendor
     */
    public function getVendorWarehouse(Request $request)
    {
        $data['vendor_data'] = Vendor::select('gstin')->find($request->vendor_id);
        $data['vendor_warehouse'] = VendorWarehouse::where("vendor_id", $request->vendor_id)->get();
        $data['recommendationData'] = RecommendationEngine::where('product_id', $request->product_id)->get();
        $data['vendorMaterialMapData'] = VendorMaterialMapping::where('packaging_material_id', $data['recommendationData'][0]->packaging_material_id)
            ->where('vendor_id', $request->vendor_id)
            ->get();
        successMessage('Data fetched successfully', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 02-Feb-2022
     *   Uses :  To view customer enquiry  
     *   @param int $id
     *   @return Response
     */
    public function view($id)
    {
        $data['data'] = CustomerEnquiry::find($id);
        $data['addressType'] = addressType();
        $data['user_address'] = UserAddress::all();
        $data['customer_enquiry_id'] = getFormatid($data['data']->id, 'customer_enquiries');
        // $data['vendors'] = VendorQuotation::with('vendor', 'vendor_warehouse')->where('customer_enquiry_id', '=', $data['data']->id)->get(); //->pluck('vendor_id')->toArray();

        $data['vendors'] = DB::table('vendor_quotations')->select(
            'vendor_quotations.id',
            'vendors.vendor_name',
            'vendors.vendor_email',
            'vendors.phone',
            'vendor_warehouses.warehouse_name',
            'vendor_warehouses.pincode',
            'states.state_name',
        )
            ->leftjoin('vendors', 'vendor_quotations.vendor_id', '=', 'vendors.id')
            ->leftjoin('vendor_warehouses', 'vendor_quotations.vendor_warehouse_id', '=', 'vendor_warehouses.id')
            ->leftjoin('states', 'vendor_warehouses.state_id', '=', 'states.id')
            ->where('vendor_quotations.customer_enquiry_id', '=', $data['data']->id)->get();

        // $data['vendor'] = Vendor::where('id', $data['vendor_id'][0]->vendor_id)->get();
        // $data['vendor_warehouse'] = VendorWarehouse::where('id', $data['vendor_id'][0]->vendor_warehouse_id)->get();
        return view('backend/customer_section/customer_enquiry/customer_enquiry_view', $data);
    }


    /**
     *   Created by : Maaz
     *   Created On : 16-Jun-2022
     *   Uses :  to load map vendor form
     *   @param int $id
     *   @return Response
     */
    public function mapVendorForm($id, $customer_enquiry_id)
    {
        // $data['vendor'] = Vendor::all()->toArray();
        $data['view_only'] = false;


        $data['customer_enquiry_data'] = CustomerEnquiry::with('user')->find($customer_enquiry_id);
        $data['vendor'] = DB::table('vendors')->select('vendors.*')
            ->where([['vendor_material_mappings.packaging_material_id', $data['customer_enquiry_data']->packaging_material_id], ['vendors.deleted_at', NULL], ['vendors.status', 1]])
            ->join('vendor_material_mappings', 'vendor_material_mappings.vendor_id', '=', 'vendors.id')
            ->groupBy('vendors.id')
            ->get();

        if ($data['customer_enquiry_data']->quote_type == 'accept_cust') {
            $accepted_vendor = DB::table('vendor_quotations')->select(
                'vendor_quotations.vendor_id',
                'vendors.vendor_name',
            )
                ->leftjoin('vendors', 'vendor_quotations.vendor_id', '=', 'vendors.id')
                ->where([['vendor_quotations.customer_enquiry_id', $customer_enquiry_id]])->first();
            $vendor_name = $accepted_vendor->vendor_name;
            $data['view_only'] = true;

            if ($id == -1) {
                displayMessage('qoutation_accepted_by_customer', $vendor_name);
            }
            // return 'No more Vendors can be map, it is accepted for Vendor ' . $vendor_name;
        } elseif ($data['customer_enquiry_data']->quote_type == 'order') {
            $data['view_only'] = true;
            if ($id == -1) {
                displayMessage('enquiry_order');
            }
        } elseif ($data['customer_enquiry_data']->quote_type == 'closed') {
            $data['view_only'] = true;
            if ($id == -1) {
                displayMessage('enquiry_closed');
            }
        }

        //get min_order_quantity_unit from from recommendation engine 
        $order_quantity_unit = RecommendationEngine::select('min_order_quantity_unit')->where('id',$data['customer_enquiry_data']->recommendation_engine_id)->first();
        $data['min_order_quantity_unit'] = $order_quantity_unit->min_order_quantity_unit;

        if ($id != -1) {
            $data['vender_quotation_details'] = DB::table('vendor_quotations')->select(
                'vendor_quotations.id',
                'vendor_quotations.product_quantity',
                'vendor_quotations.customer_enquiry_id',
                'vendor_quotations.vendor_price',
                'vendor_quotations.mrp',
                'vendor_quotations.commission_amt',
                'vendor_quotations.lead_time',
                'vendor_quotations.vendor_id',
                'vendor_quotations.gst_type',
                'vendor_quotations.vendor_warehouse_id',
                'vendor_quotations.gst_percentage',
                'vendor_quotations.delivery_in_days',
                'vendor_quotations.freight_amount',
                'vendors.vendor_name',
                'vendors.gstin',
            )
                ->leftjoin('vendors', 'vendor_quotations.vendor_id', '=', 'vendors.id')
                ->where([['vendor_quotations.id', $id]])->first();

            if ($data['vender_quotation_details']){
                $data['vender_quotation_details']->vendor_price = $data['vender_quotation_details']->vendor_price * $data['vender_quotation_details']->product_quantity;
                $data['vender_quotation_details']->commission_amt = $data['vender_quotation_details']->commission_amt * $data['vender_quotation_details']->product_quantity;
            }
        }

        // echo '<pre>';
        // print_r($data['customer_enquiry_data']);
        // die;
        // $data = VendorQuotation::find($id);

        return view('backend/customer_section//customer_enquiry/map_vendor_form', $data);
    }

    /**
     *   created by : Maaz
     *   Created On : 17-Jun-2022
     *   Uses :  Delete Mapped Vendor
     *   @param Request request
     *   @return Response
     */

    public function deleteMappedVendor(Request $request)
    {
        $id = $request->ib;
        $msg_data = array();
        try {
            \Log::error("Deleting Mapped Vendor");
            VendorQuotation::destroy($id);
            $msg = 'Mapped Vendor Deleted Successfully';
            \Log::error("Mapped Vendor Deleted Successfully");
            successMessage($msg, $msg_data);
        } catch (\Exception $e) {
            \Log::error("Something Went Wrong. Error: " . $e->getMessage());
        }
        echo $request->ib;
        die;
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 02-mar-2022
     *   Uses :  Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request, $for = "")
    {
        if ($for == 'vendor') {
            return \Validator::make($request->all(), [
                'vendor' => 'required|numeric|unique:vendor_quotations,vendor_id,' . $request->id . ',id,customer_enquiry_id,' . $request->customer_enquiry_id,
                // 'warehouse' => 'required',
                'vendor_price_bulk' => 'required|numeric|min:0|not_in:0',
                'commission_rate_bulk' => 'required|numeric|min:0|not_in:0',
                'delivery_in_days' => 'required|integer',
                'delivery_charges' => 'required|numeric',
                // 'quotation_validity.*' => 'required|numeric',
                // 'lead_time' => 'required|numeric|min:0|not_in:0',
                // 'gst_type.*' => 'required',
                'gst_percentage' => $request->gst_type == 'applicable' ? 'required|numeric|gt:0.9|lt:100' : '',
                // 'gst_percentage.*' => 'required_if:gst_type.*,igst,cgst+sgst',
            ])->errors();
        } elseif ($for == 'addEnquiry') {
            return \Validator::make($request->all(), [
                'description' => 'required|string',
                'user' => 'required|integer',
                'category' => 'required|integer',
                'sub_category' => 'required|integer',
                'product' => 'required|integer',
                'product_weight' => 'required|numeric',
                'measurement_unit' => 'required|integer',
                'product_quantity' => 'required|integer',
                'shelf_life' => 'required|integer',
                'storage_condition' => 'required|integer',
                'packaging_machine' => 'required|integer',
                'product_form' => 'required|integer',
                'packing_type' => 'required|integer',
                'packaging_treatment' => 'required|integer',
                'quote_type' => 'required|string',
                'user_address' => 'required|integer'
            ])->errors();
        } else {
            return \Validator::make($request->all(), [])->errors();
        }
    }
}
