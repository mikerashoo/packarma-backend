<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Category;
use App\Models\GeneralSetting;
use App\Models\SubCategory;
use App\Models\ProductForm;
use App\Models\PackingType;
use App\Models\OrderPayment;
use App\Models\MeasurementUnit;
use App\Models\StorageCondition;
use App\Models\PackagingMachine;
use App\Models\VendorQuotation;
use App\Models\RecommendationEngine;
use App\Models\PackagingTreatment;
use App\Models\PackagingMaterial;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use PDF;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\URL;
use App\Models\MessageNotification;

class OrderController extends Controller
{
    /**
     *  created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  To show order  listing page
     */
    public function index()
    {
        $data['user'] = User::withTrashed()->where('approval_status', 'accepted')->get();
        $data['vendor'] = Vendor::withTrashed()->where('approval_status', 'accepted')->get();
        $data['packaging_material'] = PackagingMaterial::orderBy('packaging_material_name', 'asc')->get();
        $data['product'] = Product::orderBy('product_name', 'asc')->get();
        $data['paymentStatus'] = paymentStatus();
        $data['deliveryStatus'] = deliveryStatus();
        $data['order_view'] = checkPermission('order_view');
        $data['order_delivery_update'] = checkPermission('order_delivery_update');
        $data['order_payment_update'] = checkPermission('order_payment_update');
        $data['vendor_payment_update'] = checkPermission('vendor_payment_update');
        return view('backend/order/order_list/index', ["data" => $data]);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  display dynamic data in datatable for Contactus  page
     *   @param Request request
     *   @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Order::with('user', 'vendor', 'currency')->orderBy('updated_at', 'desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_order_id']) && !is_null($request['search']['search_order_id'])) {
                            $query->where('id', $request['search']['search_order_id']);
                        }
                        if (isset($request['search']['search_user_id']) && !is_null($request['search']['search_user_id'])) {
                            $query->where('user_id', $request['search']['search_user_id']);
                        }
                        if (isset($request['search']['search_vendor_id']) && !is_null($request['search']['search_vendor_id'])) {
                            $query->where('vendor_id', $request['search']['search_vendor_id']);
                        }
                        if (isset($request['search']['search_delivery_status']) && !is_null($request['search']['search_delivery_status'])) {
                            $query->where('order_delivery_status', 'like', "%" . $request['search']['search_delivery_status'] . "%");
                        }
                        if (isset($request['search']['search_packaging_material']) && !is_null($request['search']['search_packaging_material'])) {
                            $query->where('packaging_material_id', $request['search']['search_packaging_material']);
                        }
                        if (isset($request['search']['search_product_name']) && !is_null($request['search']['search_product_name'])) {
                            $query->where('product_id', $request['search']['search_product_name']);
                        }
                        $query->get();
                    })
                    ->editColumn('user_name', function ($event) {
                        $isUserDeleted = isRecordDeleted($event->user->deleted_at);
                        if (!$isUserDeleted) {
                            return $event->user->name;
                        } else {
                            return '<span class="text-danger text-center">' . $event->user->name . '</span>';
                        }
                    })
                    ->editColumn('vendor_name', function ($event) {
                        $isVendorDeleted = isRecordDeleted($event->vendor->deleted_at);
                        if (!$isVendorDeleted) {
                            return $event->vendor->vendor_name;
                        } else {
                            return '<span class="text-danger text-center">' . $event->vendor->vendor_name . '</span>';
                        }
                    })
                    ->editColumn('grand_total', function ($event) {
                        return $event->currency->currency_symbol . $event->grand_total;
                    })
                    ->editColumn('product_quantity', function ($event) {
                        return $event->product_quantity;
                    })
                    ->editColumn('order_delivery_status', function ($event) {
                        return deliveryStatus($event->order_delivery_status);
                    })
                    // ->editColumn('payment_status', function ($event) {
                    //     return paymentStatus($event->customer_payment_status);
                    // })
                    ->editColumn('packaging_material', function ($event) {
                        return $event->packaging_material->packaging_material_name;
                    })
                    ->editColumn('action', function ($event) {
                        $orderID = Crypt::encrypt($event->id);

                        $url = URL::temporarySignedRoute(
                            'invoice_pdf',
                            now()->addDays(config('global.TEMP_URL_EXP_DAYS_FOR_INVOICE')),
                            [$orderID]
                        );
                        $order_view = checkPermission('order_view');
                        $order_pdf = checkPermission('order_pdf');
                        $order_delivery_update = checkPermission('order_delivery_update');
                        $order_payment_update = checkPermission('order_payment_update');
                        $vendor_payment_update = checkPermission('vendor_payment_update');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($order_view) {
                            $actions .= '<a href="order_view/' . $event->id . '" class="btn btn-primary btn-sm src_data" title="View"><i class="fa fa-eye"></i></a>';
                        }

                        if ($order_pdf) {
                            $actions .= '  <a href="' . $url . '" class="btn btn-success btn-sm" title="Pdf" target="_blank"><i class="fa fa-file-pdf-o"></i></a>';
                        }

                        if ($event->order_delivery_status != 'cancelled' && $event->order_delivery_status != 'delivered') {
                            if ($order_delivery_update) {
                                $actions .= '  <a href="order_delivery_update/' . $event->id . '" class="btn btn-info btn-sm src_data" title="Update Delivery"><i class="fa fa-truck"></i></a>';
                            }
                        }
                        if ($event->customer_pending_payment != 0.00) {
                            if ($order_payment_update) {
                                $actions .= '  <a href="orderPaymentUpdate/' . $event->id . '" class="btn btn-secondary btn-sm src_data" title="Customer Payment"><i class="fa fa-money"></i></a>';
                            }
                        }
                        if ($event->vendor_pending_payment != 0) {
                            if ($vendor_payment_update) {
                                $actions .= ' <a href="vendor_payment_list?id=' . Crypt::encrypt($event->id) . '" class="btn btn-warning btn-sm " title="Vendor Payment"><i class="fa fa-money"></i></a>';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['user_name', 'vendor_name', 'grand_total', 'product_quantity', 'order_delivery_status', 'packaging_material', 'action'])->setRowId('id')->make(true);
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
     *   Created On : 04-April-2022
     *   Uses :  To load update order delivery status page
     *   @param int $id
     *   @return Response
     */
    public function updateOrderDelivery($id)
    {
        $data['data'] = Order::with('user', 'product', 'vendor', 'packaging_material')->find($id);
        $data['deliveryStatus'] = deliveryStatus();
        $data['paymentStatus'] = paymentStatus();
        $data['order_id'] = getFormatid($data['data']->id, 'orders');
        return view('backend/order/order_list/order_delivery_status_update', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-Mar-2022
     *   Uses :  To store order delivery status in table
     *   @param Request request
     *   @return Response
     */
    public function updateDeliveryStatusData(Request $request)
    {
        $msg_data = array();
        $msg = "";
        $validationErrors = $this->validateRequest($request);
        if (count($validationErrors)) {
            \Log::error("Order Delivery Status Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $deliveryData = Order::find($_GET['id']);
        if (isset($_GET['id'])) {
            $getKeys = true;
            $deliveryStatus = deliveryStatus('', $getKeys);
            if (in_array($request->order_delivery_status, $deliveryStatus)) {
                if ($request->order_delivery_status == $deliveryData->order_delivery_status) {
                    errorMessage('Order is Already in ' . deliveryStatus($request->order_delivery_status) . ' Status.', $msg_data);
                }
                $tableObject = Order::find($_GET['id']);
                $msg = "Delivery Status Updated Successfully";
            } else {
                errorMessage('Delivery Status Does not Exists.', $msg_data);
            }
        }
        $tableObject->order_delivery_status = $request->order_delivery_status;
        if ($request->order_delivery_status ==  'processing') {
            $tableObject->processing_datetime = date('Y-m-d H:i:s');
        }
        if ($request->order_delivery_status ==  'out_for_delivery') {
            $tableObject->out_for_delivery_datetime = date('Y-m-d H:i:s');
        }
        if ($request->order_delivery_status ==  'delivered') {
            $tableObject->delivery_datetime = date('Y-m-d H:i:s');
        }
        $tableObject->updated_at = date('Y-m-d H:i:s');
        $tableObject->updated_by =  session('data')['id'];
        $tableObject->save();

        //fcm notification to customer after delivery status update
        $can_send_fcm_notification =  DB::table('general_settings')->where('type', 'trigger_customer_fcm_notification')->value('value');
        if ($can_send_fcm_notification == 1) {
            $this->callAdminOrderDeliveryFcmNotification($deliveryData['user_id'], $deliveryData['id']);
        }

        successMessage($msg, $msg_data);
    }

    /*
    *Created By: Pradyumn, 
    *Created At : 9-sept-2022, 
    *uses: call order delivery fcm notification for admin 
    */
    private function callAdminOrderDeliveryFcmNotification($user_id, $order_id)
    {
        $landingPage = 'Order';
        if ((!empty($user_id) && $user_id > 0) && (!empty($order_id) && $order_id > 0)) {
            $orderData = Order::find($order_id);
            $product_name =  DB::table('products')->where('id', $orderData['product_id'])->value('product_name');

            $notificationData = MessageNotification::where([['user_type', 'customer'], ['notification_name', 'admin_update_delivery_status'], ['status', 1]])->first();

            if (!empty($notificationData)) {
                $notificationData['type_id'] = $order_id;

                if (!empty($notificationData['notification_image']) && file_exists(URL::to('/') . '/storage/app/public/uploads/notification/customer' . $notificationData['notification_image'])) {
                    $notificationData['image_path'] = getFile($notificationData['notification_image'], 'notification/customer');
                }

                if (empty($notificationData['page_name'])) {
                    $notificationData['page_name'] = $landingPage;
                }

                $formatted_id = getFormatid($order_id, 'order');
                $delivery_status = deliveryStatus($orderData['order_delivery_status']);
                $notificationData['title'] = str_replace('$$order_id$$', $formatted_id, $notificationData['title']);
                $notificationData['body'] = str_replace('$$product_name$$', $product_name, $notificationData['body']);
                $notificationData['body'] = str_replace('$$delivery_status$$', $delivery_status, $notificationData['body']);
                $userFcmData = DB::table('users')->select('users.id', 'customer_devices.fcm_id','customer_devices.imei_no','customer_devices.remember_token')
                    ->where([['users.id', $user_id], ['users.status', 1], ['users.fcm_notification', 1], ['users.approval_status', 'accepted'], ['users.deleted_at', NULL]])
                    ->leftjoin('customer_devices', 'customer_devices.user_id', '=', 'users.id')
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
                    sendFcmNotification($devices_data, $notificationData, 'customer', $user_id);
                }
            }
        }
    }


    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  To load update order payment status page
     *   @param int $id
     *   @return Response
     */
    public function updateOrderPayment($id)
    {
        $data['data'] = Order::with('user', 'product', 'vendor', 'currency')->find($id);
        $data['deliveryStatus'] = deliveryStatus();
        $data['customerPaymentStatus'] = customerPaymentStatus();
        $data['onlinePaymentMode'] = onlinePaymentMode();
        $data['order_id'] = getFormatid($data['data']->id, 'orders');
        return view('backend/order/order_list/order_payment_status_update', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  To store order payment status in table
     *   @param Request request
     *   @return Response
     */
    public function updatePaymentStatusData(Request $request)
    {
        $msg_data = array();
        $msg = "";
        $validationErrors = $this->validatePaymentRequest($request);
        if (count($validationErrors)) {
            \Log::error("Order Payment Status Validation Exception: " . implode(", ", $validationErrors->all()));
            errorMessage(implode("\n", $validationErrors->all()), $msg_data);
        }
        $orderData = Order::find($_GET['id']);
        if (isset($_GET['id'])) {
            $getKeys = true;
            $customerPaymentStatus = customerPaymentStatus('', $getKeys);
            $onlinePaymentMode = onlinePaymentMode('', $getKeys);
            if (in_array($request->payment_status, $customerPaymentStatus) && in_array($request->payment_mode, $onlinePaymentMode)) {
                // $tableObject = OrderPayment::find($_GET['id']);
                if ($request->payment_status == 'pending') {
                    errorMessage('Order is Already in Pending Status, Please Select Another Status', $msg_data);
                } elseif ($request->amount == 0) {
                    errorMessage('Entered Amount Should be Greater Than Zero', $msg_data);
                } elseif (($request->payment_status == 'fully_paid') && ($request->amount != $orderData->customer_pending_payment)) {
                    errorMessage('Please Enter Proper Amount for Selected Status.', $msg_data);
                } elseif (($request->payment_status == 'semi_paid') && ($request->amount == $orderData->customer_pending_payment)) {
                    errorMessage('Please Select Proper Status for Entered Amount.', $msg_data);
                } elseif ($orderData->customer_pending_payment >= $request->amount) {
                    $msg = "Payment Status Updated Successfully";
                } else {
                    errorMessage('Amount Should be Less Than or Equal To Pending Payment', $msg_data);
                }
            } else {
                errorMessage('Payment Status Does not Exists.', $msg_data);
            }
        }
        $tableObject  = new OrderPayment;
        $tableObject->user_id = $request->user_id;
        $tableObject->order_id = $_GET['id'];
        $tableObject->product_id = $request->product_id;
        $tableObject->vendor_id = $request->vendor_id;
        $tableObject->payment_mode = $request->payment_mode;
        $tableObject->payment_status = $request->payment_status;
        $tableObject->amount = $request->amount;
        $tableObject->transaction_date = $request->transaction_date;
        if ($request->remark != '') {
            $tableObject->remark = $request->remark;
        } else {
            $tableObject->remark = '';
        }
        $tableObject->created_at = date('Y-m-d H:i:s');
        $tableObject->created_by =  session('data')['id'];
        $tableObject->save();
        $last_inserted_id = $tableObject->id;
        if ($request->hasFile('order_image')) {
            $image = $request->file('order_image');
            $actualImage = saveSingleImage($image, 'order_payment', $last_inserted_id);
            $thumbImage = createThumbnail($image, 'order_payment', $last_inserted_id, 'order_payment');
            $bannerObj = OrderPayment::find($last_inserted_id);
            $bannerObj->order_payment_image = $actualImage;
            $bannerObj->order_payment_thumb_image = $thumbImage;
            $bannerObj->save();
        }
        //decreasing customer pending_payment by amount in order table
        Order::where('id',  $_GET['id'])->decrement('customer_pending_payment', $request->amount);
        if (($request->payment_status == 'fully_paid') && ($request->amount == $orderData->customer_pending_payment)) {
            Order::where("id", '=',  $_GET['id'])->update(['customer_payment_status' => 'fully_paid']);
            successMessage($msg, $msg_data);
        }
        successMessage($msg, $msg_data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  To view order details
     *   @param int $id
     *   @return Response
     */
    // 'storage_condition', table pending
    public function viewOrder($id)
    {
        $data['data'] = Order::with('user','vendor','product','country','currency','category','sub_category','product_form','packing_type',
                                'vendor_quotation','measurement_unit','packaging_machine','packaging_material','packaging_treatment','recommendation_engine')->where('id', $id)->get();
        $i = 0;
        foreach ($data['data'] as $row) {
            $data['data'][$i]->order_id = getFormatid($row->id, 'orders');
            $data['data'][$i]->billing_details = json_decode($row->billing_details, TRUE);
            $data['data'][$i]->shipping_details = json_decode($row->shipping_details, TRUE);
            $i++;
        }
        return view('backend/order/order_list/order_view', $data);
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-April-2022
     *   Uses :  delivery status Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validateRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'order_delivery_status' => 'string|required',
        ])->errors();
    }

    /**
     *   created by : Pradyumn Dwivedi
     *   Created On : 04-Mar-2022
     *   Uses :  payment status Form Validation part will be handle by below function
     *   @param Request request
     *   @return Response
     */
    private function validatePaymentRequest(Request $request)
    {
        return \Validator::make($request->all(), [
            'payment_status' => 'required|string',
            'payment_mode' => 'required|string',
            'amount' => 'required|numeric',
            'transaction_date' => 'required|date',
            'order_image' => 'nullable|mimes:jpeg,png,jpg|mimes:jpeg,png,jpg|max:'.config('global.SIZE.ORDER_PAYMENT'),
        ])->errors();
    }

    /**
     *   created by : Maaz Ansari
     *   Created On : 10-Jun-2022
     *   Uses :  Print Order pdf
     *   @param Request request
     *   @return Response
     */
    public function orderPdf($enc_id)
    {
        try {
            \Log::info("Order Invoice Generation Starts " . Carbon::now()->format('H:i:s:u'));
            $main_table = 'orders';
            $id = Crypt::decrypt($enc_id);
            $orderFormatedId = getFormatid($id, 'orders');
            $data = DB::table('orders')->select(
                'orders.id',
                'orders.product_weight',
                'orders.vendor_amount',
                'orders.vendor_pending_payment',
                'orders.vendor_payment_status',
                'orders.order_delivery_status',
                'orders.product_quantity',
                'orders.mrp',
                'orders.gst_type',
                'orders.gst_percentage',
                'orders.freight_amount',
                'orders.gst_amount',
                'orders.sub_total',
                'orders.grand_total',
                'orders.shipping_details',
                'orders.billing_details',
                'orders.shelf_life',
                'orders.delivery_datetime',
                'orders.created_at',
                'vendors.vendor_name',
                'vendors.vendor_company_name',
                'vendors.vendor_email',
                'vendors.gstin',
                'vendors.vendor_address',
                'vendors.phone',
                'users.name',
                'users.email',
                'users.phone as user_phone',
                'users.gstin as user_gstin',
                'categories.category_name',
                'sub_categories.sub_category_name',
                'products.product_name',
                'products.product_description',
                'measurement_units.unit_name',
                'measurement_units.unit_symbol',
                'countries.country_name',
                'countries.phone_code',
                'currencies.currency_name',
                'currencies.currency_symbol',
                'storage_conditions.storage_condition_title',
                'packaging_machines.packaging_machine_name',
                'product_forms.product_form_name',
                'packing_types.packing_name',
                'packaging_treatments.packaging_treatment_name',
                'recommendation_engines.engine_name',
                'recommendation_engines.structure_type',
                'recommendation_engines.min_shelf_life',
                'recommendation_engines.max_shelf_life',
                'packaging_materials.packaging_material_name',
                'packaging_materials.material_description',
                'customer_enquiries.address',
                'states.state_name',
                'cities.city_name',
            )
                ->leftjoin('categories', 'orders.category_id', '=', 'categories.id')
                ->leftjoin('vendors', 'orders.vendor_id', '=', 'vendors.id')
                ->leftjoin('users', 'orders.user_id', '=', 'users.id')
                ->leftjoin('sub_categories', 'orders.sub_category_id', '=', 'sub_categories.id')
                ->leftjoin('products', 'orders.product_id', '=', 'products.id')
                ->leftjoin('measurement_units', 'orders.measurement_unit_id', '=', 'measurement_units.id')
                ->leftjoin('countries', 'orders.country_id', '=', 'countries.id')
                ->leftjoin('currencies', 'orders.currency_id', '=', 'currencies.id')
                ->leftjoin('storage_conditions', 'orders.storage_condition_id', '=', 'storage_conditions.id')
                ->leftjoin('packaging_machines', 'orders.packaging_machine_id', '=', 'packaging_machines.id')
                ->leftjoin('product_forms', 'orders.product_form_id', '=', 'product_forms.id')
                ->leftjoin('packing_types', 'orders.packing_type_id', '=', 'packing_types.id')
                ->leftjoin('packaging_treatments', 'orders.packaging_treatment_id', '=', 'packaging_treatments.id')
                ->leftjoin('recommendation_engines', 'orders.recommendation_engine_id', '=', 'recommendation_engines.id')
                ->leftjoin('packaging_materials', 'orders.packaging_material_id', '=', 'packaging_materials.id')
                ->leftjoin('customer_enquiries', 'orders.customer_enquiry_id', '=', 'customer_enquiries.id')
                ->leftjoin('states', 'customer_enquiries.state_id', '=', 'states.id')
                ->leftjoin('cities', 'customer_enquiries.city_id', '=', 'cities.id')
                ->where([[$main_table . '' . '.id', $id], [$main_table . '' . '.deleted_at', NULL]])->first();


            $invoice_date = Carbon::now()->format('d/m/Y');
            !empty($data->created_at) ? $order_date = Carbon::parse($data->created_at)->format('d/m/Y') : $order_date = 'Order Date Not Found';
            !empty($data->delivery_datetime) ?  $delivery_date = Carbon::parse($data->delivery_datetime)->format('d/m/Y') : $delivery_date = 'Delivery Date Not Found';

            $shipping_data = json_decode($data->shipping_details);
            $billing_data = json_decode($data->billing_details);

            if (!empty($data->gst_type) &&  $data->gst_type == 'Igst') {
                $cgst = $cgst_amount =  $sgst = $sgst_amount = $dc_cgst = $dc_cgst_amount = $dc_sgst = $dc_sgst_amount =  0;
                $igst = $dc_igst = $data->gst_percentage ?? 0;
                $igst_amount = $data->gst_amount ?? 0;

                //reverse calculation
                $dc_amount = $data->freight_amount ?? 0;
                $dc_tax_val = round($dc_amount / (1 + ($dc_igst / 100)), 2);
                $dc_igst_amount = $dc_amount - $dc_tax_val;
            } else {

                $cgst = $sgst = $dc_cgst = $dc_sgst = $data->gst_percentage ? ($data->gst_percentage / 2) : 0;
                $cgst_amount = $sgst_amount = $data->gst_amount ? ($data->gst_amount / 2) : 0;

                $dc_amount = $data->freight_amount ?? 0;
                $dc_tax_val = round($dc_amount / (1 + ((2 * $dc_cgst) / 100)), 2);
                $dc_tax_amount = $dc_amount - $dc_tax_val;
                $dc_cgst_amount = $dc_sgst_amount  = round($dc_tax_amount / 2, 2);

                $igst =  $igst_amount = $dc_igst = $dc_igst_amount = 0;
            }

            $grand_total = ($data->grand_total ?? 0) + ($dc_amount ?? 0);

            //added by : Pradyumn, added on : 18-Oct-2022, Use : To display solution sub total amount
            $solution_sub_total = ($data->sub_total ?? 0) + ($sgst_amount ?? 0) + ($cgst_amount ?? 0) + ($igst ?? 0);
            // $in_words = convertNumberToWord($grand_total);
            $in_words = currencyConvertToWord($grand_total);
            $financialYear = (date('m') > 4) ?  date('Y') . '-' . substr((date('Y') + 1), -2) : (date('Y') - 1) . '-' . substr(date('Y'), -2);


            $adminBankName = GeneralSetting::where("type", 'admin_bank_name')->first();
            $adminAccountName = GeneralSetting::where("type", 'admin_benificiary_name')->first();
            $adminBankAccountNo = GeneralSetting::where("type", 'admin_account_no')->first();
            $adminBankIfsc = GeneralSetting::where("type", 'admin_ifsc')->first();


            $result = [
                'data' => $data,
                'orderFormatedId' => $orderFormatedId,
                'invoice_date' => $invoice_date,
                'order_date' => $order_date,
                'delivery_date' => $delivery_date,
                'shipping_data' => $shipping_data,
                'billing_data' => $billing_data,
                'cgst' => $cgst,
                'dc_cgst' => $dc_cgst,
                'cgst_amount' => $cgst_amount,
                'dc_cgst_amount' => $dc_cgst_amount,
                'sgst' => $sgst,
                'dc_sgst' => $dc_sgst,
                'sgst_amount' => $sgst_amount,
                'dc_sgst_amount' => $dc_sgst_amount,
                'igst' => $igst,
                'dc_igst' => $dc_igst,
                'igst_amount' => $igst_amount,
                'dc_amount' => $dc_amount,
                'dc_tax_val' => $dc_tax_val,
                'dc_igst_amount' => $dc_igst_amount,
                'in_words' => $in_words,
                'financialYear' => $financialYear,
                'admin_bank_name' => $adminBankName->value ?? '',
                'admin_benificiary_name' => $adminAccountName->value ?? '',
                'admin_account_no' => $adminBankAccountNo->value ?? '',
                'admin_ifsc' => $adminBankIfsc->value ?? '',
                // 'no_image' => getFile('packarma_logo.png', 'notification'),
                'no_image' => URL::to('/') . '/public/backend/img/Packarma_logo.png',
                'solution_sub_total' => $solution_sub_total,
            ];
            // $result['data'] = $data;
            // echo '<pre>';
            // print_r($billing_data);
            // echo '</pre>';
            // die;
            $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $html =  view('backend/order/order_list/order_pdf', $result);

            $pdf->SetTitle('Order Invoice');
            $pdf->AddPage();
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->Output('Order Invoice.pdf', 'I');
        } catch (\Exception $e) {
            \Log::error("Order Invoice Generation Failed " . $e->getMessage());
            return redirect()->back()->withErrors(array("msg" => "Something went wrong"));
        }
    }


    public function expirePdf()
    {
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle('TCPDF Example 002');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // set font
        $pdf->SetFont('times', 'BI', 20);

        // add a page
        $pdf->AddPage();

        // set some text to print
        $txt = <<<EOD
                The link is broken please try after some time
                EOD;

        // print a block of text using Write()
        $pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

        // ---------------------------------------------------------

        //Close and output PDF document
        $pdf->Output('example_002.pdf', 'I');
    }
}
